<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>error page 404</title>
  <link rel="stylesheet" href="<?= media() ?>/css/error.css">
  <link rel="icon" type="image/png" href="<?= media() ?>/img/favicon.png">
</head>

<body>

  <header class="top-header">
  </header>

  <!--dust particel-->
  <div>
    <div class="starsec"></div>
    <div class="starthird"></div>
    <div class="starfourth"></div>
    <div class="starfifth"></div>
  </div>
  <!--Dust particle end--->


  <div class="lamp__wrap">
    <div class="lamp">
      <div class="cable"></div>
      <div class="cover"></div>
      <div class="in-cover">
        <div class="bulb"></div>
      </div>
      <div class="light"></div>
    </div>
  </div>
  <!-- END Lamp -->
  <section class="error">
    <!-- Content -->
    <div class="error__content">
      <div class="error__message message">
        <h1 class="message__title">Page Not Found</h1>
        <p class="message__text">Lo sentimos, la página que buscas no se encuentra aquí. El enlace que seguiste puede estar roto o ya no existe. Vuelve a intentarlo o echa un vistazo a la ruta</p>
      </div>
      <div class="error__nav e-nav">
        <a href="<?= base_url() ?>/login" target="_blanck" class="e-nav__link"></a>
      </div>
    </div>
    <!-- END Content -->

  </section>

  <!-- partial -->

</body>

</html>