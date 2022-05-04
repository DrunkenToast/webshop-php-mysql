<?php
session_start();
require_once '../../include/errors.php';

require_once '../../include/class/db.php';
$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $categories = [];
    $db->query("SELECT ID, name FROM categories")
    ->bind($ID, $name);

    while ($db->fetch())
    {
        array_push($categories,
            Array(
                "id" => $ID,
                "name" => htmlspecialchars($name),
            )
        );
    }
    
    echo json_encode($categories);
}
?>