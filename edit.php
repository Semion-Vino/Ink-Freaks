<?php require_once 'app/helpers.php';
session_start();
if (!user_auth()) { //IF THE USER ISN'T LOGGED IN, REDIRECT HIM TO LOGIN PAGE
  header('location: login.php');
  exit;
}

if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {

  $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_STRING);

  if ($pid) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
    //mysqli_query($link, "SET NAMES UTF8"); OPTION 1
    mysqli_set_charset($link, 'utf8');
    $uid = $_SESSION['user_id'];
    $pid = mysqli_real_escape_string($link, $pid);
    $sql = "SELECT * FROM posts WHERE id=$pid AND user_id=$uid";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
      $post = mysqli_fetch_assoc($result);
      $title = $post['title'];
      $article = $post['article'];
    } else {
      header('location: blog.php');
    }
  } else {
    header('location: blog.php');
  }
} else {
  header('location: blog.php');
}



$errors = [
  'title' => '',
  'article' => '',
];



if (isset($_POST['submit'])) { //IF THE USER PRESSED ON SUBMIT
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

  //$title = !empty($_POST['title']) ? trim($_POST['title']) : ''; <-UNSECURED AGAINST XSS ATTACK
  // $article = !empty($_POST['article']) ? trim($_POST['article']) : '';  //IF THE FIELDS AREN'T EMPTY, TRIM THEM
  $form_valid = true;

  if (!$title || mb_strlen($title) < 2) {
    $form_valid = false;
    $errors['title'] = 'Title must be 2 character minimum';
  }
  if (!$article || mb_strlen($article) < 2) {
    $form_valid = false;
    $errors['article'] = 'Article must be 2 character minimum';
  }
  if ($form_valid) {

    $title = mysqli_real_escape_string($link, $title); //CLEAR THE INPUTS FROM POTENTIAL SINGLE QUOTES
    $title = htmlentities($title);
    $article = mysqli_real_escape_string($link, $article);
    $article = htmlentities($article);
    $sql = "UPDATE posts SET title='$title', article='$article' WHERE id=$pid";
    $result = mysqli_query($link, $sql);

    $_SESSION['updated'] = true;
    header("location: post.php?id=$pid");
  }
}



$page_title = 'Edit Post';
$body_class = 'editpost'; ?>
<?php include 'tpl/header.php' ?>
<div class="container">


  <div class="row mt-5 mb-5">
    <div class="col-8 mx-auto d-block">
      <h1 class='text-white display-3 '>Edit Post</h1>
      <form action="" method='POST' novalidate='novalidate' autocomplete='off'>
        <div class="form-group">
          <label for="" class='text-white'>Title:</label>
          <input type="text" value='<?= $title ?>' name='title' id='title' class='form-control'>
        </div>
        <span class="text-danger"><?= $errors['title'] ?></span>

        <div class="form-group">
          <label for="" class='text-white'>Article:</label>
          <textarea name="article" id="article" cols="30" rows="10" class='form-control'><?= $article ?></textarea>
          <div class="row">
            <span class="text-danger ml-2"><?= $errors['article'] ?></span>
          </div>
          <div class="row">
            <div class="col-6">
              <input type="submit" value='Update' name='submit' class='btn btn-warning mt-3'>
            </div>
            <div class="col-6">

              <a href="blog.php"> <input type="button" value='Cancel' class='btn btn-light float-right mt-3 '></a>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>


</div>
<?php include 'tpl/footer.php' ?>