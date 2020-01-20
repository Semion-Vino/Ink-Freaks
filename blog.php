<?php require 'app/helpers.php';
session_start();
$page_title = 'Blog';
$body_class = 'blog';



$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
//PREVENT GIBBERISH IN CASE OF HEBREW POST ↓
mysqli_set_charset($link, 'utf8');

$results_per_page = 5;

if (isset($_GET["page"])) {
  $page  = $_GET["page"];
} else {
  $page = 1;
};

$start_from = ($page - 1) * $results_per_page;

$sql = "SELECT u.first_name,u.last_name,up.profile_img,up.city,up.country, p.*, DATE_FORMAT(p.date,'%d/%m/%y  |  %H:%i') pdate 
FROM posts p 
JOIN users u ON u.id=p.user_id 
JOIN user_profile up ON u.id=up.user_id 
ORDER BY p.date DESC
LIMIT $start_from,$results_per_page";

$result = mysqli_query($link, $sql);

$total_records = mysqli_query($link, "SELECT COUNT(id) FROM posts");
$total_records = mysqli_fetch_assoc($total_records);


$total_pages = ceil($total_records['COUNT(id)'] / $results_per_page);


//QUERY TO DETERMINE WHICH USER HAS THE MOST POSTS ↓
$top_posts_query = "SELECT u.id, u.first_name, u.last_name, COUNT(p.id) cn 
FROM posts p 
JOIN users u ON u.id=p.user_id 
GROUP BY p.user_id 
ORDER BY cn DESC
LIMIT 3";
$top_posters = mysqli_query($link, $top_posts_query); ?>


<?php include 'tpl/header.php' ?>
<main>
  <div class="container p-0 m-5 ">
    <section class='p-0'>
      <div class="row mt-5">
        <div class="col-12">
          <h1 class='text-white display-3 text-left'>Blog</h1>

          <?php if (user_auth()) : ?>
            <a href="addpost.php" class="btn btn-warning float-right  big-only"><i class="far fa-calendar-plus"></i> Add Post</a>
            <a href="addpost.php" class="btn btn-warning float-left small-only"><i class="far fa-calendar-plus"></i> Add Post</a>
          <?php else : ?>
            <span class='text-white float-right trans big-only'><a class='text-white' href="login.php">Log in</a> to create a new post</span>
            <span class='text-white float-left trans small-only'><a class='text-white' href="login.php">Log in</a> to create a new post</span>
          <?php endif ?>
        </div>
      </div>
      <div class="row p-0 m-0">

        <div class="wrap">
          <div class="col-md-1">
            <div class="card names mt-3 bg-dark text-left  text-center">
              <div class="card-header text-white">
                Top Posters
              </div>
              <ul class="list-group list-group-flush">
                <?php $num = 1 ?>
                <?php while ($user = mysqli_fetch_assoc($top_posters)) : ?>
                  <?php echo "<li class='list-group-item'>$num. $user[first_name] $user[last_name] </li>" ?>
                  <?php $num++ ?>

                <?php endwhile ?>
              </ul>
            </div>

          </div>

          <?php while ($post = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-11 p-0">
              <a class='post-link' href="post.php?id=<?= $post['id'] ?>">
                <div class="card mt-5">

                  <div class="card-header text-white">
                    <img style='width:80px; height:80px;' src="images/<?= $post['profile_img']; ?>" alt="" class='rounded-circle float-left'>
                    <span class='float-left ml-3 mt-2 poster-name p-0 m-0'><?= htmlentities($post['first_name']) . ' ' . htmlentities($post['last_name']); ?></span>
                    <br>
                    <br>

                    <?php if ($post['city'] && $post['country']) : ?>
                      <small class='text-muted float-left ml-3 p-0'><?= htmlentities($post['city']) . ', ', $post['country'] ?></small>
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

              </a>

            </div>

          <?php endwhile; ?>

          <script>
            ScrollReveal().reveal('.col-md-11', {
              delay: 230,
              easing: 'cubic-bezier(1,1,.0,0)',

            });
          </script>

        </div>




        <div class="pages">

          <?php if ($page != 1) : ?>
            <a class="btn btn-warning float-left page-button" href='blog.php?page=<?php echo --$page ?>'>
              <?php $page++ ?>
              <!-- //The page++ is to settle a conflict with the 'next' button-->
              Previous page</a>

          <?php endif; ?>


          <?php

          for ($i = 1; $i <= $total_pages; $i++) {
            if ($page == $i) {
              echo "<a class='text-warning page-number' href='blog.php?page=$i'> $i </a>";
            } else {
              echo "<a class='text-white page-number' href='blog.php?page=$i'> $i </a>";
            }
          };
          ?>


          <?php if ($page != $total_pages) : ?>

            <a class="btn btn-warning float-right page-button" href='blog.php?page=<?= ++$page ?>'>Next page</a>
          <?php endif ?>


        </div>


      </div>
    </section>

  </div>
</main>



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