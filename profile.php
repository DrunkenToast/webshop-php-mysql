<?php
session_start();
require_once 'include/errors.php';
require_once 'include/class/require-role.php';
RequireRole::requireLoggedIn();

require_once 'include/class/db.php';
$db = new Database();

$javascript = ['js/alert.js', 'js/checkForm.js', 'js/profile.js'];
$title = 'Profile'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="modal fade" id="manageUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageUserTitle">Edit your profile</h5>
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
                            <label for="DOB" class="form-label">Birthday:</label>
                            <input type="date" id="DOB" name="DOB" class="form-control">
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="submitAction" type="submit" value="Submit" class="btn btn-primary">Apply changes</button>
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
        <div class="col-sm-12 col-md-8 col-lg-4  card">
            <div class="card-body" id="profile">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageUser">Edit profile</button>
            </div>
        </div>
    </div>
    <hr>
    <h3>Orders</h3>
    <ul class="row list-group" id="orders">
        <li class="list-group-item list-group-item-secondary col">
            <div class="row">
                <div class="col-1">
                    <strong>ID</strong>
                </div>
                <div class="col-2">
                    <strong>Payment date</strong>
                </div>
                <div class="col-2">
                    <strong>Ship date</strong>
                </div>
                <div class="col-1">
                    <strong>Fulfilled</strong>
                </div>
                <div class="col-6">
                    <strong>Products</strong>
                </div>
            </div>
        </li>
        <?php
            $db->query("SELECT ID, paymentDate, shipDate, fulfilled FROM `orders` WHERE userID = ? AND paymentDate IS NOT NULL", $_SESSION['user']['id'])
            ->bind($orderID, $paymentDate, $shipDate, $fulfilled);
            $orders = [];
            while ($db->fetch()) {
                array_push($orders, Array($orderID, $paymentDate, $shipDate, $fulfilled));
            }
            
            foreach (array_keys($orders) as $key) {
                echo '
                <li class="list-group-item col">
                <div class="row">
                    <div class="col-1">
                        <span>' . $orders[$key][0] . '</span>
                    </div>
                    <div class="col-2">
                        <span>' . $orders[$key][1] . '</span>
                    </div>
                    <div class="col-2">
                        <span>' . $orders[$key][2] . '</span>
                    </div>
                    <div class="col-1">
                        <span>' . ($orders[$key][3] ? 'Fulfilled' : 'Unfulfilled') . '</span>
                    </div>
                    <div class="col-6 list-group-item ">';


                $db->query("SELECT p.ID, p.name, p.unitPrice, op.amount FROM `products` as p
                LEFT JOIN orderProducts as op
                    ON (op.productID = p.ID)
                WHERE op.orderID = ?", $orders[$key][0])->bind($productID, $name, $price, $amount);

                while ($db->fetch()) {
                    $orders[$key][4] = Array($productID, $name, $price, $amount);
                    echo '
                    <div class="row">
                        <div class="col-1">
                            <span>' . $productID . '</span>
                        </div>
                        <div class="col-7">
                            <span>' . $name . '</span>
                        </div>
                        <div class="col-2">
                            <span> $ ' . $price . '</span>
                        </div>
                        <div class="col-2">
                            <span>x' . $amount . '</span>
                        </div>
                    </div>';
                }

                echo '</div>
                </div>
            </li>';
            }

        ?>
    </ul>
</div>


<?php
include_once 'include/footer.php';
?>