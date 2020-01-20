<?php
require_once 'app/helpers.php';
session_start();

$page_title = 'Home';
$body_class = 'home'; ?>
<?php include 'tpl/header.php' ?>

<div class="container-fluid black-bg">
  <h1 class='text-center d-flex justify-content-center display-2 text-white first'>A shared passion for tattoos</h1>
  <p class="text-center text-white welcome first">Welcome to Ink Freaks!<br> We are a social platform focusing on tattoos and body modifications </p>

  <?php if (!isset($_SESSION['user_id'])) : ?>
    <button type="button" class="btn-click-effect mx-auto d-block second"><a href="login.php" class='card-link'> Join The Party </a></button>
  <?php else : ?>
    <h2 class='text-white text-center text-warning pt-4 second'>Welcome back <?= htmlentities($_SESSION['user_name']) ?>!</h2>
  <?php endif ?>
</div>

<script>
  ScrollReveal().reveal('.first', {
    delay: 150,
    easing: 'cubic-bezier(1,1,.0,0)',
    duration: 1000,
  });
  ScrollReveal().reveal('.second', {
    delay: 800,
    easing: 'cubic-bezier(1,1,.0,0)',
    duration: 1000,
  });
</script>

<div class="separatorContainer">
  <section class="ss-style-doublediagonal">
  </section>
</div>


<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/carousel10.jpg" class="d-block w-100 carousel-img" alt="tattoo">
    </div>
    <div class="carousel-item">
      <img src="images/carousel11.jpg" class="d-block w-100 carousel-img" alt="tattoo">
    </div>
    <div class="carousel-item">
      <img src="images/carousel12.jpg" class="d-block w-100 carousel-img" alt="tattoo">
    </div>
    <div class="carousel-item">
      <img src="images/carousel13.jpg" class="d-block w-100 carousel-img" alt="tattoo">
    </div>
    <div class="carousel-item">
      <img src="images/carousel15.jpg" class="d-block w-100 carousel-img" alt="tattoo">
    </div>

    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <?php include 'tpl/footer.php';

  if (isset($_SESSION['logged'])) : ?>
    <script>
      toastr["success"]("Logged In");
    </script>
  <?php unset($_SESSION['logged']);
  endif ?>