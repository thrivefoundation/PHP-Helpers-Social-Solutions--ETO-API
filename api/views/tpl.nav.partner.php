		<ul id="nav_sub_partners">
		<?php if (isset($_SESSION['SSOAuthToken'])) { ?>
			<li>Hello <?php echo $_SESSION['user'] ?></li>
			<li><a href="<?php echo $config['base_url'] ?>participant/search/">Select a New Participant</a></li>
			<li><a href="<?php echo $config['base_url'] ?>login/logout/">Logout</a></li>
		<?php } else { ?>
			<li>&nbsp;</li>
		<?php } ?>
		</ul>