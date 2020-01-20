<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Cinzel|Rokkitt:100&display=swap" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />

  <link rel="stylesheet" href='newcss.css'>

  <link rel="icon" href="images/faicon2.png">


  <title>Ink Freaks <?= '| ' . $page_title ?></title>
</head>

<body class=<?= $body_class ?>>
  <nav class="navbar navbar-expand-md navbar-light">
    <div class="container">
      <button class="navbar-toggler bg-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto left-nav">

          <li class="nav-item">
            <a class="nav-link text-warning" href="about.php">About <i class="fas fa-info-circle"></i></a>
          </li>
          <li class="nav-item useless">
            <a class="nav-link text-warning " id='useless' href="#">|</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning" href="blog.php">Blog <i class="fas fa-comments"></i></a>
          </li>
        </ul>


        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <div class="nav-space"></div>
          </li>
        </ul>
        <ul class="navbar-nav mr-auto">
          <?php if (!isset($_SESSION['user_id'])) : ?>
            <!--IF USER ISN'T CONNECTED, SHOW HIM THESE BUTTONS-->
            <li class="nav-item">
              <a class="nav-link text-warning" href="login.php">Log in <i class="fas fa-sign-in-alt text-warning"> </i> </a>
            </li>
            <li class='nav-item useless'>
              <a class="nav-link text-warning" id='useless' href="#">|</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-warning" href="signup.php">Sign Up <i class="fas fa-user-plus"> </i> </a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link text-warning ml-4" href="myprofile.php">My Profile <img style='width:30px; height:30px;' src="images/<?= $_SESSION['user_img']; ?>" alt="" class='ml-2 rounded-circle'></i> </a>
            </li>
            <li class='nav-item useless'>
              <a class="nav-link text-warning" id='useless' href="#">|</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-warning" href="logout.php">Log Out <i class="fas fa-sign-out-alt"></i></i> </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <a href="index1.php"><img src="images/logo.png" alt="" class="logo"></a>