let categories;
// Can be called immediately, doesnt need to wait for document load
updateCategories();

$(() => {
    // Handle modal (popup window) for adding
    $('#manageCategory').modal();

    // Handle actions for modals
    $('#manageCategory')[0].addEventListener('show.bs.modal', (e) => {
        let btn = e.relatedTarget;
        let action = btn.getAttribute("data-bs-action");

        switch (action) {
            case 'add':
                fillModalAdd();
                break;
            case 'edit':
                let categoryId = btn.getAttribute("data-bs-categoryid");
                fillModalEdit(categoryId);
                break;
        }
    });
})

function updateCategories() {
    fetch('api/categories/list.php')
        .then(response => response.json())
        .then(c => categories = c)
        .then(() => insertcategories())
        .catch((e) => {
            console.error(e);
        });
}

function insertcategories() {
    $("#categories").empty();
    $("#categories").append(`
    <li class="list-group-item list-group-item-secondary col">
        <div class="row">
            <div class="col-1">
                <strong>ID</strong>
            </div>
            <div class="col-8">
                <strong>Category name</strong>
            </div>
            <div class="col-3">
                <strong>Actions</strong>
            </div>
        </div>
    </li>
    `);
    categories.forEach((c) => {
        $("#categories").append(`
        <li class="list-group-item col">
            <div class="row">
                <div class="col-1">
                    <span>${c.id}</span>
                </div>
                <div class="col-8">
                    <span>${c.name}</span>
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manageCategory" data-bs-action="edit" data-bs-categoryid="${c.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                        </svg>
                        Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCategory(${c.id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                        Remove
                    </button>
                </div>
                </div>
            </div>
        </li>
        `)
    });

    $("#categories").children().each(function (i) {
        $(this).hide();
        $(this).delay(i * 50).fadeIn("fast");
    })
}

function addCategory(form) {
    let formData = new FormData(form);

    fetch('api/categories/add.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
        alertMsg('Category added successfully.', 'success');
    })
    .catch((e) => {
        console.log(e);
        alertMsg('Error occured, could not add category', 'danger');
    })

    updateCategories()

    return false;
}

function editCategory(form) {
    let formData = new FormData(form);

    fetch('api/categories/edit.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
    })
    .catch((e) => {
        console.log(e);
        alertMsg('Error occured, could not edit category', 'danger')
    })

    updateCategories()

    return false;
}

function removeCategory(id) {
    let formData = new FormData();
    formData.append("ID", id);
    fetch('api/categories/remove.php', {
        method: "POST",
        body: formData
    }).then((response) => {
        if (response.status != 200) {
            throw('Error occured, possibly the category is still linked with product(s). Status: ' + response.status)
        }
    })
    .catch((e) => alertMsg(e, 'danger')) //error

    updateCategories()

    return false;
}

function fillModalAdd() {
    $('#manageCategoryForm').off();
    $('#manageCategoryForm').on('submit', function () {
        return addCategory(this);
    })

    $('#manageCategoryForm')[0].reset();
    $('#manageCategoryTitle').text('Add category');
    $('#submitAction').text('Add');
}

function fillModalEdit(catID) {
    let category = categories.find(x => x.id == catID);

    $('#manageCategoryForm').off();
    $('#manageCategoryForm').on('submit', function () {
        return editCategory(this);
    })

    $('#manageCategoryForm')[0].reset();
    $('#manageCategoryTitle').text("Edit category [" + catID + ']');
    $('#submitAction').text('Apply changes');

    // Apply data to forms
    $('#ID').val(category.id); // Important! It's a hidden field, otherwise editCategory will fail
    $('#name').val(category.name);
}
