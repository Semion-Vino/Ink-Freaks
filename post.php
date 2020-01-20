<?php
require_once 'app/helpers.php';

session_start();
if (!isset($_GET['id'])) {
  header('location: blog.php');
  exit;
} else {
  $id = $_GET['id'];
}
$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
//PREVENT GIBBERISH IN CASE OF HEBREW POST ↓
mysqli_set_charset($link, 'utf8');
$sql = "SELECT u.first_name,u.last_name,up.profile_img,up.city,up.country, p.*, DATE_FORMAT(p.date,'%d/%m/%y  |  %H:%i') pdate 
FROM posts p 
JOIN users u ON u.id=p.user_id 
JOIN user_profile up ON u.id=up.user_id
WHERE p.id=$id";
$result = mysqli_query($link, $sql);
$post = mysqli_fetch_assoc($result);

$body_class = 'post';
$page_title = $post['title']
?>
<?php include 'tpl/header.php' ?>

<div class="container " class="align-middle">
  <div class="row">
    <div class="col-12">


    </div>
  </div>
  <div class="row">

    <div class="col-12">

      <div class="card mt-5">

        <div class="card-header text-white">
          <img style='width:80px; height:80px;' src="images/<?= $post['profile_img']; ?>" alt="" class='rounded-circle float-left'>
          <span class='float-left ml-3 mt-2 poster-name p-0 m-0'><?= htmlentities($post['first_name']) . ' ' . htmlentities($post['last_name']); ?></span>
          <br>
          <br>

          <?php if ($post['city'] && $post['country']) : ?>
            <small class='text-muted float-left ml-3 p-0'><?= $post['city'] . ', ', $post['country'] ?></small>
          <?php endif ?>

          <span class='mt-2 float-right poster-name date'>
            <?= $post['pdate']; ?>
          </span>

        </div>

        <div class="card-body">

          <h4 class='text-left'><?= htmlentities($post['title']); ?></h4>


          <!--CONVERT LINE BREAK TO <BR> ELEMENT-->

          <p class='text-left'><?= str_replace("\n", '<br>', htmlentities($post['article'])); ?></p>

          <!--THREE DOTS DISPLAY-->

          <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) : ?>

            <div class="dropdown float-right">
              <a class="" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
              </a>

              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="edit.php?pid=<?= $post['id'] ?>"><i class="far fa-edit"></i> Edit </a>
                <a class="dropdown-item delete-post" href="delete.php?pid=<?= $post['id'] ?>"><i class="fas fa-trash-alt"></i> Delete</a>
              </div>
            </div>
          <?php endif ?>
        </div>
      </div>
      <a href="blog.php" class="btn btn-warning float-left mt-5 "><i class="fas fa-arrow-circle-left"></i> Blog</a>
    </div>
  </div>
</div>
</div>

<?php include 'tpl/footer.php';
if (isset($_SESSION['added'])) : ?>

  <script>
    toastr["success"]("Added");
  </script>
<?php unset($_SESSION['added']);
elseif (isset($_SESSION['updated'])) : ?>

  <script>
    toastr["success"]("Updated");
  </script>

<?php unset($_SESSION['updated']);

elseif (isset($_SESSION['deleted'])) : ?>

  <script>
    toastr["success"]("Deleted");
  </script>

<?php unset($_SESSION['deleted']);
endif ?>