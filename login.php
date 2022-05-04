<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireNoLogin();
require_once 'include/class/db.php';
$db = new Database();

$errormsg = array(
  "email" => "",
  "password" => "",
  "login" => ""
);

$input = array(
  "email" => "",
  "password" => "",
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if any fields are empty and or not valid
  if (
    !isset($_POST['email']) || empty(trim($_POST["email"])) ||
    !filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)
  ) {
    $errormsg['email'] = 'Please enter a valid email';
  } else {
    $input['email'] = trim($_POST["email"]);
  }

  if (!isset($_POST['password']) || empty($_POST["password"])) {
    $errormsg['password'] = 'Please enter a last name';
  } else {
    $input['password'] = $_POST["password"];
  }


  // Validate login information
  $error = false;
  foreach ($errormsg as $msg) {
    if (!empty($msg)) {
      $error = true;
    }
  }

  if (!$error) {
    $db->query("SELECT ID, firstName, lastName, email, passwordHash, roleID FROM users WHERE email = ? AND active = 1 ", $input['email'])
    ->bind($ID, $firstName, $lastName, $email, $passwordHash, $roleID)->fetch();
    if (password_verify($input['password'], $passwordHash)) { // Password verified
      $_SESSION["user"] = Array(
        "id"=>$ID,
        "roleId"=>$roleID,
        "firstName"=> htmlspecialchars($firstName),
        "lastName"=> htmlspecialchars($lastName),
        "email"=> htmlspecialchars($email)
      );

      header("location: index.php");
      exit;
    } else {
      // password wrong
      $errormsg['login'] = "Invalid username or password.";
      Logger::warning("Failed login attempt with email '" . $input['email'] . "'");
    }
  }
}

$title = 'Login'; // For header, document title
$javascript = array('js/checkForm.js');
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="col"></div>
    <div class="col-sm-12 col-md-6 col-lg-4 card">
      <div class="card-body">
        <?php
        if (isset($_GET['registered'])) {
          echo '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Registered successfully, you can now log in.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          ';
        }

        if (isset($errormsg['login']) && !empty($errormsg['login'])) {
          echo '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $errormsg['login'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          ';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="needs-validation" novalidate onsubmit="return validateLoginForm();">
          <h4 class="card-title">Log in</h4>
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control <?php echo empty($errormsg['verifyPassword']) ? '' : 'is-invalid'?>"
              id="email" name="email" value="<?php if (isset($_POST['email'])) {echo htmlspecialchars($_POST['email']);} ?>">
            <div class="invalid-feedback">
              <?php echo $errormsg['email'] ?>
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <div class="invalid-feedback">
              <?php echo $errormsg['email'] ?>
            </div>
          </div>
          <hr>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="register.php" class="card-link">I don't have an account yet :((</a>
      </div>
    </div>
    <div class="col"></div>
  </div>
</div>
<?php
include_once 'include/footer.php';
?>