<html>
    <head>
	<base target="bottom-frame">
</head>
<body>
<?php

session_start();

if(!empty($_REQUEST['form_products']))
{	
	if(!isset($_SESSION['products']))
	{	
		$_SESSION['products'][0] = $_REQUEST['form_products'];
		$_SESSION['quantity'][0] = $_REQUEST['quantity'];
		$_SESSION['id'][0] = $_REQUEST['prodId'];
		$_SESSION['quant'][0] = $_REQUEST['unitQuant'];
		$_SESSION['price'][0] = $_REQUEST['form_prod_price'];
	}
	else
	{
		$newProdID = $_REQUEST['prodId'];
		$match = false;
                for ($i=0; $i < sizeof($_SESSION['products']); $i++)
		{
			if($newProdID == $_SESSION['id'][$i])
			{
				$_SESSION['quantity'][$i]+=(int)$_REQUEST['quantity'];
				$match = true;				
				break;
			}
		}	
		if(!$match)
		{
			$_SESSION['products'][] = $_REQUEST['form_products'];
			$_SESSION['quantity'][] = $_REQUEST['quantity'];
			$_SESSION['id'][] = $newProdID;
			$_SESSION['quant'][] = $_REQUEST['unitQuant'];
			$_SESSION['price'][] = $_REQUEST['form_prod_price']; 
		}
	}
}
else
{
	print "<h1>No products to add <br> at the moment.</h1> <br>";
}

?>

<form id = "updateCart" action = "cart.php" method = "post"> 
<input type = "hidden" id = "update" name = "update">
<script type = "text/javascript">
    document.getElementById("updateCart").submit();     
</script>
</form>

</body>
</html>