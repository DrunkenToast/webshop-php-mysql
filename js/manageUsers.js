let users;
// Can be called immediately, doesnt need to wait for document load
updateUsers();

$(() => {
    // Handle modal for adding a user (pop up window)
    $('#manageUser').modal();

    // Handle actions for modals (for add user and edit user)
    $('#manageUser')[0].addEventListener('show.bs.modal', (e) => {
        let btn = e.relatedTarget;
        let action = btn.getAttribute("data-bs-action");

        switch (action) {
            case 'add':
                fillModalAdd();
                break;
            case 'edit':
                let userId = btn.getAttribute("data-bs-userid");
                fillModalEdit(userId);
                break;
        }
    });
})

function updateUsers() {
    fetch('api/users/list.php')
        .then(response => response.json())
        .then(p => users = p)
        .then(() => insertUsers())
        .catch((e) => {
            console.error(e);
        });

}

function insertUsers() {
    $("#users").empty();
    $("#users").append(`
    <li class="list-group-item list-group-item-secondary col">
        <div class="row">
            <div class="col-1">
                <strong>ID</strong>
            </div>
            <div class="col-1">
                <strong>Name</strong>
            </div>
            <div class="col-2">
                <strong>E-mail</strong>
            </div>
            <div class="col-2">
                <strong>Address</strong>
            </div>
            <div class="col-1">
                <strong>Phone</strong>
            </div>
            <div class="col-2">
                <strong>Created at</strong>
            </div>
            <div class="col-3">
                <strong>Actions</strong>
            </div>
        </div>
    </li>
    `);
    users.forEach((u) => {
        let userHTML = `
        <li class="list-group-item col ${u.active ? '' : 'inactive'}">
            <div class="row">
                <div class="col-1">
                    <span>${u.id}</span>
                </div>
                <div class="col-1">
                    <span>${u.firstName + ' ' + u.lastName}</span>
                </div>
                <div class="col-2">
                    <span>${u.email}</span>
                </div>
                <div class="col-2">
                    <span>${u.address}</span>
                </div>
                <div class="col-1">
                    <span>${u.phone ? u.phone : 'None'}</span>
                </div>
                <div class="col-2">
                    <span>${u.DOC}</span>
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-sm btn-outline-primary"  data-bs-toggle="modal" data-bs-target="#manageUser" data-bs-action="edit" data-bs-userid="${u.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                        </svg>
                        Edit
                    </button>`
    
        if (u.roleId == 1) { // Admin
            userHTML += `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="setRole(${u.id}, 2)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                    <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                </svg>
                Demote
            </button>
            `
        }
        else {
            userHTML += `
            <button type="button" class="btn btn-sm btn-outline-success" onclick="setRole(${u.id}, 1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                </svg>
                Promote
            </button>`
        }
        if (u.active) {
            userHTML += `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="setActive(${u.id}, 0)">
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
            userHTML += `
            <button type="button" class="btn btn-sm btn-outline-success" onclick="setActive(${u.id}, 1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>
                Activate
            </button>`
        }

        userHTML += `
        </div>
            </div>
        </li>`

        $("#users").append(userHTML)
    });

    $("#users").children().each(function (i) {
        $(this).hide();
        $(this).delay(i * 50).fadeIn("fast");
    })
}

function editUser(form) {
    let formData = new FormData(form);

    fetch('api/users/edit.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
        // Commented because a failure already gives a message and it's a little bit jarring for the table to move
        // alertMsg('User edited successfully. For all changes to apply they need to restart their session.', 'success');
    })
    .catch((e) => {
        console.log(e)
        alertMsg('User could not be edited, possibly the email has been taken by another user.','danger')
    })

    updateUsers()

    return false;
}

function setRole(userID, roleID) {
    fillModalEdit(userID);

    $('#roleID').val(roleID);

    $('#manageUserForm').submit();

    updateUsers()

    return false;
}

function setActive(userId, isActive) {
    fillModalEdit(userId);

    $('#active').val(isActive);

    $('#manageUserForm').submit();

    updateUsers()

    return false;
}

function fillModalEdit(userId) {
    let user = users.find(x => x.id == userId);

    $('#manageUserForm').off();
    $('#manageUserForm').on('submit', function () {
        if (validateUserModalForm()) {
            $('#manageUser').modal('hide');
            return editUser(this);
        }
        return false;
    })

    $('#manageUserForm')[0].reset();
    $('#manageUserTitle').text("Edit user [" + userId + ']');
    $('#submitAction').text('Apply changes');

    // Apply data to forms
    $('#ID').val(user.id); // Important! It's a hidden field, otherwise editUser will fail
    $('#roleID').val(user.roleId); // Important! It's a hidden field, otherwise editUser will fail
    $('#active').val(user.active); // Important! It's a hidden field, otherwise editUser will fail
    $('#firstName').val(user.firstName);
    $('#lastName').val(user.lastName);
    $('#email').val(user.email);
    $('#address').val(user.address);
    $('#billingAddress').val(user.billingAddress);
    $('#phone').val(user.phone);
    $('#DOB').val(user.DOB);
}