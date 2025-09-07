<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Responsive Payment getway form design in Hindi</title>
	<link rel="stylesheet"  href="../public/css/payment.css">
</head>
<body>
<header>
	<div class="container">
		<div class="left">
			<h3>BILLING ADDRESS</h3>
			<form>
				Full name
				<input type="text" name="" placeholder="Enter name">
				Email
				<input type="text" name="" placeholder="Enter email">

				Address
				<input type="text" name="" placeholder="Enter address">
						
			</form>
		</div>
		<div class="right">
			<h3>PAYMENT</h3>
			<form>
				Accepted Card <br>
				<img src="../public/img/card1.png" width="100">
				<img src="../public/img/card2.png" width="50">
				<br><br>

				Credit card number
			<input type="text" name="" placeholder="Enter card number">
				
				Exp month
				<input type="text" name="" placeholder="Enter Month">
				<div id="zip">
					<label>
						Exp year
						<select>
							<option>Choose Year..</option>
							<option>2025</option>
							<option>2026</option>
							<option>2027</option>
							<option>2028</option>
						</select>
					</label>
						<label>
						CVV
						<input type="number" name="" placeholder="CVV">
					</label>
				</div>
			</form>
			<input type="submit" name="" value="Proceed to Checkout">
		</div>
	</div>
</header>
</body>
</html>