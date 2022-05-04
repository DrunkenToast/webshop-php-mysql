<?php
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireLoggedIn();

require_once '../../include/class/db.php';
$db = new Database();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get cart ID from orders
        $db->query("SELECT o.ID FROM `orders` as o
        WHERE o.userID = ? AND o.paymentDate IS NULL", $_SESSION['user']['id'])->bind($cartID);
        while ($db->fetch()) {}

        // No cart yet -> create one
        if (empty($cartID)) { 
            $cartID = $db->query("INSERT INTO orders(userID)
            VALUES (?)", $_SESSION['user']['id'])->lastInsertId();
        }

        // Get amount of product
        $db->query("SELECT amount FROM orderProducts
        WHERE orderID = ? AND productID = ?", $cartID, (int)$_POST['id'])->bind($productAmount);
        while ($db->fetch()) {}

        if (empty($productAmount)) { // Item not in cart yet -> add
            $amount = (int)$_POST['amount'];

            if ($amount > 0) {
                $db->query("INSERT INTO orderProducts(orderID, productID, amount)
                    VALUES (?,?,?)", $cartID, $_POST['id'], $amount);
            }
        }
        else { // Item already in cart -> adjust
            $amount = $productAmount + (int)$_POST['amount'];

            if ($amount > 0) { // Adjust amount
                $db->query("UPDATE orderProducts SET amount=?
                WHERE orderID = ? AND productID = ?", $amount, $cartID, (int)$_POST['id']);
            }
            else { // < 0 -> delete entry from database
                $db->query("DELETE FROM `orderProducts`
                WHERE orderID = ? AND productID = ?", $cartID, (int)$_POST['id']);
            }
        }
    }
}
catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>