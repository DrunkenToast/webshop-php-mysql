<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireAdmin();

require_once 'include/class/db.php';
$db = new Database();

function insertCategoryCheckboxes(Database $db) {
    $db->query("SELECT ID, name FROM categories")->bind($ID, $name);
    while ($db->fetch())
    {
    echo "<div>
            <input type=\"checkbox\" name=\"categories[]\" value=\"$ID\" id=\"cat$ID\">
            <label for=\"cat$ID\">" . htmlspecialchars($name) . "</label>
            </div>";
    }
}

$javascript = ['js/alert.js', 'js/manageProducts.js'];
$title = 'Manage products'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="modal fade" id="manageProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageProductTitle">??? product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manageProductForm">
                    <input type="hidden" id="ID" name="ID" value="">
                    <div class="mb-3">
                        <label for="name" class="">Name:</label>
                        <input name="name" type="text" class="form-control" id="name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="">Description:</label>
                        <textarea name="description" class="form-control" id="description"></textarea>
                    </div>
                    <div class="mb-3">
                    <label class="">Categories:</label>
                    <?php InsertCategoryCheckboxes($db);?>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image:</label>
                        <input class="form-control" type="file" id="image" name="image">
                    </div>
                    <div class="mb-3">
                        <img class="hidden" id="previewImage" src="img/skelly.png" alt="Current product image"></img>
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="active" class="">Active:</label>
                        <input name="active" type="checkbox" id="active" checked=checked>
                    </div>
                    <div class="mb-3">
                        <label for="unitsInStock" class="">Stock:</label>
                        <input name="unitsInStock" class="form-control" type="number" id="unitsInStock" min="0" max="999999" value="15">
                    </div>
                    <div class="mb-3">
                        <label for="unitPrice" class="">Unit price:</label>
                        <input name="unitPrice" class="form-control" type="number" id="unitPrice" step="0.01" min="0.01" value="5">
                    </div>
                    <div class="modal-footer">
                        <button id="submitAction" type="submit" value="Submit" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3 gx-5">
    <div class="row">
        <div id="alert"></div>
        <div class="col-lg-5 col-md-6 col-sm-12">
            <div class="input-group mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageProduct" data-bs-action="add">Add product</button>
                <span class="input-group-text" id="basic-addon1">Filter</span>
                <input type="text" id="search" class="form-control" placeholder="Product name">
            </div>
        </div>
        <hr>
    </div>

    <ul class="row list-group" id="products">
    </ul>
</div>

<?php
include_once 'include/footer.php';
?>