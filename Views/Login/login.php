<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="<?= media() ?>/img/favicon.png">
    <title>BarberShop | Login</title>
    <link rel="stylesheet" type="text/css" href="<?= media() ?>/css/fonts-family-google.css" />
    <link href="<?= media() ?>/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?= media() ?>/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="<?= media() ?>/vendor/sweetalert/sweetalert2.min.css">
    <link id="pagestyle" href="<?= media() ?>/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link rel="stylesheet" href="<?= media() ?>/css/login.css" />
</head>

<body class="bg-gray-200">
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100 login-bg">
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Iniciar Sesión</h4>
                                    <p class="text-white text-sm text-center mt-1 mb-2 opacity-8">BarberShop</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formLogin" method="POST" class="text-start" novalidate>
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" id="txtTelefono" name="txtTelefono" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Contraseña</label>
                                        <input type="password" id="txtPassword" name="txtPassword" class="form-control" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Iniciar Sesión</button>
                                    </div>
                                    <div class="text-center position-relative" style="margin-top: -8px; margin-bottom: 8px;">
                                        <div class="credentials-badge" id="credentialsBadge">
                                            <span class="material-symbols-rounded text-sm me-1" style="font-size:16px; vertical-align:middle">person</span>
                                            <strong>Tel:</strong> 3001234567 &nbsp;|&nbsp;
                                            <span class="material-symbols-rounded text-sm me-1" style="font-size:16px; vertical-align:middle">lock</span>
                                            <strong>Pass:</strong> 12345
                                        </div>
                                        <button type="button" class="btn-credentials" id="btnCredentials" title="Ver credenciales de acceso">
                                            <span class="material-symbols-rounded" style="font-size:20px">visibility</span>
                                        </button>
                                        <span class="text-xs text-secondary ms-1">Ver credenciales</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const btn = document.getElementById('btnCredentials');
        const badge = document.getElementById('credentialsBadge');
        btn.addEventListener('click', () => badge.classList.toggle('show'));
        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !badge.contains(e.target)) {
                badge.classList.remove('show');
            }
        });
    </script>

    <?php footer_login($data); ?>
</body>

</html>
