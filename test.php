 <?php
$title = 'Test page'; // For header, document title
require_once 'include/class/db.php';
$db = new Database();

include_once 'include/header.php';
include_once 'include/navbar.php';
include_once 'include/class/logger.php';
?>
<script>
    let form = new FormData();
    form.append('id', 3);
    fetch('api/users/edit.php', {
        method: 'post',
        body: form
    })
        .then(response => {
            if (response.status != 200) {
                throw 'Status code: ' + response.status;
            }
            return response.json();
        })
        .then(products => console.log(products))
        .catch((e) => {
            console.error(e);
        })
</script>


<form action="api/products/add.php" method="POST" class="needs-validation" novalidate onsubmit="return validateLoginForm();">
    <h4 class="card-title">Log in</h4>
    <div class="form-group col-md-10">
        <label for="email">Email address</label>
        <input type="email" class="form-control <?php echo empty($errormsg['verifyPassword']) ? '' : 'is-invalid' ?>" id="email" name="email" value="<?php if (isset($_POST['email'])) {
                                                                                                                                                            echo htmlspecialchars($_POST['email']);
                                                                                                                                                        } ?>">
    </div>
    <div class="form-group col-md-10">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="viewpass">
        <label class="form-check-label" for="viewpass">Show password</label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php
include_once 'include/footer.php';
?>