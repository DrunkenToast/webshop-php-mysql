<?php
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireLoggedIn();

require_once '../../include/class/db.php';
$db = new Database();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get cart ID from orders
    $db->query("SELECT o.ID FROM `orders` as o
    WHERE o.userID = ? AND o.paymentDate IS NULL", $_SESSION['user']['id'])->bind($cartID);
    while ($db->fetch()) {}

    $products = [];
    if (!empty($cartID))
    {
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
                    "description" => htmlspecialchars($description),
                    "unitPrice" => $unitPrice,
                    "unitsInStock" => $unitsInStock,
                    "active" => (bool)$active,
                    "categories" => Array(),
                    "pictures" => Array(),
                    "amount" => $amount
                )
            );
        }

        // query for categories and pictures of a product.
        $query = "SELECT c.ID, c.name, pic.picturePath FROM products as p
        LEFT JOIN categories as c ON c.ID IN (
            SELECT pc.categoryID FROM productCategory as pc where p.ID = pc.productID
        )
        LEFT JOIN pictures as pic ON p.ID = pic.productID
        WHERE p.ID = ?";

        foreach (array_keys($products) as $key) {
            $db->query($query, $products[$key]['id'])->bind($catid, $cat, $pic);
            $products[$key]['categories'] = [];
            while($db->fetch())
            {
                if ($catid != null) {
                    $products[$key]['categories'][$catid] = htmlspecialchars($cat);
                }
                if ($pic != null) {
                    array_push($products[$key]['pictures'], $pic);
                }
            }

            $products[$key]['categories'] = array_unique($products[$key]['categories']);
            $products[$key]['pictures'] = array_unique($products[$key]['pictures']);
        }
    }
    echo json_encode($products);
}
?>