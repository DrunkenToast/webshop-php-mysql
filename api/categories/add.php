<?
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireAdmin();

require_once '../../include/class/db.php';
$db = new Database();

try {
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        throw('Name of category needs to be defined');
    }

    $db->query("INSERT INTO categories(name) VALUES (?)",
        $_POST['name']);

    $ID = $db->lastInsertId();
    Logger::info("User " . (string)$_SESSION['user']['id'] . " added category $ID with name " . $_POST['name']);
    
    echo json_encode(Array("id" => $ID));
} catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>