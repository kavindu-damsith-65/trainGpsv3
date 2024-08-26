<!DOCTYPE html>
<html>

<head>
	<title>Train scheduler</title>
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords"
		content="App Loction Form,Login Forms,Sign up Forms,Registration Forms,News latter Forms,Elements" . />
	<script
		type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	</script>
	<!--webfonts-->
	<link
		href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700,800'
		rel='stylesheet' type='text/css'>
	<!--//webfonts-->
</head>

<body>
	<h1 style="color:#323542;">T R A I N &nbsp;&nbsp;&nbsp;S C H E D U L E R</h1>
	<div class="app-location">
		<h2>Please input your details</h2>
		<div class="line"><span></span></div>
		<div class="location"><img src="images/location.png" class="img-responsive" alt="" /></div>
		<form action="app_model/indexUser.php">
			<input type="text" list="browsers" class="text" name="train" id="train" value="Enter train name"
				onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter train name';}">

			<datalist id="browsers">
				<option value="Podi manike">	
				<option value="Udarata manike">
				<option value="Yaldewi">

			</datalist>
			<input type="text" list="browsers1" name="station" id="station" value="Enter train station"
				onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter train station';}">
			<datalist id="browsers1">

				<option value="Maradana">
				<option value="Dematagoda">
				<option value="Kelaniya">
				<option value="Wanawasala">
				<option value="Hunupitiya">
				<option value="Ederamulla">
				<option value="Horape">
				<option value="Ragama Junction">
				<option value="Walpola">
				<option value="Batuwaththa">
				<option value="Bulugahagoda">
				<option value="Ganemulla">
				<option value="Yagoda">
				<option value="Gampaha">
				<option value="Daraluwa">
				<option value="Bemmulla">
				<option value="Magalegoda">
				<option value="Heendeniya Pattiyagoda">
				<option value="Veyangoda">
				<option value="Wadurawa">
				<option value="Keenawala">
				<option value="Pallewela">
				<option value="Ganegoda">
				<option value="Wijaya Rajadahana">
				<option value="Meerigama">
				<option value="Wilwatta">
				<option value="Botale">
				<option value="Ambepussa">
				<option value="Yaththalgoda">
				<option value="Bujjomuwa">
				<option value="Alawwa">
				<option value="Walakumbura">
				<option value="Polgahawela Junction">
				<option value="Panaliya">
				<option value="Tismalpola">
				<option value="Yatagama">
				<option value="Rambukkana">
				<option value="Kadigamuwa">
				<option value="Gangoda">
				<option value="Ihala Kotte">
				<option value="Balana">
				<option value="Kadugannawa">
				<option value="Pilimatalawa">
				<option value="Peradeniya Junction">
				<option value="Sarasavi Uyana">
				<option value="Kandy">
			</datalist>
			<div class="submit"><input type="submit" onclick="myFunction()" value="Search"></div>
			<div class="clear"></div>

		</form>
		<div class="submit" ><a style="color:white;font-size:20px" href="app_model/logIn.php">Are you a Train Driver?</a></div>
            
            

	</div>


</body>

</html>