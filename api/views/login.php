<div class="grid_5 bg">
	<h1><?php echo $tpl['h1']; ?></h1>

	<form name="frm_login" action="#" method="post">
		<p>Please login using the same username/password combination that you'd use at the <a href="https://secure.etosoftware.com/">ETO website</a>.</p>

		<?php if ($tpl['error']) { ?>
		<div class="row error">
			<div class="grid_1 alpha">
				<label for="LoginError" class="label_pre error">Error!</label>
			</div>
			<div class="grid_4 omega error">
				<p class="error">We encountered an error while attempted to log you into ETO.</p>
				<p class="error">The error response was:</p>
				<?php echo $tpl['error']; ?>
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<?php } ?>

		<div class="row">
			<div class="grid_1 alpha">
				<label for="txt_user" class="pre">Username</label>
			</div>
			<div class="grid_4 omega">
				<input type="text" name="txt_user" id="txt_user" class="text text_med" value="<?php echo (isset($_POST['txt_user'])) ? $_POST['txt_user'] : ''; ?>" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<div class="row">
			<div class="grid_1 alpha">
				<label for="txt_pass" class="pre">Password</label>
			</div>
			<div class="grid_4 omega">
				<input type="password" name="txt_pass" id="txt_pass" class="text text_med" value="<?php echo (isset($_POST['txt_pass'])) ? $_POST['txt_pass'] : ''; ?>" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>

		<?php if (isset($tpl['sites'])) { ?>
		<div class="row">
			<div class="grid_1 alpha">
				<label for="site_id" class="pre">Site</label>
			</div>
			<div class="grid_4 omega">
				<select name="site_id" id="site_id">
					<?php
					foreach ($tpl['sites'] as $sites => $site) {
						echo '<option value="'.$site->Key.'">'.$site->Value.'</option>'."\n\t\t\t\t\t\t";
					}
					?>
				</select>
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<?php } ?>

		<?php if (isset($tpl['programs'])) { ?>
		<div class="row">
			<div class="grid_1 alpha">
				<label for="site_id" class="pre">Site</label>
			</div>
			<div class="grid_4 omega">
				<pre style="margin:0;"><?php echo $_POST['site_id']; ?></pre>
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<div class="row">
			<div class="grid_1 alpha">
				<label for="program_id" class="pre">Program</label>
			</div>
			<div class="grid_4 omega">
				<select name="program_id" id="program_id">
					<?php
					foreach ($tpl['programs'] as $program) {
						echo '<option value="'.$program->ID.'">'.$program->Name.'</option>'."\n\t\t\t\t\t\t";
					}
					?>
				</select>
				<input type="hidden" name="site_id" value="<?php echo $_POST['site_id']; ?>" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<?php } ?>


		<div class="row action">
			<div class="grid_1 alpha">
				&nbsp;
			</div>
			<div class="grid_4 omega">
				<input type="submit" name="button" id="button" class="submit blue large primary" value="Login" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
	</form>
	<span class="clear">&nbsp;</span>
</div>

<div class="grid_7">
 <!-- YouTube video screencast showing login directions went here. -->
</div>

<?php
if (!empty($tpl['debug']) && $_GET['debug']) { ?>
<div class="grid_5 debug bg">
	<?php echo $tpl['debug']; ?>
</div>
<?php } ?>
