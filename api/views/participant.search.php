<div class="grid_7 bg">
	<h1><?php echo $tpl['h1']; ?></h1>

	<form name="frm_grades" method="get" action="<?php echo $config['self_url']; ?>">
		<div class="row">
			<div class="grid_3 alpha">
				<label for="search_participant" class="pre">Enter a Participant ID <br />or search by last name:</label>
			</div>
			<div class="grid_4 omega">
				<input type="text" name="search_participant" id="search_participant" class="text textLong" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
		<div class="row action">
			<div class="grid_3 alpha">
				&nbsp;
			</div>
			<div class="grid_4 omega">
				<input type="submit" id="button" class="submit blue large primary" value="Search" />
			</div>
			<span class="clear">&nbsp;</span>
		</div>
	</form>
</div>


<?php
if (!empty($tpl['data'])) {
	// dval($tpl['data']);
	$output = '';
	$output .= '<div class="grid_5 bg">';
	$output .= '<h2>Search Results:</h2>';
	$output .= '
		<table>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>ID</th>
				<th>Action</th>
			</tr>
	';

	foreach ( $tpl['data'] as $participant ) {
		$output .= '
			<tr>
				<td>'.$participant['name_first'].'</td>
				<td>'.$participant['name_last'].'</td>
				<td>'.$participant['case_id'].'</td>
				<td>
					<a href="' . $config['base_url'] . 'assessment/list/' . $participant['id'] . '">Proceed...</a>
				</td>
			</tr>
		';
	}
	$output .= '</table></div>';
	echo $output;
} elseif (isset($_GET['search_participant'])) { ?>
	<div class="grid_5 bg">
		<h2>Search Results</h2>
		<p>No results were found. Please try again. The ETO search works with partial matches but the search term must match the <em>beginning</em> of the <em>last name</em> that you are searching for. So searching for "foo" would "Foodey" for not "Afoo".</p>
	</div>

	<?php
	if (!empty($tpl['debug']) && $_GET['debug']) { ?>
	<div class="grid_5 debug bg">
		<h2>Debug</h2>
		
		<?php echo $tpl['debug']; ?>
	</div>
	<?php } ?>

<?php } ?>

