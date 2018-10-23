<?php include 'header.php'; ?>

<div class="col-sm-offset-2 col-md-8">

<div class="panel panel-default">
<div class="panel-heading"><h3><strong>Staff Login</strong></h3></div>
<div class="panel-body">

<?php

if ($_GET['act'] === 'logout') {
	$_SESSION['auth'] = false;
}


$form = new Formr('bootstrap');
$form->id = 'login';


if ($form->submit() && $form->post("pass") > '' && $form->post("user") > '')
{
	$logins = file_get_contents('logins.txt');
	if ($logins > '')
	{
		$logins = str_replace("\n",'|',str_replace("\r",'|', $logins));

		$log = $form->post("user") . '/' . $form->post("pass") . '|';

		if (stripos($logins, $log) !== FALSE)
		{
			$_SESSION['auth'] = true;
			$_SESSION['auth_user'] = $form->post("user");
			header('Location: subscribe.php');
			exit;
		} else {
			echo '<p class="alert alert-danger">Invalid username or password.</p>';
		}
	} else {
		echo '<p class="alert alert-danger">Logins not setup!</p>';
	}
}


$form->required = '*';
$form->action = 'login.php';
echo $form->form_open();

echo '<div class="col-xs-12">';
echo $form->input_text('user','User Name:','','user','placeholder="User Name"');
echo '</div>';
echo '<div class="col-xs-12">';
echo $form->input_password('pass','Password:','','pass','placeholder="Password"');
echo '</div>';

echo '<div class="col-xs-12">';
echo '<center><button type="submit" class="btn btn-primary btn-lg">Login</button></center>';
echo '</div>';

echo $form->form_close();


?>

</div>
</div>
</div>


<?php include 'footer.php' ?>