<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= media() ?>/img/favicon.png">
    <title>
        Binary Dreamers | login
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="<?= media() ?>/css/fonts-family-google.css" />
    <!-- Nucleo Icons -->
    <link href="<?= media() ?>/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?= media() ?>/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= media() ?>/vendor/sweetalert/sweetalert2.min.css">
    <link id="pagestyle" href="<?= media() ?>/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="bg-gray-200">

    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Iniciar Sesion</h4>
                                    <div class="row mt-3">
                                        <div class="col-2 text-center ms-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-facebook text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 text-center px-1">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-github text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 text-center me-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-google text-white text-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formLogin" method="POST" class="text-start" novalidate>
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Telefono</label>
                                        <input type="text" id="txtTelefono" name="txtTelefono" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Contraseña</label>
                                        <input type="password" id="txtPassword" name="txtPassword" class="form-control" required>
                                    </div>
                                    <!-- <div class="form-check form-switch d-flex align-items-center mb-3">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                        <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                                    </div> -->
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Iniciar Sesion</button>
                                    </div>
                                    <!--  <p class="mt-4 text-sm text-center">
                                        Don't have an account?
                                        <a href="../pages/sign-up.html" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <?php footer_login($data); ?>
</body>

</html>