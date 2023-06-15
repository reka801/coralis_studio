<!DOCTYPE html>
<html>
<head>
    <title>User Landing Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f9fb;
        }
        .container {
            margin-top: 50px;
        }

        .card {
            border-color: transparent;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .05);
        }

        .card-img-top {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .card-text {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0069d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-3 mx-auto">
                <h1>Welcome, <?php echo $name; ?>!</h1>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <div class="card">
                    <img src="/img/user/<?php echo $profile_picture ?>" class="card-img-top" alt="Profile Picture">
                    <div class="card-body">
                        <h5 class="card-title">Profile Information</h5>
                        <p class="card-text"><strong>Name:</strong> <?php echo $name; ?></p>
                        <p class="card-text"><strong>Email:</strong> <?php echo $email; ?></p>
                        <a href="/user/logout" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
