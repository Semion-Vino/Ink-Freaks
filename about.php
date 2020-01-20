<?php session_start();
$page_title = 'About';
$body_class = 'about'; ?>
<?php include 'tpl/header.php' ?>
<div class="container container2">
  <div class="row row1">
    <div class="col-md-6 "><img class='float-right' src="images/carousel4.jpg" alt="">

    </div>


    <div class="col-md-6">
      <h1 class="display-3 text-white">About Us</h1>
      <p class="text-white p-3 ">Ink Freaks was founded by a group of friends united by their shared passion for ink. Dedicated to both artists and human canvasses, Ink Freaks serves as a community providing artistic recognition, inspiration & ideas for body decoration, and a place to connect between tattoo artists, tattoo stock-suppliers, and human canvases.</p>
    </div>
  </div>
  <div class="row row2">
    <div class="col-md-6">
      <h1 class="display-3 text-white">About Us</h1>
      <p class="text-white p-3 ">Ink Freaks was founded by a group of friends united by their shared passion for ink. Dedicated to both artists and human canvasses, Ink Freaks serves as a community providing artistic recognition, inspiration & ideas for body decoration, and a place to connect between tattoo artists, tattoo stock-suppliers, and human canvases.</p>

    </div>
    <script>
      ScrollReveal().reveal('.text-white', {
        delay: 150,
        easing: 'cubic-bezier(1,1,.0,0)'
      });
    </script>
    <div class="col-md-6 "><img class='mx-auto d-block' src="images/carousel4.jpg" alt="">

    </div>
  </div>
</div>

<?php include 'tpl/footer.php' ?>