<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireAdmin();

$title = 'Admin panel'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="card-group">
        <div class="card">
        <div class="card-body">
            <h5 class="card-title">Manage products</h5>
            <p class="card-text">Panel to add products or edit existing products</p>
            <a href="manage-products.php" class="btn btn-primary">Manage products</a>
        </div>
        </div>
        <div class="card">
        <div class="card-body">
            <h5 class="card-title">Manage categories</h5>
            <p class="card-text">Panel to add categories, edit existing categories or delete categories</p>
            <a href="manage-categories.php" class="btn btn-primary">Manage categories</a>
        </div>
        </div>
        <div class="card">
        <div class="card-body">
            <h5 class="card-title">Manage users</h5>
            <p class="card-text">Panel to add users, edit existing users or promote/demote users</p>
            <a href="manage-users.php" class="btn btn-primary">Manage users</a>
        </div>
    </div>
  </div>
</div>


<?php
include_once 'include/footer.php';
?>