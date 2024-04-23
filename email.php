<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="CSS/checkout.css" type="text/css">
    <base target="bottom-frame">
  </head>
  <body>
    <?php

    require 'C:\xampp\htdocs\rmb\vendor\autoload.php';
   
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    session_start();
    
    error_reporting(E_ALL);
    
    $servername = "74.48.21.122";
    $username = "nana"; 
    $password = "12345678";     
    $dbname = "assignment1"; 
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sqlCreateTable = "CREATE TABLE IF NOT EXISTS customer_orders (
      order_id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      address VARCHAR(255) NOT NULL,
      suburb VARCHAR(100),
      state VARCHAR(100),
      country VARCHAR(100),
      phone VARCHAR(20),
      email VARCHAR(255) NOT NULL,
      total_price DECIMAL(10,2) NOT NULL,
      order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
  if ($conn->query($sqlCreateTable) === FALSE) {
      echo "Error creating table: " . $conn->error;
  }

    $cartPrice = 0;
    for ($i=0; $i < sizeof($_SESSION['products']); $i++) {
      $tmp = $_SESSION['price'][$i] * $_SESSION['quantity'][$i];
      $cartPrice += $tmp;
    }
    $total_price = number_format($cartPrice,2); 

    $name = $_REQUEST['custName'];
    $address = $_REQUEST['address'];
    $suburb = $_REQUEST['suburb'];
    $state = $_REQUEST['state'];
    $country = $_REQUEST['country'];
    $phone = $_REQUEST['phone'];
    $email = $_REQUEST['email'];
    
    $total_price = number_format($cartPrice,2); 
    $sqlInsertCustomer = "INSERT INTO customer_orders (name, address, suburb, state, country, phone, email, total_price)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmtInsertCustomer = $conn->prepare($sqlInsertCustomer);
    if(!$stmtInsertCustomer) {
        echo "Error preparing insert statement: " . $conn->error;
    } else {
        $stmtInsertCustomer->bind_param('sssssssd', $name, $address, $suburb, $state, $country, $phone, $email, $total_price);
        $stmtInsertCustomer->execute();
        if($stmtInsertCustomer->error) {
            echo "Error inserting new customer order record: " .$stmtInsertCustomer->error;
        } else {
            //echo "Order information saved successfully.<br>";
        }
        $stmtInsertCustomer->close();
    }

    $subject = "Grocery Store - Details of order placed.";
    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Grocery Store <test@test.com>\r\n"; 
    $message = "Dear $name, Thank you for placing an online order with us. Here are the items that you ordered:<br><br>
                <table id = 'list'> 
                <tr> <th> Product ID</th> <th> Product Name </th> <th>Unit Quantity</th> <th> Unit Price ($)</th> <th> Units in cart </th> <th> Value in Cart ($) </th></tr>";

    $cartPrice = 0;
    for ($i=0;$i<sizeof($_SESSION['products']);$i++){
        $message .="<tr>
          <td align = 'center'>". $_SESSION['id'][$i]. "</td>
          <td align = 'center'>". $_SESSION['products'][$i]."</td>
          <td align = 'center'>". $_SESSION['quant'][$i]."</td>
          <td align = 'center'>". $_SESSION['price'][$i] ."</td>
          <td align = 'center'>". $_SESSION['quantity'][$i]."</td>";
        $tmp = $_SESSION['price'][$i]*$_SESSION['quantity'][$i];
        $cartPrice += $tmp;
        $message .= "<td align = 'center'>".number_format($tmp,2) ."</td></tr>";
    }
    $message .="</table><br>Total price for ".sizeof($_SESSION['products']). " product(s): $".number_format($cartPrice,2);
    
   $mail = new PHPMailer(true);
   try {
       $mail->isSMTP();                                     
       $mail->Host = 'smtp.qq.com';                         
       $mail->SMTPAuth = true;                              
       $mail->Username = 'xxx@qq.com';               
       $mail->Password = 'xxx';                
       $mail->SMTPSecure = 'ssl';                           
       $mail->Port = 465;                                   
       $mail->setFrom('xxx@qq.com', 'Grocery Store');   
           $mail->addAddress($email);                      
       $mail->isHTML(true);                                  
       $mail->Subject = $subject;
       $mail->Body    = $message;
       $mail->send();
       echo '';
   } catch (Exception $e) {
       echo '', $mail->ErrorInfo;
   }    
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_SESSION['products'])) {
        for ($i=0;$i<sizeof($_SESSION['products']);$i++) {
            $product_id = $_SESSION['id'][$i];
            $quantity = $_SESSION['quantity'][$i];
            
            $sql = "UPDATE products SET in_stock = in_stock - ? WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            if(!$stmt) {
                echo "Error preparing statement: " . $conn->error;
            } else {
                $stmt->bind_param('ii', $quantity, $product_id);
                $stmt->execute();
    
                if ($stmt->error) {
                    echo "Error updating record: " . $stmt->error;
                }
            }
        }
            $stmt->close();
        $conn->close();
        
        session_destroy();
        
        echo "<div style='text-align: center;'>";
        echo "<br><br>Dear $name,<br>Thank you for placing an online order with us.<br>";
        echo "Your mail has been sent. Your order will be delivered soon.<br><br>";
        echo "<a href='index.html' target='_blank'>Return to cart</a>";
        echo "</div>";
    } else {
        echo "<div style='text-align: center;'>";
        echo "<h3>Product list is empty. Please add products to Checkout.</h3>";
        echo "<a href='index.html' target='_blank'>Return to cart</a>";
        echo "</div>";
    }
    ?>
  </body>
</html>
