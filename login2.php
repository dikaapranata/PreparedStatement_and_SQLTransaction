<?php
require_once('./db_login.php');
if (isset($_POST["submit"])) {
	$valid = TRUE;
	$username = $_POST['username'];
	if ($username == '') {
		$error_username = "Username is required";
		$valid = FALSE;
	}

	$password = $_POST['password'];
	if ($password == '') {
		$error_password = "Password is required";
		$valid = FALSE;
	}

	//insert data into database
	if ($valid) {
		//Asign a query
		$stmt = $db->prepare(" SELECT * FROM users WHERE username=? AND password=? ");
		$stmt->bind_param("ss", $username, $password);
		$stmt->execute();
		$result = $stmt->get_result();

		if (!$result) {
			die("Could not query the database: <br />" . $db->error . 'query = ' . $query);
		} else {
			if ($result->num_rows < 1) {
				$error_login = "Username atau password salah";
			} else {
				echo "<br>Login Berhasil";
			}
		}
		//close db connection
		$stmt->close();
		$db->close();
	}
}
?>
<?php include('../header.html') ?>
<br>
<div class="card">
	<div class="card-header">Login</div>
	<div class="card-body">
		<div class="error">
			<?php if (isset($error_login))
				echo $error_login; ?>
		</div>
		<form method="POST" autocomplete="on" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<div class="form-group">
				<label for="name">Username:</label>
				<input type="text" class="form-control" id="username" name="username" maxlength="50"
					value="<?php if (isset($username))
						echo $username; ?>">
				<div class="error">
					<?php if (isset($error_username))
						echo $error_username; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="name">Password:</label>
				<input type="password" class="form-control" id="password" name="password" maxlength="50">
				<div class="error">
					<?php if (isset($error_password))
						echo $error_password; ?>
				</div>
			</div>
			<button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>&nbsp;
			<a href="view_customer.php" class="btn btn-secondary">Cancel</a>
		</form>
	</div>
</div>
<?php include('../footer.html') ?>