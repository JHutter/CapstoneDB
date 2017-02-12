<div id="login">
			<form action="" method="post">
			<label style="white-space:pre;"><strong>Username :</strong></label>
			<input id="name" name="username" placeholder="username" type="text">
			<br><br>
			<label style="white-space:pre;"><strong>Password : </strong></label>
			<input id="password" name="password" placeholder="**********" type="password">
			<br><br>
			<input class="button" name="submit" type="submit" value="      Login      ">
			<span><script>var isError = '<?php echo $error; ?>'; if (isError != "") {alert('Invalid password or username. Please try again.');}</script></span>
			</form>
		</div>