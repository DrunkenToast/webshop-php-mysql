let products;
// Can be called immediately, doesnt need to wait for document load
updateProducts();

$(() => {
    // Handle modal for adding a product (pop up window)
    $('#manageProduct').modal();

    // Handle actions for modals (for add product and edit product)
    $('#manageProduct')[0].addEventListener('show.bs.modal', (e) => {
        let btn = e.relatedTarget;
        let action = btn.getAttribute("data-bs-action");

        switch (action) {
            case 'add':
                fillModalAdd();
                break;
            case 'edit':
                let productId = btn.getAttribute("data-bs-productid");
                fillModalEdit(productId);
                break;
        }
    });

    $('#search').change(() => updateProducts());
})

function updateProducts() {
    fetch('api/products/list.php?name=' + ($('#search').val() ? $('#search').val() : ''))
        .then(response => response.json())
        .then(p => products = p)
        .then(() => insertProducts())
        .catch((e) => {
            console.error(e);
            alertMsg('Could not load products', 'danger')
        });

}

function insertProducts() {
    $("#products").empty();
    $("#products").append(`
    <li class="list-group-item list-group-item-secondary col">
        <div class="row">
            <div class="col-1">
                <strong>ID</strong>
            </div>
            <div class="col-4">
                <strong>Product name</strong>
            </div>
            <div class="col-2">
                <strong>Stock</strong>
            </div>
            <div class="col-2">
                <strong>Price</strong>
            </div>
            <div class="col-3">
                <strong>Actions</strong>
            </div>
        </div>
    </li>
    `);
    products.forEach((p) => {
        let categories = '';
        for (const key in p.categories) {
            categories += `<a href="products.php?categories[]=${key}"><span class="badge rounded-pill bg-success ">${p.categories[key]}</span></a> `
        }

        let productHTML = `
        <li class="list-group-item col ${p.active ? '' : 'inactive'}">
            <div class="row">
                <div class="col-1">
                    <span>${p.id}</span>
                </div>
                <div class="col-4">
                    <span>${p.name}</span>
                </div>
                <div class="col-2">
                    <span>${p.unitsInStock}</span>
                </div>
                <div class="col-2">
                    <span>&euro; ${p.unitPrice}</span>
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-sm btn-outline-primary"  data-bs-toggle="modal" data-bs-target="#manageProduct" data-bs-action="edit" data-bs-productid="${p.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                        </svg>
                        Edit
                    </button>`
    
        if (p.active) {
            productHTML += `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="setActive(${p.id}, false)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                    <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                </svg>
                Deactivate
            </button>
            `
        }
        else {
            productHTML += `
            <button type="button" class="btn btn-sm btn-outline-success" onclick="setActive(${p.id}, true)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>
                Activate
            </button>`
        }

        productHTML += `
        </div>
            </div>
        </li>`

        $("#products").append(productHTML)
    });

    $("#products").children().each(function (i) {
        $(this).hide();
        $(this).delay(i * 50).fadeIn("fast");
    })
}

function addProduct(form) {
    let formData = new FormData(form);
    formData.append('image', $('#image')[0].files[0]);

    fetch('api/products/add.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
        alertMsg('Product added successfully.', 'success');
        updateProducts();
    })
    .catch((e) => {
        console.error(e);
        alertMsg('Could not add product', 'danger')
    })

    return false;
}

function editProduct(form) {
    let formData = new FormData(form);
    formData.append('image', $('#image')[0].files[0]);

    fetch('api/products/edit.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
        // Commented because a failure already gives a message and it's a little bit jarring for the table to move
        // alertMsg('Product edited successfully.', 'success');
    })
    .catch((e) => {
        console.error(e);
        alertMsg('Could not edit product', 'danger')
    })

    updateProducts()

    return false;
}

function setActive(productId, isActive) {
    fillModalEdit(productId);

    $('#active').prop("checked", isActive);

    $('#manageProductForm').submit();

    updateProducts()

    return false;
}

function fillModalAdd() {
    $('#manageProductForm').off();
    $('#manageProductForm').on('submit', function () {
        return addProduct(this);
    })

    $('#manageProductForm')[0].reset();
    $('#manageProductTitle').text('Add product');
    $('#submitAction').text('Add');

    $("#previewImage").addClass("hidden");
}

function fillModalEdit(productId) {
    let product = products.find(x => x.id == productId);

    $('#manageProductForm').off();
    $('#manageProductForm').on('submit', function () {
        return editProduct(this);
    })

    $('#manageProductForm')[0].reset();
    $('#manageProductTitle').text("Edit product [" + productId + ']');
    $('#submitAction').text('Apply changes');

    // Apply data to forms
    $('#ID').val(product.id); // Important! It's a hidden field, otherwise editProduct will fail
    $('#name').val(product.name);
    $('#description').val(product.description);
    $('#active').prop("checked", product.active);
    $('#unitsInStock').val(product.unitsInStock);
    $('#unitPrice').val(product.unitPrice);
    // Check categories
    for (const key in product.categories) {
        $("#cat" + key).prop("checked", true);
    }
    // Show currently set image
    $("#previewImage").addClass("hidden");
    if (product.pictures.length > 0) {
        $("#previewImage").prop('src', 'img/' + product.pictures[0]);
        $("#previewImage").removeClass("hidden");
    }
}