let user;
// Can be called immediately, doesnt need to wait for document load
updateUser();

$(() => {
    // Handle modal (pop up window)
    $('#manageUser').modal();

    $('#manageUser')[0].addEventListener('show.bs.modal', (e) => {
        fillModalEdit();
    });
})

function updateUser() {
    fetch('api/user/list.php')
        .then(response => response.json())
        .then(u => user = u)
        .then(() => updateProfile())
        .catch((e) => {
            console.error(e);
        });

}

function updateProfile() {
    $("#profile").empty();
    $("#profile").html(`
    <h3 class="card-title">${user.firstName + ' ' + user.lastName}</h3>
    <p class="card-text"><strong>E-mail:</strong> ${user.email}</p>
    <p class="card-text"><strong>Address:</strong> ${user.address}</p>
    <p class="card-text"><strong>Billing address:</strong> ${user.billingAddress ? user.billingAddress : 'None'}</p>
    <p class="card-text"><strong>Phone:</strong> ${user.phone ? user.phone : 'None'}</p>
    <p class="card-text"><strong>Date of birth:</strong> ${user.DOB ? user.DOB : 'None'}</p>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manageUser">Edit profile</button>
    `);
}

function editUser(form) {
    let formData = new FormData(form);

    fetch('api/user/edit.php', {
        method: "POST",
        body: formData,
    }).then((response) => {
        if (response.status != 200) {
            throw ('Status: ' + response.status);
        }
    })
    .catch((e) => {
        console.log(e)
        alertMsg('Couldn\'t edit your user profile. Email might\'ve been taken by someone else.', 'danger')
    }) //error

    updateUser()

    return false;
}

function fillModalEdit() {
    $('#manageUserForm').off();
    $('#manageUserForm').on('submit', function () {
        if (validateUserModalForm()) {
            $('#manageUser').modal('hide');
            return editUser(this);
        }
        return false;
    });

    $('#manageUserForm')[0].reset();
    $('#submitAction').text('Apply changes');

    // Apply data to forms
    $('#ID').val(user.id); // Important! It's a hidden field, otherwise editUser will fail
    $('#firstName').val(user.firstName);
    $('#lastName').val(user.lastName);
    $('#email').val(user.email);
    $('#address').val(user.address);
    $('#billingAddress').val(user.billingAddress);
    $('#phone').val(user.phone);
    $('#DOB').val(user.DOB);
}