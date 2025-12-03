<?php
session_start();
include('penting/config.php');
if(isset($_POST['login']))
{
$email=$_POST['username'];
$password=md5($_POST['password']);
$sql = "SELECT * FROM admin WHERE UserName='$email' AND Password='$password'";
$query = mysqli_query($koneksidb,$sql);
$results = mysqli_fetch_array($query);
if(mysqli_num_rows($query)>0){
	$_SESSION['alogin']=$_POST['username'];
	$_SESSION['id']=$results['id'];
	echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
} else{
	echo "<script>alert('Email atau Password Salah!');</script>";
}
}

?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>Admin Login</title>

	<style>
		/* Background Gradient Biru Elegan */
		body {
			margin: 0;
			padding: 0;
			font-family: "Segoe UI", sans-serif;
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			background: linear-gradient(145deg, #0a1a3b, #0e3a78, #0a1e4f);
			background-size: 400% 400%;
			animation: gradientMove 10s ease infinite;
		}

		@keyframes gradientMove {
			0% {background-position: 0% 50%;}
			50% {background-position: 100% 50%;}
			100% {background-position: 0% 50%;}
		}

		/* Box Login */
		.login-box {
			width: 380px;
			background: rgba(255, 255, 255, 0.05);
			padding: 35px;
			border-radius: 18px;
			backdrop-filter: blur(10px);
			box-shadow: 0 0 25px rgba(0, 120, 255, 0.4);
			border: 1px solid rgba(255, 255, 255, 0.1);
			color: white;
			animation: fadeIn 1.2s ease;
		}

		@keyframes fadeIn {
			from {opacity: 0; transform: translateY(20px);}
			to   {opacity: 1; transform: translateY(0);}
		}

		/* Judul */
		.login-title {
			text-align: center;
			font-size: 28px;
			font-weight: 700;
			letter-spacing: 1px;
			margin-bottom: 20px;
			color: #5fb3ff;
			text-shadow: 0 0 10px rgba(0, 150, 255, 0.6);
		}

		/* Input */
		.input-group {
			margin-bottom: 20px;
		}

		label {
			font-size: 13px;
			opacity: 0.85;
		}

		input {
			width: 100%;
			padding: 12px;
			border-radius: 10px;
			border: none;
			outline: none;
			margin-top: 7px;
			font-size: 15px;
			background: rgba(255, 255, 255, 0.15);
			color: white;
			transition: 0.3s;
		}

		input:focus {
			background: rgba(255, 255, 255, 0.25);
			box-shadow: 0 0 10px rgba(0, 150, 255, 0.6);
		}

		/* Tombol Login */
		.btn-login {
			width: 100%;
			padding: 13px;
			border: none;
			border-radius: 10px;
			background: linear-gradient(135deg, #0d8bff, #0066cc);
			color: white;
			font-size: 16px;
			font-weight: bold;
			cursor: pointer;
			margin-top: 10px;
			transition: 0.25s;
			box-shadow: 0 0 10px rgba(0, 120, 255, 0.45);
		}

		.btn-login:hover {
			background: linear-gradient(135deg, #33a3ff, #0080ff);
			box-shadow: 0 0 18px rgba(0, 150, 255, 0.7);
			transform: scale(1.02);
		}
	</style>
</head>

<body>

	<div class="login-box">

		<div class="login-title">ADMIN LOGIN</div>

		<form method="post">

			<div class="input-group">
				<label>Username</label>
				<input type="text" name="username" placeholder="Masukkan Username" required>
			</div>

			<div class="input-group">
				<label>Password</label>
				<input type="password" name="password" placeholder="Masukkan Password" required>
			</div>

			<button class="btn-login" name="login" type="submit">LOGIN</button>

		</form>
	</div>

</body>
</html>
