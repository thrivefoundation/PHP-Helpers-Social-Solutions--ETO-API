<?php
$assessments = array(
	'Example Assessment 1' => 'gps',
	'Example Assessment 5' => 'other'
);
?>

<div class="grid_7 bg">
	<h1><?php echo $tpl['h1']; ?></h1>

	<form action="<?php echo $config['base_url'].'assessment/info/'; ?>" method="get" accept-charset="utf-8">
		<div class="row" style="margin-bottom:30px;">
			<div class="grid_3 alpha">
				<label for="grade_selection" class="pre">Filter by:</label>
			</div>
		</div>
		<span class="clear">&nbsp;</span>
		<div class="row">
			<div class="grid_3 alpha">
				<label for="assessment" class="pre">Select an Assessment</label>
				<p class="info">Current User: <strong><?php echo $tpl['participant_name']; ?></strong></p>
			</div>
			<div class="grid_4 omega">
				<select name="assessment" id="assessment">

					<?php
					$counter = 0;
					$output = '';
					foreach ($tpl['data'] as $assessment) {
						if ($assessments[$assessment['name']] == 'gps') {
							$output .= "\n\t\t\t\t\t\t".'<option value="'.$assessment['id'].'">'.$assessment['name'].'</option>';
							$counter++;
						}
					}
					if ($counter > 0) {
						echo '<optgroup label="GPS Rubrics">'.$output."\n\t\t\t\t\t".'</optgroup>';
					}
					?>

					<?php
					$counter = 0;
					$output = '';
					foreach ($tpl['data'] as $assessment) {
						if ($assessments[$assessment['name']] == 'other') {
							$output .= "\n\t\t\t\t\t\t".'<option value="'.$assessment['id'].'">'.$assessment['name'].'</option>';
							$counter++;
						}
					}
					if ($counter > 0) {
						echo '<optgroup label="Other Assessments">'.$output."\n\t\t\t\t\t".'</optgroup>';
					}
					?>
				</select>
			</div>
			<span class="clear">&nbsp;</span>
		</div>

		<div class="row action">
			<div class="grid_3 alpha">
				&nbsp;
			</div>
			<div class="grid_4 omega">
				<input type="submit" id="button" class="submit blue large primary" value="Take Assessment" />
				<input type="hidden" name="participant" value="<?php echo $tpl['participant']; ?>" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
	</form>
	<select name="temp" id="temp" style="display:none"></select>
</div>

<?php
if (!empty($tpl['debug']) && $_GET['debug']) { ?>
<div class="grid_5 debug bg">
	<?php echo $tpl['debug']; ?>
</div>
<?php } ?>
