<?
session_start();
require_once '../../include/errors.php';
require_once '../../include/class/require-role.php';
RequireRole::requireAdmin();

require_once '../../include/class/db.php';
$db = new Database();

try {
    $active = 0;
    if (isset($_POST['active']) && $_POST['active'] = 'on') {
        $active = 1;
    }

    $db->query("INSERT INTO products (name, description, unitPrice, unitsInStock, active) VALUES (?, ?, ?, ?, ?)",
        $_POST['name'], $_POST['description'], $_POST['unitPrice'], $_POST['unitsInStock'], $active);

    $ID = $db->lastInsertId();
    
    Logger::info("User " . (string)$_SESSION['user']['id'] . " added product $ID");

    // Add image (currently only use 1 image but I kept the table as is, maybe in the future product page with multiple images)
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0)
    {
        // Upload file
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFileName = 'products/' . date('Y-m-d-H-i-s') . '-id-' . $ID . '.'. $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../img/' . $imageFileName);

        Logger::info('User ' . (string)$_SESSION['user']['id'] . " uploaded image '$imageFileName'");

        // Add to product
        $db->query("INSERT INTO pictures (productID, picturePath) VALUES (?, ?)", $ID,  $imageFileName);
    }

    // Add categories
    if (isset($_POST['categories']) && is_array($_POST['categories']))
    {
        $catValues = [];
        $catIDs = [];
        foreach (array_keys($_POST['categories']) as $key)
        {
            array_push($catValues, "($ID, ?)");
            array_push($catIDs, (int)$_POST['categories'][$key]);
        }
        $query = "";
        $db->query("INSERT INTO productCategory (productID, categoryID)
        VALUES " . implode(', ', $catValues), ...$catIDs);
    }

} catch (\Throwable $th) {
    http_response_code(400);
    throw($th);
}
?>