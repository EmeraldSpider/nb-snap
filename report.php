<?php
use Medoo\Medoo;

include 'header.php';
require 'medoo.php';

if ($_SESSION['auth'] !== true || $_SESSION['auth_user'] != 'admin') {
	header('Location: login.php');
	exit;
}

?>



<div class="panel panel-default">
<div class="panel-heading">
	<h3>
		<sup class="pull-right"><a href="login.php?act=logout">staff logout <span class="fa fa-sign-out"></span></a>
		|
		<a href="subscribe.php">staff enroll another <span class="fa fa-repeat"></span></a>
		</sup>
		<strong>Subscription Report</strong>
	</h3>
</div>
<div class="panel-body">

<?php

	$database = new Medoo([
		'database_type' => 'sqlite',
		'database_file' => 'bullcitycool.sqlite3'
	]);

	$datas = $database->select("Subscribers", [
		"FirstName",
		"LastName",
		"Phone",
		"Email",
		"Code",
		"Date",
		"User",
		"Notes"
	]);

if ($_GET["d"] == "1") {
	ob_clean();
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=report.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$output = fopen('php://output', 'w');
	echo "First Name,Last Name,Phone,Email,MVP Number,Date,Staff,Notes\n";
	foreach($datas as $data)
	{
		fputcsv($output, $data);
	}
    fclose($output);

	exit();
} else {

echo '<div class="col-xs-12" style="margin-bottom:20px;">';
echo '<center><a href="?d=1" class="btn btn-primary btn-lg">Download Report <span class="fa fa-check"></span></a></center>';
echo '</div>';
 
	echo "<table class='table'><thead><tr><td>First Name</td><td>Last Name</td><td>Phone</td><td>Email</td><td>MVP Number</td><td>Staff</td><td>Notes</td><td>Date Subscribed</td></tr></thead><tbody>";

	foreach($datas as $data)
	{
		echo "<tr><td>" . $data["FirstName"] . "</td>" .
		"<td>" . $data["LastName"] . "</td>" . 
		"<td>" . $data["Phone"] . "</td>" . 
		"<td>" . $data["Email"] . "</td>" . 
		"<td>" . $data["Code"] . "</td>" . 
		"<td>" . $data["User"] . "</td>" .
		"<td>" . $data["Notes"] . "</td>" .
		"<td>" . date('F d, Y', strtotime($data["Date"])) . "</td></tr>";
	}

	echo "</tbody></table>";
}

?>

<style>
table.table thead td
{
	font-weight:bold;
}
</style>

</div>

</div>
</div>




<?php include 'footer.php' ?>