function alertMsg(message, type) {
    $('#alert').empty();
    $('#alert').append('<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>')
}