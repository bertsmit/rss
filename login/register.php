<?php
session_start();
	require_once '../classes/user.class.php';
	require_once '../classes/core.class.php';

	$core = new Core();

	if (isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['email'])) {
		if (filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
			$user = new User();
	        $user->setName(trim($_POST['name']))
	             ->setEmail(trim($_POST['email']))
	             ->setPassword(trim($_POST['password']));
	        $core->addUser($user);
	        header("Location: index.php");
	        die();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
</head>
<body>
	<form method="POST">
		name: <input type="text" name="name"><br>
		email: <input type="text" name="email"><br>
		pass: <input type="password" name="password"><br>
		<input type="submit" name="submit">
	</form>
</body>
</html>

<br><br><br><br>
<?php
	// echo print_r($core->getUserByName('willem'));
?>