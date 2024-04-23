<html>
    <head>
        <title> Grocery Store</title>
        <link rel="stylesheet" href="CSS/checkout.css" type="text/css">
        <script src="JS/validate.js" type ="text/javascript"></script>
        <script src="JS/jquery.min.js"></script>
        <base target="bottom-frame">
    </head>

    <body>
        <div id="title">
			<h3>Product List</h3>
		</div>
        <div>
<?php

session_start();
	
if(isset($_SESSION['products']))
{	
	print "<table id = 'list'> <tr> 
			<th> Product ID</th> 
			<th> Product Name </th> 
			<th>Unit Quantity</th>
			<th> Unit Price ($)</th> 
			<th> Units in cart </th> 
			<th> Value in Cart ($) </th> ";

	$cartPrice = 0;
	for ($i=0;$i<sizeof($_SESSION['products']);$i++)
	{
		echo "<tr> <td align = 'center'>".$_SESSION['id'][$i]. "</td>
		<td align = 'center'>". $_SESSION['products'][$i]."</td>
		<td align = 'center'>".$_SESSION['quant'][$i]."</td>
		<td align = 'center'>". $_SESSION['price'][$i] ."</td>
		<td align = 'center'>". $_SESSION['quantity'][$i]."</td>";
		$tmp = $_SESSION['price'][$i]*$_SESSION['quantity'][$i];
		echo "<td align = 'center'> ".number_format($tmp,2) ."</td></tr>";
		$cartPrice += $tmp;
	}
        echo "</table>
		<br>
		<table id='total'>
		<tr> <td>Total price for ".sizeof($_SESSION['products']). " product(s) </td> <td align = 'center'> ".number_format($cartPrice,2)."</td></tr>
		</table>
		<hr/>
		<br/>";
		echo"<h3>&nbsp;&nbsp;&nbsp;&nbsp;Checkout</h3>
		<br/>
		<hr/>"; 

		// Form for user details
		echo'<form id = "check_out" name = "check_out" method = "POST" action = "email.php" onsubmit = "return validate_checkout()" >
			<table> <tr> <th colspan = "2" > Please enter your details: </th></tr> <tr> <td> Name:<span style = "color:red;">*</span> </td>
			<td> <input type = "text" id = "custName" name = "custName"> </td> </tr>
			<tr> <td> Phone Number:<span style = "color:red;">*</span>  </td>
			<td> <input type = "text" id = "phone" name = "phone"> </td> </tr>
			<tr> <td> Email<span style= "color:red;">*</span> </td>
			<td> <input type = "email" id = "email" name = "email"> </td> </tr>

			<tr> <td> Address:<span style = "color:red;">*</span> </td> 
			<td> <input type = "text" id = "address" name = "address"> </td> </tr>
			<tr> <td> Suburb:<span style = "color:red;">*</span></td>
			<td> <input type = "text" id = "suburb" name = "suburb"> </td> </tr>
			<tr> <td> Country:<span style = "color:red;">*</span>  </td>
			<td> <input type = "text" id = "country" name = "country"> </td> </tr>
			<tr> <td> State:<span style = "color:red;">*</span> </td>
			<td>
			  <select id="state" name="state" style="width: 95%; color: black;">
				<option value="NSW">NSW</option>
				<option value="VIC">VIC</option>
				<option value="QLD">QLD</option>
				<option value="WA">WA</option>
				<option value="SA">SA</option>
				<option value="TAS">TAS</option>
				<option value="ACT">ACT</option>
				<option value="NT">NT</option>
				<option value="Others">Others</option>
			  </select>
			</td> </tr>
			
			<tr> <td colspan ="2" align = "center"> <input type = "submit" value = "Purchase" ></td> </tr>
			</table>
		</form></div>';
}?>

<?php
if(!isset($_SESSION['products']))
{
	echo "<h3>Product list is empty. Please add product to Checkout.</h3>";
	echo '<a href="cart.php">Return to cart</a>';
}
?> 
</div>
</body>
</html>