<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
class UserController extends BaseController{

    public function register() {


        return view('users/register');
    }

    public function store()
    {
        // Lakukan validasi data jika diperlukan
        $validationRules = [
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email field is required.',
                    'valid_email' => 'Invalid email format.',
                    'is_unique' => 'Email already exists in the database.'
                ]
            ],
            'name' => 'required',
            'password' => 'required|min_length[6]',
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,1024]|ext_in[profile_picture,jpg,png]'
        ];
        
    
        if (!$this->validate($validationRules)) {
            // Jika validasi gagal, tampilkan pesan kesalahan dan kembali ke halaman pendaftaran
            $validation = $this->validator;
            return redirect()->back()->withInput()->with('validation', $validation);
        }
    
        // Tangkap data dari formulir pendaftaran
        $email = $this->request->getVar('email');
        $name = $this->request->getVar('name');
        $password = $this->request->getVar('password');
        $profilePicture = $this->request->getFile('profile_picture');
    
        // Hash password menggunakan bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        // Simpan gambar ke direktori public/img/user dengan nama yang acak
        $newName = $profilePicture->getRandomName();
        $profilePicture->move(ROOTPATH . 'public/img/user', $newName);
    
        // Simpan data ke database
        $userModel = new UserModel();
        $userModel->insert([
            'email' => $email,
            'name' => $name,
            'password' => $hashedPassword,
            'profile_picture' => $newName,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    
        // Redirect ke halaman login
        return redirect()->to('/user/login');
    }
    
    public function login()
    {
        return view('users/login');
    }
    

    public function processLogin()
{
    // Tangkap data dari formulir login
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');

    // Lakukan validasi data jika diperlukan
    $validationRules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_length[6]'
    ];

    if (!$this->validate($validationRules)) {
        // Jika validasi gagal, tampilkan pesan kesalahan dan kembali ke halaman login
        $validation = $this->validator;
        return redirect()->back()->withInput()->with('validation', $validation);
    }

    // Proses autentikasi
    $userModel = new UserModel();
    $user = $userModel->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        // Autentikasi berhasil, simpan data ke sesi
        $session = session();
        $session->set('user_id', $user['id']);
        $session->set('email', $user['email']);
        $session->set('name', $user['name']);
        $session->set('profile_picture', $user['profile_picture']);

        // Redirect ke halaman landing
        return redirect()->to('/user/landing/' . $user['id']);
    } else {
        // Autentikasi gagal, kembali ke halaman login dengan pesan kesalahan
        $data['error'] = 'Invalid email or password.';
        return view('users/login', $data);
    }
}


    public function landing($userId)
    {
        // Ambil data pengguna dari database berdasarkan ID
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Tampilkan halaman landing dengan data pengguna
        echo view('users/landing', $user);
    }

    public function logout()
    {
        // Hapus data sesi dan redirect ke halaman login
        $session = session();
        $session->remove('user_id');
        $session->remove('email');
        $session->remove('name');
        $session->remove('profile_picture');

        return redirect()->to('/user/login');
    }


    public function forgotPassword()
    {
        return view('users/forgot_password');
    }
    
    public function sendResetLink()
    {
        // Tangkap input email dari form
        $email = $this->request->getVar('email');
    
        // Lakukan validasi input email
        $validationRules = [
            'email' => 'required|valid_email'
        ];
    
        if (!$this->validate($validationRules)) {
            // Jika validasi gagal, tampilkan pesan kesalahan dan kembali ke halaman lupa password
            $validation = $this->validator;
            return redirect()->to('/forgot-password')->withInput()->with('validation', $validation);
        }
    
        // Periksa apakah email terdaftar
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
    
        if ($user) {
            // Ubah zona waktu ke Asia/Jakarta
            date_default_timezone_set('Asia/Jakarta');
    
            // Generate token reset password dan atur waktu kadaluarsa
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
            // Update field reset_token dan reset_token_expires_at pada tabel pengguna
            $userModel->update($user['id'], [
                'reset_token' => $token,
                'reset_token_expires_at' => $expiresAt
            ]);
    
            // Redirect ke halaman reset password dengan mengirim token sebagai parameter
            return redirect()->to('/user/reset-password/' . $token);
        } else {
            // Tampilkan pesan error jika email tidak terdaftar
            return redirect()->to('/user/forgot-password')->with('error', 'Email not found.');
        }
    }
    
    public function resetPassword($token)
    {
        // Periksa apakah token reset password valid
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
            ->first();
    
        if ($user) {
            // Tampilkan halaman reset password dengan mengirim token sebagai data
            return view('users/reset_password', ['token' => $token]);
        } else {
            // Tampilkan pesan error jika token tidak valid atau telah kadaluarsa
            return redirect()->to('/user/forgot-password')->with('error', 'Invalid or expired token.');
        }
    }
    
    public function updatePassword($token)
    {
        // Periksa apakah token reset password valid
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
            ->first();
    
        if ($user) {
            // Tampilkan halaman update password dengan mengirim token sebagai data
            return view('users/update_password', ['token' => $token]);
        } else {
            // Tampilkan pesan error jika token tidak valid atau telah kadaluarsa
            return redirect()->to('/user/forgot-password')->with('error', 'Invalid or expired token.');
        }
    }
    
    public function submitUpdatePassword()
{
    // Tangkap input password dan token dari form
    $password = $this->request->getVar('password');
    $confirmPassword = $this->request->getVar('confirm_password');
    $token = $this->request->getVar('token');

    // Lakukan validasi input password dan konfirmasi password
    $validationRules = [
        'password' => 'required|min_length[6]|matches[confirm_password]'
    ];

    if (!$this->validate($validationRules)) {
        // Jika validasi gagal, tampilkan pesan kesalahan dan kembali ke halaman update password
        $validation = $this->validator;
        return redirect()->to('/user/update-password/' . $token)->withInput()->with('validation', $validation);
    }

    // Set zona waktu ke Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');

    // Periksa apakah token reset password valid
    $userModel = new UserModel();
    $user = $userModel->where('reset_token', $token)
        ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
        ->first();

    if ($user) {
        // Update password user dengan password baru
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userModel->update($user['id'], [
            'password' => $hashedPassword,
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        // Redirect ke halaman login atau halaman sukses
        return redirect()->to('/user/login')->with('success', 'Password updated successfully. You can now login with your new password.');
    } else {
        // Tampilkan pesan error jika token tidak valid atau telah kadaluarsa
        return redirect()->to('/user/forgot-password')->with('error', 'Invalid or expired token.');
    }
}

    




}