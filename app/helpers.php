<?php
require 'config.php';
if (!function_exists('old')) {

  /**  
   *RETURN LAST INPUT VALUE OF A FIELD
   * @param string $arg field name
   *@return string
   */
  function old($arg)
  {
    return $_REQUEST[$arg] ?? '';
  }
}

if (!function_exists('csrf')) {
  /**  
   *GENERATE A RANDOM STRING FOR SECURITY
   *@return string
   */
  function csrf()
  {
    $token = sha1(rand(1, 10000) . '$$' . rand(1, 10000) . 'inkFreaks');
    $_SESSION['csrf_token'] = $token;
    return $token;
  }
}

if (!function_exists('user_auth')) {
  /**  
   *COMPARE THE IP & AGENT CREATED BY THE SESSION WITH THE IP & AGENT
   *FROM THE SERVER, TO MAKE SURE IT'S THE SAME USER
   *@return boolean
   */
  function user_auth()
  {
    $auth = false;
    if (isset($_SESSION['user_id'])) {
      if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR']) {
        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']) {
          $auth = true;
        }
      }
    }

    return $auth;
  }
}
if (!function_exists('email_check')) {
  /**  
   *SEND A QUERY THAT CHECKS IF THE USER'S INPUT ALREADY EXISTS
   *@param $link- CONNECTION TO MYSQLI
   *@param $email- THE USER'S INPUT
   *@return boolean
   */

  function email_check($link, $email)
  {
    $email_exists = false;
    $sql = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $email_exists = true;
    }
    return $email_exists;
  }
}
