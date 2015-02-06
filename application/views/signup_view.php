<html>
<head>
	<title>Sign up</title>
</head>
<body>
<h1>Sign up</h1>

<?

echo form_open('main/signup_validation');

echo validation_errors();
echo form_input('email', $this->input->post('email'));
echo form_password('password');
echo form_password('cpassword');
echo form_submit('signup_submit', 'Sign up');

echo form_close();

?>

</body>
</html>