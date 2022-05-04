<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireAdmin();

require_once 'include/class/db.php';
$db = new Database();

$javascript = ['js/alert.js', 'js/checkForm.js', 'js/manageUsers.js'];
$title = 'Manage users'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="modal fade" id="manageUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageUserTitle">??? User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="manageUserForm">
                    <input type="hidden" id="ID" name="ID" value="">
                    <input type="hidden" id="roleID" name="roleID" value="">
                    <input type="hidden" id="active" name="active" value="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">First name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="lastName" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Billing address <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="billingAddress" name="billingAddress" placeholder="1234 Main St">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="phone" class="form-label">Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="0412 34 56 78">
                            <div class="invalid-feedback">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label for="DOB" class="form-label">Birthday <span class="text-muted">(Optional)</span></label>
                            <input type="date" id="DOB" name="DOB" class="form-control">
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="submitAction" type="submit" value="Submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
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
            <a href="register.php" class="btn btn-primary">Register user</a>
        </div>
        <hr>
    </div>

    <ul class="row list-group" id="users">
    </ul>
</div>


<?php
include_once 'include/footer.php';
?>