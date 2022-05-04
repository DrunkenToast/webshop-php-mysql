function getProducts() {
    let filter = $('#filter').serialize();

    // Change URL with filter so that it can be bookmarked
    let newurl = window.location.href.split('?')[0] + '?' + filter;
    window.history.pushState({path: newurl}, '', newurl) ;

    fetch('api/products/list.php?available=1&' + filter)
        .then(response => response.json())
        .then(products => {
            products.sort((a, b) => a.name.toLowerCase() > b.name.toLowerCase());
            insertProducts(products);
        })
        .catch((e) => {
            console.error(e);
        })
}

function insertProducts(products) {
    $("#products").empty();
    products.forEach((p) => {
        let categories = '';
        for (const key in p.categories) {
            categories += `<a href="#" onclick="filterCat(${key})"><span class="badge rounded-pill bg-success ">${p.categories[key]}</span></a> `
        }

        $("#products").append(`
        <div class="product card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="${p.pictures[0] ? 'img/' + p.pictures[0] : 'img/skelly.png'}" class="img-fluid rounded-start" alt="Product image">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h3 class="card-title">${p.name}</h5>` + 
                        categories

                        +
                        `<p class="card-text">${p.description.replace(/(?:\r\n|\r|\n)/g, '<br>')}</p>
                        <h3 class="card-title">&euro; ${p.unitPrice.toFixed(2)}</h5>
                        <div class="btn-group">
                            <button id="buy${p.id}" class="btn btn-primary">Buy now</button>
                            <button id="add${p.id}" class="btn btn-outline-primary">Add to cart</button>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
        `)

        $("#buy" + p.id).off();
        $("#buy" + p.id).click(() => {
            if (typeof(loggedIn) != 'undefined') {
                buyNow(p.id);
            }
            else {
                window.location = 'login.php'
            }
        })

        $("#add"+ p.id).off();
        $("#add"+ p.id).click(() => {
            if (typeof(loggedIn) != 'undefined') {
                updateCartItem(p.id, 1);
            }
            else {
                window.location = 'login.php'
            }
        })
    });

    // Animate
    $("#products").children().each(function (i) {
        $(this).hide();
        $(this).delay(i * 50).fadeIn("fast");
    })
}               

async function updateCartItem(id, amount) {
    let form = new FormData;
    form.append('id', id);
    form.append('amount', amount);
    await fetch('api/cart/edit.php', {
        method: 'post',
        body: form
    })
    .then(response => {
        if (response.status != 200) {
            throw 'Status code: ' + response.status;
        }
    })
    .then()
    .catch((e) => {
        console.error(e);
    })
}

async function buyNow(id) {
    await updateCartItem(id, 1);
    window.location = 'cart.php';
}

$(() => {
    getProducts();

    $("#maxPriceLabel").text('$ ' + parseFloat($("#maxPrice")[0].value).toFixed(2))

    $("#maxPrice").on('input', function () {
        updateMaxPriceLabel()
    });
});

function filterCat(id) {
    $('[id^=cat]').prop('checked', false); // select all ID's beginning with cat, then uncheck
    $('#cat' + id).prop('checked', true);
    getProducts();
}


function updateMaxPriceLabel() {
    $("#maxPriceLabel").text('$ ' + parseFloat($("#maxPrice").val()).toFixed(2));
}