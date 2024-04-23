<?php
$servername = "74.48.21.122";
$username = "nana"; 
$password = "12345678";     
$dbname = "assignment1"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%{$search}%";
$sql = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
$sql->bind_param("s", $searchParam);
$sql->execute();
$result = $sql->get_result();

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '    <meta charset="UTF-8">';
echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '    <title>Search Results</title>';
echo '    <link rel="stylesheet" href="CSS/search.css">';
echo '</head>';
echo '<body>';
echo '    <div class="search-results-container">';
if ($result->num_rows > 0) {
    echo '        <ul class="search-results-list">';
    while($row = $result->fetch_assoc()) {
        echo '            <li class="search-result-item">';
        echo '                <div class="search-result-info">';
        echo '                    <h3>' . htmlspecialchars($row["product_name"]) . '</h3>'; 
        echo '                    <p>Price: $' . htmlspecialchars($row["unit_price"]) . '</p>'; 
        echo '                    <p>Unit Quantity: ' . htmlspecialchars($row["unit_quantity"]) . '</p>'; 
        echo '                    <p>In Stock: ' . htmlspecialchars($row["in_stock"]) . '</p>'; 
        echo '                    <a href="right.php?id=' . htmlspecialchars($row["product_id"]) . '">View Details</a>';
        echo '                </div>';
        echo '                <img src="'.htmlspecialchars($row["image_url"]).'" alt="Product Image" class="search-result-image">';
        echo '            </li>';
    }
    echo '        </ul>';
} else {
    echo '        <p>No results found for "'. htmlspecialchars($search) .'"</p>';
}
echo '    </div>';
echo '</body>';
echo '</html>';

$conn->close();
?>