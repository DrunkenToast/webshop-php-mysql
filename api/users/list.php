<?php
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireAdmin();

require_once '../../include/class/db.php';
$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $users = [];
    $db->query("SELECT `ID`, `firstName`, `lastName`, `email`, `address`, `billingAddress`, `phone`, `DOB`, `DOC`, `roleID`, `active`
    FROM `users`")
    ->bind($ID, $firstName, $lastName, $email, $address, $billingAddress, $phone, $DOB, $DOC, $roleID, $active);

    while ($db->fetch())
    {
        array_push($users,
            Array(
                "id" => $ID,
                "firstName" => htmlspecialchars($firstName),
                "lastName" => htmlspecialchars($lastName),
                "email"=> htmlspecialchars($email),
                "address"=> htmlspecialchars($address),
                "billingAddress"=> htmlspecialchars($billingAddress),
                "phone"=> htmlspecialchars($phone),
                "DOB"=> $DOB,
                "DOC"=> $DOC,
                "roleId" => $roleID,
                "active"=> $active,
            )
        );
    }
    
    echo json_encode($users);
}
?>