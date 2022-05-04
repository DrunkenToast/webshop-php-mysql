<?
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireAdmin();

require_once '../../include/class/db.php';
$db = new Database();

try {
    if (!isset($_POST['ID']) || empty($_POST['ID'])) {
        throw(new Exception('ID of category needs to be defined'));
    }

    if (!isset($_POST['name']) || empty($_POST['name'])) {
        throw(new Exception('Name of category needs to be defined'));
    }

    $db->query("
    UPDATE categories
    SET name=?
    where ID=?
    ", html_entity_decode($_POST['name']), $_POST['ID']);

    Logger::info("User " . (string)$_SESSION['user']['id'] . " updated category " . $_POST['ID'] . " to name " . $_POST['name']);
} catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>