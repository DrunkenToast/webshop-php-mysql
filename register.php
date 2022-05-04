<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
// RequireRole::requireNoLogin();
require_once 'include/class/db.php';
$db = new Database();

// Process form
$errormsg = array(
  "firstName" => "",
  "lastName" => "",
  "email" => "",
  "address" => "",
  "billingAddress" => "",
  "phone" => "",
  "DOB" => "",
  "password" => "",
  "verifyPassword" => ""
);

$result = array(
  "firstName" => "",
  "lastName" => "",
  "email" => "",
  "address" => "",
  "billingAddress" => "",
  "phone" => "",
  "password" => "",
  "DOB" => "",
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate first and last name
  if (!isset($_POST['firstName']) || empty(trim($_POST["firstName"]))) {
    $errormsg['firstName'] = 'Please enter a first name';
  }
  // else if to check if valid
  else {
    $result['firstName'] = trim($_POST["firstName"]);
  }

  if (!isset($_POST['lastName']) || empty(trim($_POST["lastName"]))) {
    $errormsg['lastName'] = 'Please enter a last name';
  }
  // else if to check if valid
  else {
    $result['lastName'] = trim($_POST["lastName"]);
  }

  // Validate email AND check if its unique!
  if (
    !isset($_POST['email']) || empty(trim($_POST["email"])) ||
    !filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)
  ) {
    $errormsg['email'] = 'Please enter a valid email';
  } else {
    $search = trim($_POST['email']);

    if ($db->query("SELECT ID FROM users WHERE email = ?", $search)->numRows() > 0) {
      $errormsg['email'] = 'An account with this email is already registered';
    } else {
      $result['email'] = $search;
    }
  }

  // Validate addresses
  if (!isset($_POST['address']) || empty(trim($_POST["address"]))) {
    $errormsg['address'] = 'Enter an address';
  }
  // else if to check if valid
  else {
    $result['address'] = trim($_POST["address"]);
  }

  if (isset($_POST['billingAddress'])) {
    $result['billingAddress'] = trim($_POST["billingAddress"]);
  }
  // else if to check if valid

  // Validate phone number
  if (isset($_POST['phone']) && !empty(trim($_POST["phone"]))) {
    if (strlen($_POST['phone']) < 10) { // Regex is more accurate but since its optional and unused it doesnt matter too much
      $errormsg['phone'] = 'Enter a valid phone number';
    } else {
      $result['phone'] = trim($_POST["phone"]);
    }
  }
  if (isset($_POST['DOB']) && !empty(trim($_POST["DOB"]))) {
    $tmp_date = explode('-', $_POST['DOB']);
    if (checkdate((int)$tmp_date[1], (int)$tmp_date[2], (int)$tmp_date[0])) {
      $tmp_date = strtotime($_POST["DOB"]);
      $result['DOB'] = date('Y-m-d', $tmp_date);
    } else {
      $errormsg['DOB'] = 'Enter a valid birthday';
    }
  }

  // Validate password
  if (!isset($_POST['password']) || empty($_POST["password"])) { // no trim on password
    $errormsg['password'] = 'Please enter a password';
  } else if (strlen($_POST["password"]) < 8) {
    $errormsg['password'] = 'Password needs to be atleast 8 characters';
  } else {
    $result['password'] = trim($_POST["password"]);
  }

  // Validate verify password
  if (!isset($_POST['verifyPassword']) || empty($_POST["verifyPassword"])) { // no trim on verifyPassword
    $errormsg['verifyPassword'] = 'Please enter password to verify';
  } else {
    if ($_POST["password"] !== $_POST["verifyPassword"] && empty($errormsg['password'])) {
      $errormsg["verifyPassword"] = "Passwords don't not match!";
    } else {
      $result['password'] = $_POST['password'];
    }
  }


  // REGISTRATION
  // check if it has errors, otherwise register the user
  $error = false;
  foreach ($errormsg as $msg) {
    if (!empty($msg)) {
      $error = true;
    }
  }

  if (!$error) { // Every input given is good -> register user!

    // Convert empty to null
    foreach ($result as $key => $val) {
      $result[$key] = empty($val) ? null : $val;
    }

    $result['password'] = password_hash($result['password'], PASSWORD_DEFAULT);
    $db->query(
      "INSERT INTO users (firstName, lastName, email, passwordHash, address, billingAddress, phone, DOB, roleID) VALUES (?,?,?,?,?,?,?,?, 2)", /*2 is normal user role*/
      $result['firstName'],
      $result['lastName'],
      $result['email'],
      $result['password'],
      $result['address'],
      $result['billingAddress'],
      $result['phone'],
      $result['DOB']
    );
    Logger::info('New user registered. ID: ' . $db->lastInsertId());
    header("location: logout.php?registered");
  }
}

