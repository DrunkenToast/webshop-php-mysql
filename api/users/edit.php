<?
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireAdmin();

require_once '../../include/class/db.php';
$db = new Database();

try {
    if (!isset($_POST['ID']) || empty($_POST['ID'])) {
        throw(new Exception('ID of user needs to be defined'));
    }

    if ($db->query("SELECT ID FROM users WHERE email = ? AND ID != ?", $_POST['email'], $_POST['ID'])->numRows() > 0) {
        throw(new Exception("Can't edit user " . $_POST['ID'] . "'s email because it is already taken by someone else."));
    }

    $DOB = null;
    if (isset($_POST['DOB']) && !empty(trim($_POST["DOB"]))) {
        $tmp_date = explode('-', $_POST['DOB']);
        $tmp_date = strtotime($_POST["DOB"]);
        $DOB = date('Y-m-d', $tmp_date);
    }

    $db->query("
    UPDATE `users` SET `firstName`=?,`lastName`=?,`email`=?,`address`=?,`billingAddress`=?,`phone`=?,`DOB`=?,`roleID`=?,`active`=?
    WHERE ID=?",
    html_entity_decode($_POST['firstName']), html_entity_decode($_POST['lastName']), $_POST['email'],
    html_entity_decode($_POST['address']), html_entity_decode($_POST['billingAddress']),
    $_POST['phone'], $DOB, $_POST['roleID'], $_POST['active'], $_POST['ID']);

    Logger::info("User " . (string)$_SESSION['user']['id'] . " updated user " . $_POST['ID']);
} catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>