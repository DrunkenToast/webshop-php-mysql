<?php
session_start();
require_once 'include/errors.php';

$title = 'Shopping cart'; // For header, document title
$javascript = ['js/alert.js', 'js/cart.js'];
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-sm"></div>
    <div class="col-lg-8 card">
      <div class="card-body">
        <h3 class="card-title">Shopping cart</h3>
        <div id="alert"></div>
        <ul class="list-group" id="products">
        </ul>
        <div class="row">
            <div class="col"></div>
            <button id="checkout" class="col btn btn-primary btn-lg" type="submit">Checkout!</button>
            <div class="col"></div>
        </div>
        <div id="checkedout"></div>
      </div>
    </div>
    <div class="col-sm"></div>
  </div>
</div>


<?php
include_once 'include/footer.php';
?>