// Include HTML
$title = 'Register'; // For header, document title
$javascript = array('js/checkForm.js');
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="col"></div>
    <div class="col-sm-12 col-md-8 col-lg-5 card">
      <div class="card-body">
        <h4 class="card-title">Register</h4>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="needs-validation" novalidate onsubmit="return validateRegisterForm();">

          <div class="row g-3">
            <div class="col-sm-6">
              <label for="firstName" class="form-label">First name</label>
              <input type="text" class="form-control <?php echo empty($errormsg['firstName']) ? '' : 'is-invalid' ?>" id="firstName" name="firstName" placeholder="" value="<?php if (isset($_POST['firstName'])) {
                                                                                                                                                                              echo htmlspecialchars($_POST['firstName']);
                                                                                                                                                                            } ?>" required>
              <div class="invalid-feedback">
                <?php echo $errormsg['firstName'] ?>
              </div>
            </div>

            <div class="col-sm-6">
              <label for="lastName" class="form-label">Last name</label>
              <input type="text" class="form-control <?php echo empty($errormsg['lastName']) ? '' : 'is-invalid' ?>" id="lastName" name="lastName" placeholder="" value="<?php if (isset($_POST['lastName'])) {
                                                                                                                                                                            echo htmlspecialchars($_POST['lastName']);
                                                                                                                                                                          } ?>" required>
              <div class="invalid-feedback">
                <?php echo $errormsg['lastName'] ?>
              </div>
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control <?php echo empty($errormsg['email']) ? '' : 'is-invalid' ?>" id="email" name="email" placeholder="you@example.com" value="<?php if (isset($_POST['email'])) {
                                                                                                                                                                                  echo htmlspecialchars($_POST['email']);
                                                                                                                                                                                } ?>">
              <div class="invalid-feedback">
                <?php echo $errormsg['email'] ?>
              </div>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control <?php echo empty($errormsg['address']) ? '' : 'is-invalid' ?>" id="address" name="address" placeholder="1234 Main St" value="<?php if (isset($_POST['address'])) {
                                                                                                                                                                                    echo htmlspecialchars($_POST['address']);
                                                                                                                                                                                  } ?>" required>
              <div class="invalid-feedback">
                <?php echo $errormsg['address'] ?>
              </div>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Billing address <span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control <?php echo empty($errormsg['billingAddress']) ? '' : 'is-invalid' ?>" id="billingAddress" name="billingAddress" placeholder="1234 Main St" value="<?php if (isset($_POST['billingAddress'])) {
                                                                                                                                                                                                          echo htmlspecialchars($_POST['billingAddress']);
                                                                                                                                                                                                        } ?>">
              <div class="invalid-feedback">
                <?php echo $errormsg['billingAddress'] ?>
              </div>
            </div>

            <div class="col-12">
              <label for="phone" class="form-label">Phone <span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control <?php echo empty($errormsg['phone']) ? '' : 'is-invalid' ?>" id="phone" name="phone" placeholder="0412 34 56 78" value="<?php if (isset($_POST['phone'])) {
                                                                                                                                                                                echo htmlspecialchars($_POST['phone']);
                                                                                                                                                                              } ?>">
              <div class="invalid-feedback">
                <?php echo $errormsg['phone'] ?>
              </div>
            </div>

            <div class="col-lg-6">
              <label for="birthday" class="form-label">Birthday:</label>
              <input type="date" id="birthday" name="DOB" class="form-control <?php echo empty($errormsg['DOB']) ? '' : 'is-invalid' ?>">
              <div class="invalid-feedback">
                <?php echo $errormsg['DOB'] ?>
              </div>
            </div>

            <hr class="my-4">

            <div class="col-sm-6">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control <?php echo empty($errormsg['password']) ? '' : 'is-invalid' ?>" id="password" name="password" placeholder="" required>
              <div class="invalid-feedback">
                <?php echo $errormsg['password'] ?>
              </div>
            </div>

            <div class="col-sm-6">
              <label for="verifyPassword" class="form-label ">Verify password</label>
              <input type="password" class="form-control <?php echo empty($errormsg['verifyPassword']) ? '' : 'is-invalid' ?>" id="verifyPassword" name="verifyPassword" placeholder="" required>
              <div class="invalid-feedback">
                <?php echo $errormsg['verifyPassword'] ?>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <p id="emailHelp" class="form-text text-muted">We'll probably sell your data.</p>
          <button class="w-100 btn btn-primary btn-lg" type="submit">Register!</button>
        </form>
      </div>

    </div>
    <div class="col"></div>
  </div>
</div>
<?php
include_once 'include/footer.php';
?>