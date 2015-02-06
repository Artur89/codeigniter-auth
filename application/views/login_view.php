<html>
<head>
	<title>Login</title>
</head>
<body>
<h1>Login</h1>

<?

echo form_open('main/login_validation');

echo validation_errors();
echo form_input('email', $this->input->post('email'));
echo form_password('password');
echo form_submit('login_submit', 'Login');

echo form_close();

?>

</body>
</html>