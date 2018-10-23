<?php

include 'header.php';

?>



<div class="panel panel-default">
<div class="panel-heading">
	<h3>
		<sup class="pull-right"><a href="subscribe.php">staff enroll another <span class="fa fa-repeat"></span></a></sup>
		<strong>Thank you</strong> for enrolling!
	</h3>
</div>
<div class="panel-body">

<?php
	if ($_GET["d"] == "1")
	{
		echo("<p class='alert alert-info'>That MVP number is already subscribed, so we've updated the contact information.</p>");
	}
?>		

<h4>Thank you for participating in this program to help provide us with health insights!</h4>

<!--
<h4>
Please take a moment to complete our health survey to help us understand how this program benefits the overall health of participants.
</h4>

<center><a class="btn btn-lg btn-primary" href="#" target="_blank">Take Survey <span class="fa fa-pie-chart"></span></a></center>
-->

</div>
</div>


<?php include 'footer.php' ?>