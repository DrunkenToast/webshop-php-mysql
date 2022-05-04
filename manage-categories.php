<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireAdmin();

require_once 'include/class/db.php';
$db = new Database();

$javascript = ['js/alert.js', 'js/manageCategories.js'];
$title = 'Manage categories'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="modal fade" id="manageCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageCategoryTitle">??? category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manageCategoryForm">
                    <input type="hidden" id="ID" name="ID" value="">
                    <div class="mb-3">
                        <label for="name" class="">Name:</label>
                        <input name="name" type="text" class="form-control" id="name">
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
        <div class="col mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageCategory" data-bs-action="add">Add category</button>
        </div>
        <hr>
    </div>

    <ul class="row list-group" id="categories">
    </ul>
</div>


<?php
include_once 'include/footer.php';
?>