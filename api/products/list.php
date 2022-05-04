<?
require_once '../../include/errors.php';
require_once '../../include/class/db.php';
$db = new Database();
$products = [];

// $filters = Array(
//     "name"=>null,
//     "catagories"=>Array(),
//     "maxPrice"=>null,
// );

$filters = Array(
    null, null, null, null
);

// TODO request active etc

$query = "SELECT p.ID, p.name, p.description, p.unitPrice, p.unitsInStock, p.active FROM products as p";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['name']) && !empty($_GET['name']))
    {
        $filters[0] = "p.name LIKE '%" . str_replace(" ", "%", $db->escapeString($_GET['name'])) . "%'";
    }

    if (isset($_GET['categories']) && !empty($_GET['categories']) && is_array($_GET['categories']))
    {
        $catIds = Array();
        foreach ($_GET['categories'] as $cat)
        {
            array_push($catIds, (int)$cat);
        }
        $filters[1] = "c.categoryID IN (" . implode(",", $catIds) . ")";
    }

    if (isset($_GET['maxPrice']) && !empty($_GET['maxPrice']))
    {
        $filters[2] = "p.unitPrice <= " . (float)$_GET['maxPrice'];
    }

    if (isset($_GET['available']) && !empty($_GET['available']))
    {
        if ((bool)$_GET['available'] == 1) {
            $filters[3] = "p.active = 1 AND p.unitsInStock >= 1";
        }
    }

    foreach (array_keys($filters) as $f)
    {
        if ($filters[$f] == null) unset($filters[$f]);
    }
    
    // echo implode(" AND ", $filters);

    if (count($filters) > 0) 
    {
        $query .= " LEFT JOIN productCategory as c
            ON (p.id = c.productID)";
        $query .= " WHERE " . implode(" AND ", $filters);
        $query .= " GROUP BY p.ID";
    }
}

// TODO make prepared statement instead
// -> FIX: using escaped strings and casting right now
$db->query($query)->bind($ID, $name, $description, $unitPrice, $unitsInStock, $active);
while ($db->fetch())
{
    // casting and special chars to prevent HTML injections
    array_push($products,
        Array(
            "id" => (int)$ID,
            "name" => htmlspecialchars($name),
            "description" => htmlspecialchars($description),
            "unitPrice" => (float)$unitPrice,
            "unitsInStock" => (int)$unitsInStock,
            "active" => (bool)$active,
            "categories" => Array(),
            "pictures" => Array(),
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

echo json_encode($products)
?>