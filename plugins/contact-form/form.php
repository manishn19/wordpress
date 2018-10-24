<?php
/*
Contact form
*/
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="cf_form">
	<label>Your Name</label>
	<input type="text" name="cfname" id="cfname" placeholder="Name" value="Manish" required>
	<label>Your Email</label>
	<input type="email" name="cfemail" id="cfemail" placeholder="E-mail" value="dfg@fg.oj" required>
	<label>Your Phone</label>
	<input type="text" name="cfphone" id="cfphone" placeholder="Phone" minlength="10" value="123456789" required>
	<input type="submit" name="submit" id="cdsubmit" value="Submit">
</form>
