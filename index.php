<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UnitedKU | Sign in & Sign up Form</title>
    <link rel="stylesheet" href="style/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        #errorModal {
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
</head>

<body>

    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <form action="login.php" method="post" autocomplete="off" class="sign-in-form">
                        <div class="logo">
                            <img src="src/emyu.png" alt="easyclass" />
                            <h4>UnitedKU</h4>
                        </div>

                        <div class="heading">
                            <h2>Welcome Back</h2>
                            <h6>Not registred yet?</h6>
                            <a href="#" class="toggle">Sign up</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" name="user_name" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Username</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" name="user_password" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Password</label>
                            </div>

                            <input type="submit" name="login" value="Sign In" class="sign-btn" />

                            <p class="text">
                                UnitedKU is a web-services to store and track your product for selling.
                            </p>

                        </div>
                    </form>

                    <form action="registration.php" method="post" autocomplete="off" class="sign-up-form">
                        <div class="logo">
                            <img src="src/emyu.png" alt="easyclass" />
                            <h4>UnitedKU</h4>
                        </div>

                        <div class="heading">
                            <h2>Get Started</h2>
                            <h6>Already have an account?</h6>
                            <a href="#" class="toggle">Sign in</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" name="user_name" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Username</label>
                            </div>

                            <div class="input-wrap">
                                <input type="email" name="user_email" class="input-field" autocomplete="off" required />
                                <label>Email</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" name="user_password" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Password</label>
                            </div>

                            <input type="submit" value="Sign Up" class="sign-btn" name="submit" />

                            <p class="text">
                                UnitedKU is a web-services to store and track your product for selling.
                            </p>

                        </div>
                    </form>
                </div>

                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="src/mujaer.png" class="image img-1 show" alt="" />
                        <img src="src/rashy.png" class="image img-2" alt="" />
                        <img src="src/munyuk.png" class="image img-3" alt="" />
                    </div>

                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group">
                                <h2>Utilize Your Selling</h2>
                                <h2>Track Your Product Stock</h2>
                                <h2>Sell The Item</h2>
                            </div>
                        </div>

                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                        </div>
                    </div>
                </div>
    </main>

    <!-- Javascript file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="javascript/app.js"></script>
</body>

</html>