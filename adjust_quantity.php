<?php
session_start();

header('Content-Type: application/json'); 

if(isset($_POST['product_id']) && isset($_POST['new_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    if(in_array($product_id, $_SESSION['id'])) {
        $index = array_search($product_id, $_SESSION['id']);
        $_SESSION['quantity'][$index] = $new_quantity; 
        $new_value = $new_quantity * $_SESSION['price'][$index];
        $new_value_formatted = number_format($new_value, 2); 

        $cartPrice = 0;
        foreach ($_SESSION['price'] as $idx => $price) {
            $cartPrice += $price * $_SESSION['quantity'][$idx];
        }
        $cartPrice_formatted = number_format($cartPrice, 2); 

        echo json_encode(array(
            'product_id' => $product_id,
            'new_quantity' => $new_quantity,
            'new_value' => $new_value_formatted,
            'cart_total' => $cartPrice_formatted
        ));
    } else {
        http_response_code(400); 
        echo json_encode(array('error' => 'Product not in cart'));
    }
} else {
    http_response_code(400); 
    echo json_encode(array('error' => 'Invalid request'));
}
?>