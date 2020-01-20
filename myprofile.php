<?php
require_once 'app/helpers.php';
session_start();

if (!user_auth()) {
  header('location: login.php');
  exit;
}
$errors = [
  'name' => '',
  'email' => '',
  'password' => '',
];

$id = $_SESSION['user_id'];
$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
//PREVENT GIBBERISH IN CASE OF HEBREW POST ↓
mysqli_set_charset($link, 'utf8');

$sql = "SELECT u.first_name,u.last_name,up.profile_img, up.city,up.country FROM users u JOIN user_profile up ON u.id=up.user_id WHERE u.id=$id ";

$result = mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($result);

$sql_for_posts = "SELECT title, article, id FROM posts WHERE user_id=$id ORDER BY id DESC  LIMIT 3 ";

$result_posts = mysqli_query($link, $sql_for_posts);



if (isset($_POST['submit'])) {

  if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {

    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

    $first_name = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $first_name = mysqli_real_escape_string($link, $first_name);

    $last_name = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $last_name = mysqli_real_escape_string($link, $last_name);

    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $city = mysqli_real_escape_string($link, $city);

    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $country = mysqli_real_escape_string($link, $country);

    $form_valid = true;

    $profile_img =  $_SESSION['user_img'];
    define('MAX_FILE_SIZE', 1024 * 1024 * 25); //MAX FILE SIZE FOR THE IMAGE(DIFFERENT FROM PHP.INI)

    if (
      !$first_name || !$last_name || mb_strlen($first_name) < 2 || mb_strlen($last_name) < 2 ||
      mb_strlen($first_name) > 50 || mb_strlen($last_name) > 50
    ) {

      $errors['name'] = 'Name must be between 2 and 50 characters';
      $form_valid = false;
    }

    //IF IMAGE IS UPLOADED ↓
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
      $sql = "UPDATE users SET first_name='$first_name',last_name='$last_name' WHERE id=$id";
      $result = mysqli_query($link, $sql);

      $country = !empty($country) ? $country : $_SESSION['country'];

      $sql2 = "UPDATE user_profile SET profile_img='$profile_img',city='$city' ,country='$country' WHERE user_id=$id";
      $result2 = mysqli_query($link, $sql2);

      $sql3 = "SELECT u.first_name,u.last_name,up.city FROM users u JOIN user_profile up ON u.id=up.user_id WHERE u.id = $id";
      $result3 = mysqli_query($link, $sql3);
      $foo = mysqli_fetch_assoc($result3);



      $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
      $_SESSION['user_id'] = $id;
      $_SESSION['user_name'] = $foo['first_name'];
      $_SESSION['last_name'] = $foo['last_name'];
      $_SESSION['user_img'] = $profile_img;
      $_SESSION['city'] = $foo['city'];
      $_SESSION['country'] = $country;
      $_SESSION['updated'] = true;
    }
  }
  $token = csrf();
} else {
  $token = csrf();
}

$page_title = 'My Profile';
$body_class = 'myprofile';
?>

<?php include 'tpl/header.php' ?>
<main>
  <section>
    <div class="container negative-mt p-0">

      <div class="row m-0 p-0">
        <div class="col-12 ">
          <h1 class="text-white text-left"><?= (htmlentities($_SESSION['user_name'])) . ' ' . (htmlentities($_SESSION['last_name'])) ?>'s Profile</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12"></div>
        <div class="card mt-5 mx-auto d-block">
          <img src="images/<?= $profile_img ?? $user['profile_img'] ?>" class="card-img-top" alt="profile image">

        </div>

      </div>


      <div class="row mt-5">
        <div class="col-md-6 vertical-row">
          <form action="myprofile.php" method="POST" autocomplete="off" enctype="multipart/form-data" novalidate="novalidate">
            <input type="hidden" name='csrf_token' value='<?= $token ?>'>
            <div class="form-group">

              <label for="firstName" class='text-white float-left'>First Name:</label>
              <input type="text" class="form-control" name='firstName' id="" value='<?= htmlentities($_SESSION['user_name']) ?>'>

              <label for="lastName" class='text-white float-left'>Last Name:</label>
              <input type="text" name='lastName' class="form-control" id="" value='<?= htmlentities($_SESSION['last_name']) ?>'>

              <span class="text-danger"><?= $errors['name'] ?></span>

              <label for="city" class='text-white float-left'>City:</label>
              <input type="text" class="form-control" name='city' value='<?= htmlentities($_SESSION['city']) ?>'>
              <label for="country" class='text-white float-left'>Country:</label>
              <select id="country" name="country" class="form-control">
                <option value=""><?= $_SESSION['country'] ?></option>
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
              <div class="form-group pt-3">
                <label for="img" class='text-white float-left'>Profile Image:</label>
                <div class="input-group mb-3">

                  <div class="custom-file">
                    <input type="file" onchange="jQuery(this).next('label').text(this.value);" name='img' class="custom-file-input browse" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label text-dark" for="inputGroupFile01">Choose file</label>
                  </div>
                </div>
              </div>
              <input type="submit" name="submit" value="Update" class="btn-click-effect mx-auto d-block">
            </div>
          </form>
        </div>
        <div class="col-md-6">

          <h2 class='text-white text-left display-5 p-0 m-0'>Your recent Posts</h2>

          <?php if (mysqli_num_rows($result_posts) > 0) :  ?>

            <?php while ($post = mysqli_fetch_assoc($result_posts)) : ?>
              <a href="post.php?id=<?= $post['id'] ?>" class='text-decoration-none text-dark '>
                <div class="card p-0 m-0 mt-3">
                  <div class="card-header  text-white">
                    <span class='float-left'>
                      <?= $post['title'] ?>
                    </span>
                  </div>
                  <div class="card-body">
                    <p class='text-left article'>
                      <?= $post['article'] ?>
                    </p>
                  </div>
                </div>
              </a>
            <?php endwhile ?>
          <?php else : ?>
            <p class='text-white pt-5 mt-5'>No posts to show. <a href="blog.php" class='text-white'>Start writing</a> </p>
          <?php endif; ?>
          <script>
            ScrollReveal().reveal('.text-decoration-none', {
              delay: 150,
              easing: 'cubic-bezier(1, 1, 0, 0)',
              duration: 1000,
            });
          </script>

        </div>


      </div>
    </div>

  </section>
</main>



<?php include 'tpl/footer.php';
if (isset($_SESSION['updated'])) : ?>
  <script>
    toastr["success"]("Updated");
  </script>
<?php unset($_SESSION['updated']);
endif ?>