<?php
require_once 'app/helpers.php';
session_start();
if (!user_auth()) { //IF THE USER ISN'T LOGGED IN, REDIRECT HIM TO LOGIN PAGE
  header('location: blog.php');
  exit;
}

if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
  $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
  $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_STRING);
  $pid = mysqli_real_escape_string($link, $pid);
  $uid = $_SESSION['user_id'];

  if ($pid) {
    $sql = "DELETE FROM posts WHERE id=$pid AND user_id=$uid";
    $result = mysqli_query($link, $sql);
  }

  if ($result && mysqli_affected_rows($link) > 0) {
    $_SESSION['deleted'] = true;
    header('location: blog.php');
    exit;
  }
}
header('location: blog.php');
exit;
