let cartItems;
$(() => {
    getCartItems()
    .then((p) => {
        cartItems = p;
        insertCartItems();
    })

    $('#checkout').click(() => checkout());
})

function getCartItems() {
    return new Promise((resolve, reject) => {
        fetch('api/cart/list.php')
            .then(response => {
                if (response.status == 200)
                    return response.json()
                throw('Status: ', response.status)
            })
            .then(products => resolve(products))
            .catch((e) => {
                reject(e);
            })
    })
}

function insertCartItems() {
    $("#products").empty();
    let total = 0;
    cartItems.forEach((p) => {
        $("#products").append(`
        <li class="list-group-item">
            <div class="row">
                <div class="col-1"><img src="${p.pictures[0] ? 'img/' + p.pictures[0] : 'img/skelly.png'}" class="img-fluid" alt="Product image"></div>
                <div class="col-5">
                    <h6>${p.name}</h6>
                </div>
                <div class="col-2">${p.unitPrice} $</div>
                <div class="col-3">
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" value="${p.amount}" data-id="${p.id}" min="1" max="${p.unitsInStock}" placeholder="" onchange="updateCartItem(this)">
                        <button type="button" class="btn btn-outline-danger" value="-${p.amount}" data-id="${p.id}" onclick="updateCartItem(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `)
        total += p.unitPrice * p.amount
    });

    $("#products").append('<li><h4>Total price: &euro; ' + total.toFixed(2) + '</h4></li>');

}

function updateCartItem(obj) {
    const id = obj.getAttribute('data-id');
    const product = cartItems.find(x => x.id == id);

    // Update cart item
    let form = new FormData;
    form.append('id', obj.getAttribute('data-id'));
    form.append('amount', obj.value - product.amount);
    fetch('api/cart/edit.php', {
        method: 'post',
        body: form
    })
    .then(response => {
        if (response.status != 200) {
            throw 'Status code: ' + response.status;
        }
    })
    .then(() => {
        getCartItems()
        .then((p) => {
            cartItems = p;
            insertCartItems();
        })
    }) //success
    .catch((e) => {
        console.error(e);
    })
}

function checkout() {
    fetch('api/cart/place-order.php', {
        method: 'get',
    })
    .then(response => {
        if (response.status != 200) {
            throw 'Status code: ' + response.status;
        }
        return response.json()
    })
    .then((data) => {
        if (data.success) {
            alertMsg("Order placed, thanks for your money!", 'success');
            $('#checkedout').html(`
                <img class="mx-auto d-block" src="https://media4.giphy.com/media/SsTcO55LJDBsI/giphy.gif?cid=ecf05e47b34r0y2vhdphees2980ee3od0y8z5inhst6mjcvz&rid=giphy.gif&ct=g" alt="thanks for money!!">
            `);
            getCartItems()
            .then((p) => {
                cartItems = p;
                insertCartItems();
            })
        }
        else {
            let msg = '';

            for (const key in data.products) {
                msg += "<p>" + data.products[key].name + " has only " + data.products[key].unitsInStock + " left in stock.</p>";
            }

            alertMsg(msg ? msg : "Can't place order", 'danger')
        }
    })
    .catch((e) => {
        console.error(e);
    })
}