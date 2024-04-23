<?php
session_start();

$response = array('success' => false, 'new_total' => 0);

if(isset($_POST['product_id']) && isset($_SESSION['products'])) {
    $productId = $_POST['product_id'];
    $productIndex = array_search($productId, $_SESSION['id']);
    
    if($productIndex !== false) {
        array_splice($_SESSION['products'], $productIndex, 1);
        array_splice($_SESSION['id'], $productIndex, 1);
        array_splice($_SESSION['quant'], $productIndex, 1);
        array_splice($_SESSION['price'], $productIndex, 1);
        array_splice($_SESSION['quantity'], $productIndex, 1);

        $cartPrice = 0;
        foreach ($_SESSION['price'] as $i => $price) {
            $cartPrice += $price * $_SESSION['quantity'][$i];
        }
        $response = array('success' => true, 'new_total' => number_format($cartPrice, 2, '.', ''));
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
