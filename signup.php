<?php require_once 'app/helpers.php';
session_start();

if (user_auth()) { //IF USER IS ALREADY CONNECTED, REDIRECT HIM TO MYPROFILE
  header('location: myprofile.php');
  exit;
};
$page_title = 'Sign Up';
$body_class = 'signup';
$errors = [
  'name' => '',
  'email' => '',
  'password' => '',
  'submit' => '',
  'city' => '',
];

if (isset($_POST['submit'])) {      //IF THE USER PRESSED ON SUBMIT

  if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
    mysqli_set_charset($link, 'utf8');

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $email = mysqli_real_escape_string($link, $email);

    $first_name = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $first_name = mysqli_real_escape_string($link, ucwords($first_name));

    $last_name = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $last_name = mysqli_real_escape_string($link, ucwords($last_name));

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($link, $password);


    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $city = mysqli_real_escape_string($link, $city);

    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $country = mysqli_real_escape_string($link, $country);

    $form_valid = true;

    $profile_img = 'profile_img.jpg';
    define('MAX_FILE_SIZE', 1024 * 1024 * 25); //MAX FILE SIZE FOR THE IMAGE(DIFFERENT FROM PHP.INI)

    if (!$first_name || !$last_name || mb_strlen($first_name) < 2 || mb_strlen($last_name) < 2 || mb_strlen($first_name) > 50 || mb_strlen($last_name) > 50) {
      $errors['name'] = 'First and last name must be between 2 & 50 characters';
      $form_valid = false;
    }
    if (!$email) {
      $errors['email'] = 'A valid Email is required';
      $form_valid = false;
    } else if (email_check($link, $email)) {
      $errors['email'] = 'This Email is already taken';
      $form_valid = false;
    }

    if (!$password || strlen($password) < 6 || strlen($password) > 20) {
      $errors['password'] = 'Password must be between 6 & 20 characters';
      $form_valid = false;
    }


    if (isset($_FILES['img']['error']) && $_FILES['img']['error'] == 0) {

      if (isset($_FILES['img']['size']) && $_FILES['img']['size'] <= MAX_FILE_SIZE) {

        if (isset($_FILES['img']['name'])) {

          $allowed_ex = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
          $details = pathinfo($_FILES['img']['name']);

          if (in_array(strtolower($details['extension']), $allowed_ex)) {

            if (isset($_FILES['img']['tmp_name']) && is_uploaded_file($_FILES['img']['tmp_name'])) {

              $profile_img = date('d.m.y.H.i.s') . '-' . $_FILES['img']['name'];
              move_uploaded_file($_FILES['img']['tmp_name'], 'images/' . $profile_img);
            }
          }
        }
      }
    }

    if ($form_valid) {
      $password = password_hash($password, PASSWORD_BCRYPT);
      $sql = "INSERT INTO users VALUES(null,'$first_name','$last_name','$email','$password')";
      $result = mysqli_query($link, $sql);

      if ($result && mysqli_affected_rows($link) > 0) {
        $new_id = mysqli_insert_id($link);
        $sql = "INSERT INTO user_profile VALUES(null,'$new_id','$profile_img','$city','$country')";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_affected_rows($link) > 0) {
          $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['user_id'] = $new_id;
          $_SESSION['user_name'] = $first_name;
          $_SESSION['last_name'] = $last_name;
          $_SESSION['user_img'] = $profile_img;
          $_SESSION['country'] = $country;
          $_SESSION['city'] = $city;
          header('location: blog.php');
          exit;
        }
      }
    }
  }
  $token = csrf();
} else {
  $token = csrf();
}

?>
<?php include 'tpl/header.php' ?>
<div class="container">
  <section id="signup-form-content">
    <div class="row">
      <div class="col-md-6 vertical-row">
        <h1 class='text-white display-3 text-left'>Sign Up</h1>
        <form action="signup.php" method="POST" autocomplete="off" enctype="multipart/form-data" novalidate="novalidate">
          <input type="hidden" name='csrf_token' value='<?= $token ?>'>
          <div class="form-group ">
            <div class="form-row">
              <div class="form-group col-sm-6">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" name='firstName' value='<?= old('firstName') ?>' id="" placeholder="First Name">
              </div>
              <div class="form-group col-sm-6">
                <label for="lastName">Last Name:</label>
                <input type="text" value='<?= old('lastName') ?>' name='lastName' class="form-control" id="" placeholder="Last Name">
              </div>
              <span class="text-danger"><?= $errors['name'] ?></span>
            </div>
            <div class="form-group pt-3">
              <label for="email" class='text-white'>Email address:</label>
              <input type="email" value='<?= old('email') ?>' name='email' class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
              <span class="text-danger float-left"><?= $errors['email'] ?></span>

            </div>
            <div class="form-group pt-3">
              <label for="password" class='text-white float-left'>Password:</label>
              <input type="password" name='password' class="form-control" id="password" placeholder="Enter password">
              <span class="text-danger float-left"><?= $errors['password'] ?></span>
              <span class="text-danger float-left"><?= $errors['submit'] ?></span>

            </div>
            <div class="form-row mt-4">
              <div class="form-group col-sm-6">
                <label for="city" class='text-white float-left'>City:</label>
                <input type="text" class="form-control" name='city' placeholder="City">
              </div>
              <div class="form-group col-sm-6">
                <label for="country">Country:</label>
                <select id="country" name="country" placeholder="Country" class="form-control">
                  <option value="">Select</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Åland Islands">Åland Islands</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Cote D'ivoire">Cote D'ivoire</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guernsey">Guernsey</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-bissau">Guinea-bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                  <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Isle of Man">Isle of Man</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jersey">Jersey</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                  <option value="Korea, Republic of">Korea, Republic of</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macao">Macao</option>
                  <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                  <option value="Moldova, Republic of">Moldova, Republic of</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montenegro">Montenegro</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Reunion">Reunion</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Saint Helena">Saint Helena</option>
                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                  <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia">Serbia</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                  <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Timor-leste">Timor-leste</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands, British">Virgin Islands, British</option>
                  <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                  <option value="Wallis and Futuna">Wallis and Futuna</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>



                </select>
              </div>

            </div>


            <div class="form-group pt-3">
              <label for="img" class='text-white float-left'>Profile Image:</label>
              <div class="input-group mb-3">

                <div class="custom-file text-dark">
                  <input onchange="jQuery(this).next('label').text(this.value);" type="file" name='img' class="custom-file-input browse text-dark" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                  <label class="custom-file-label text-dark" for="inputGroupFile01">Choose file</label>
                </div>
              </div>
            </div>



            <input type="submit" name="submit" value="Sign Up" class="btn-click-effect mx-auto d-block">
          </div>
        </form>
      </div>
      <div class="col-md-6 mt-5">
        <img src="images/signup.jpg" class='mt-5' alt="">
      </div>
    </div>

  </section>
</div>
<?php include 'tpl/footer.php' ?>