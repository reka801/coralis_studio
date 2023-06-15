<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Kodinger">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Login Page &mdash; Bootstrap 4 Login Page Snippet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel="stylesheet" type="text/css" href="css/my-login.css"> -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="brand">
                        <img src="/img/logo.jpg" alt="bootstrap 4 login page">
                    </div>
                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title">Login</h4>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo session()->getFlashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                            <form method="POST" class="my-login-validation" novalidate="" action="/user/processLogin">
                                <div class="form-group">
                                    <label for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" required autofocus>
                                    <div class="invalid-feedback">
                                        Your email is invalid
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password
                                        <a href="forgot-password" class="float-right">
                                            Forgot Password?
                                        </a>
                                    </label>
                                    <input id="password" type="password" class="form-control" name="password" required data-eye>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Login
                                    </button>
                                </div>
                                <div class="mt-4 text-center">
                                    Don't have an account? <a href="/user/register">Create One</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                        © Your Company 2019
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        'use strict';

        $(function() {
            // author badge :)
            var author = '<div style="position: fixed;bottom: 0;right: 20px;background-color: #fff;box-shadow: 0 4px 8px rgba(0,0,0,.05);border-radius: 3px 3px 0 0;font-size: 12px;padding: 5px 10px;">By <a href="https://twitter.com/mhdnauvalazhar">@mhdnauvalazhar</a> &nbsp;&bull;&nbsp; <a href="https://www.buymeacoffee.com/mhdnauvalazhar">Buy me a Coffee</a></div>';
            $("body").append(author);

            $("input[type='password'][data-eye]").each(function(i) {
                var $this = $(this),
                    id = 'eye-password-' + i,
                    el = $('#' + id);

                $this.wrap($("<div/>", {
                    style: 'position:relative',
                    id: id
                }));

                $this.css({
                    paddingRight: 60
                });
                $this.after($("<div/>", {
                    html: 'Show',
                    class: 'btn btn-primary btn-sm',
                    id: 'passeye-toggle-'+i,
                }).css({
                        position: 'absolute',
                        right: 10,
                        top: ($this.outerHeight() / 2) - 12,
                        padding: '2px 7px',
                        fontSize: 12,
                        cursor: 'pointer',
                }));

                $this.after($("<input/>", {
                    type: 'hidden',
                    id: 'passeye-' + i
                }));

                var invalid_feedback = $this.parent().parent().find('.invalid-feedback');

                if(invalid_feedback.length) {
                    $this.after(invalid_feedback.clone());
                }

                $this.on("keyup paste", function() {
                    $("#passeye-"+i).val($(this).val());
                });
                $("#passeye-toggle-"+i).on("click", function() {
                    if($this.hasClass("show")) {
                        $this.attr('type', 'password');
                        $this.removeClass("show");
                        $(this).removeClass("btn-outline-primary");
                    }else{
                        $this.attr('type', 'text');
                        $this.val($("#passeye-"+i).val());                
                        $this.addClass("show");
                        $(this).addClass("btn-outline-primary");
                    }
                });
            });

            $(".my-login-validation").submit(function() {
                var form = $(this);
                if (form[0].checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.addClass('was-validated');
            });
        });
    </script>
</body>
</html>
