function display_cart() {
    var cartIsEmpty = true;
    var x = document.getElementById("list").rows.length;
    var cartIsValid = true;
    $('#list tr[id]').each(function() {
        var productId = this.id.split('_')[1];
        var instock = parseInt($(this).data('instock'), 10);
        var quantity = parseInt($('#quantity_' + productId).text(), 10);
        if (quantity > instock) {
            alert('Product quantity exceeds the available stock.');
            cartIsValid = false;
            cartIsEmpty = false;
            return false;
        }
        cartIsEmpty = false;
    });

    if (x <= 1 || cartIsEmpty) {
        alert('Product list is empty. Please add product for checkout.');
        return false;
    }

    if (!cartIsValid) {
        return false;
    }

    $("body", parent.document).find('#left').hide();
    $("body", parent.document).find('#right').hide();
    $("body", parent.document).find('#bottom').css({
        'width': '100%',
        'height': '650px',
        'left': '0px',
        'top': '0px'
    });

    return true;
}

function displayCart() {
    $("body", parent.document).find('#bottom').show();
}

function clear() {
    alert('Cart Empty. Please Select The Product Again');
}

function searchProducts() {
    var searchInput = document.getElementById('search-box').value;
    var rightFrame = document.getElementById('right-frame');
    rightFrame.src = 'search.php?search=' + encodeURIComponent(searchInput);
}


function displaySearchResults(products) {
    var resultsContainer = document.createElement('div');
    resultsContainer.id = 'search-results';
    resultsContainer.classList.add('grid-view');
    document.getElementById('left').innerHTML = '';
    document.getElementById('left').appendChild(resultsContainer);

    products.forEach(function(product) {
        var productCard = `<div class="product-card">
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.unit}</p>
            <p>${product.price}</p>
            <p>${product.stock ? 'In Stock' : 'Out of Stock'}</p>
            <button>Add to Cart</button>
        </div>`;
        resultsContainer.insertAdjacentHTML('beforeend', productCard);
    });
}

function addToCart(product_id) {
    var quantity = $('#quantity').val();
    $.ajax({
        url: 'session.php',
        type: 'GET',
        data: {
            prodId: product_id,
            quantity: quantity
        },
        success: function(response) {
            var maxQuantity = response.maxQuantity;
            $('#quantity').attr('max', maxQuantity);
        }
    });
}