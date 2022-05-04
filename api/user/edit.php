<?
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireLoggedIn();

try {
    require_once '../../include/class/db.php';
    $db = new Database();

    if ($db->query("SELECT ID FROM users WHERE email = ? AND ID != ?", $_POST['email'], $_SESSION['user']['id'])->numRows() > 0) {
        throw(new Exception("User " . $_SESSION['user']['id'] . "tried changing email but is already taken by someone else."));
    }

    $DOB = null;
    if (isset($_POST['DOB']) && !empty(trim($_POST["DOB"]))) {
        $tmp_date = explode('-', $_POST['DOB']);
        $tmp_date = strtotime($_POST["DOB"]);
        $DOB = date('Y-m-d', $tmp_date);
    }

    $db->query("
    UPDATE `users` SET `firstName`=?,`lastName`=?,`email`=?,`address`=?,`billingAddress`=?,`phone`=?,`DOB`=?
    WHERE ID=?",
    html_entity_decode($_POST['firstName']), html_entity_decode($_POST['lastName']), $_POST['email'],
    html_entity_decode($_POST['address']), html_entity_decode($_POST['billingAddress']),
    $_POST['phone'], $DOB, $_SESSION['user']['id']);

    Logger::info("User " . (string)$_SESSION['user']['id'] . " updated profile");
} catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>