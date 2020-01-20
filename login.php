<?php require_once 'app/helpers.php';
session_start();
if (isset($_SESSION['user_id'])) { //IF USER IS ALREADY CONNECTED, REDIRECT HIM TO MYPROFILE
    header('location: myprofile.php');
    exit;
}
;
$page_title = 'Login';
$body_class = 'login';
$errors = [
    'email' => '',
    'password' => '',
    'submit' => '',
];
if (isset($_POST['submit'])) { //IF THE USER PRESSED ON SUBMIT

    if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        //UNSECURED FROM XSS ATTACK ↓
        // $email = !empty($_POST['email']) ? trim($_POST['email']) : ''; //IF THE FIELDS AREN'T EMPTY, TRIM THEM
        // $password = !empty($_POST['password']) ? trim($_POST['password']) : '';

        if (!$email) {
            $errors['email'] = 'A valid Email is required';
        } else if (!$password) {
            $errors['password'] = 'Please enter your password';
        } else {

            $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB); //CONNECT TO DATABASE
            $password = mysqli_real_escape_string($link, $password); //CLEAR THE INPUTS FROM POTENTIAL SINGLE QUOTES
            $email = mysqli_real_escape_string($link, $email);
            $sql = "SELECT u.*,up.profile_img,up.city,up.country
      FROM users u
      JOIN user_profile up ON u.id=up.user_id
      WHERE email='$email'
      LIMIT 1"; //THE QUERY WE'RE REQUESTING TO VERIFY EMAIL

            $result = mysqli_query($link, $sql); //RESULT WILL BE THE RESOURCE WE WILL GET FROM THE DATABASE

            if ($result && mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result); //CREATE AN ASSOCIATIVE ARRAY FROM THE RESOURCE

                if (password_verify($password, $user['password'])) { //VERIFY THE ENCRYPTED PASSWORD
                    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['city'] = $user['city'];
                    $_SESSION['country'] = $user['country'];
                    $_SESSION['user_img'] = $user['profile_img'];
                    $_SESSION['logged'] = true;
                    header('location: index1.php');
                    exit;
                } else {
                    $errors['submit'] = 'That\'s not a match';
                }
            } else {
                $errors['submit'] = 'That\'s not a match';
            }
        }
    }
    $token = csrf();
} else {
    $token = csrf();
}

?>
<?php include 'tpl/header.php'?>
<div class="container">
  <section id='signin-form-content'>
    <div class="row row11">
      <div class="col-lg-6 mt-3">
        <h1 class="display-3 text-white text-left">Log in</h1>
        <form action="login.php" method="POST" autocomplete="off" novalidate="novalidate">
          <input type="hidden" name='csrf_token' value='<?=$token?>'>
          <div class="form-group ">
            <label for="email" class='text-white'>Email address:</label>
            <input type="email" value='<?=old('email')?>' name='email' class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
            <span class="text-danger float-left"><?=$errors['email']?></span>

          </div>
          <div class="form-group pt-3">
            <label for="password" class='text-white float-left'>Password:</label>
            <input type="password" name='password' class="form-control" id="password" placeholder="Enter password">
            <span class="text-danger float-left"><?=$errors['password']?></span>
            <span class="text-danger float-left"><?=$errors['submit']?></span>

          </div>
          <input type="submit" name="submit" value="Log in" class="btn-click-effect mx-auto d-block">
          <small id="emailHelp" class="form-text mt-5 text-white text-left pb-5">Don't have an account? Click <span><a class='text-white ' href="signup.php"> here</a></span> to register</small>
        </form>

      </div>
      <div class="col-lg-6">
        <img src="images/loginn2.jpg" alt="">
      </div>
    </div>



  </section>
</div>


<?php include 'tpl/footer.php'?>