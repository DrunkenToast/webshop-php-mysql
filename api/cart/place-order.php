<?php
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireLoggedIn();

require_once '../../include/class/db.php';
$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = Array(
        "success" => false,
        "products" => Array()
    );

    // Get cart ID from orders
    $db->query("SELECT o.ID FROM `orders` as o
    WHERE o.userID = ? AND o.paymentDate IS NULL", $_SESSION['user']['id'])->bind($cartID);
    while ($db->fetch()) {}

    if (!empty($cartID))
    {
        $errors = [];
        $products = [];

        $db->query("SELECT p.ID, p.name, p.description, p.unitPrice, p.unitsInStock, p.active, op.amount FROM products as p
        LEFT JOIN orderProducts as op ON (op.productID = p.ID)
        WHERE op.orderID = ?", $cartID)
        ->bind($ID, $name, $description, $unitPrice, $unitsInStock, $active, $amount);

        while ($db->fetch())
        {
            array_push($products,
                Array(
                    "id" => $ID,
                    "name" => htmlspecialchars($name),
                    "unitPrice" => $unitPrice,
                    "unitsInStock" => $unitsInStock,
                    "amount" => $amount
                )
            );
        }

        // Check if available stock
        foreach (array_keys($products) as $key) {
            if ($products[$key]['unitsInStock'] < $products[$key]['amount']) {
                array_push($response['products'], Array(
                    "id" => $products[$key]['id'],
                    "name" => htmlspecialchars($products[$key]['name']),
                    "unitPrice" => $products[$key]['unitPrice'],
                    "unitsInStock" => $products[$key]['unitsInStock'],
                    "amount" => $products[$key]['amount']
                ));
            }
        }

        // Check if all available -> place order
        if (!(count($response['products']) > 0) && count($products) > 0)
        {
            $response['success'] = true;

            // Cart to order
            $db->query("UPDATE orders SET orderDate=?,paymentDate=?,shipDate=?,fulfilled=? WHERE ID=?",
                date('Y-m-d'), date('Y-m-d'), date('Y-m-d'), 1, $cartID);

            // Update stock
            foreach (array_keys($products) as $key) {
                $db->query("UPDATE products SET unitsInStock=? WHERE ID = ?",
                $products[$key]['unitsInStock'] - $products[$key]['amount'], $products[$key]['id']);
            }

            Logger::info("UserID " . $_SESSION['user']['id'] . " placed order " . $cartID);
        }
    }

    echo json_encode($response);
}
?>