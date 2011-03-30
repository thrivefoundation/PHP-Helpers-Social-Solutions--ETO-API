<div class="grid_7 bg">
	<h1><?php echo $tpl['h1']; ?></h1>

	<?php
	if ($tpl['error']) {
		$output = '<p class="error">There was an error. Please click the "help" button on the right to let us know about this, or try <a href="javascript:history.back();">going back to the assessment</a> to try again.</p>';
	} else {
		$output = '<p>Success! Your assessment response ID is <strong>'.$tpl['data']['response']->AddAssessmentResponseResult->SurveyResponseID.'</strong> for the assessment named "<strong>'.$tpl['data']['response']->AddAssessmentResponseResult->SurveyName.'</strong>".</p>';
		$output .= '<p><strong>You will be redirected to the assessment selection page in 5 seconds.</strong></p>';
		$output .= '
			<script type="text/javascript" charset="utf-8">
				setTimeout("window.location = \'../list/'.$tpl['data']['response']->AddAssessmentResponseResult->CL_EN_GEN_ID.'\'", 5000);
				// console.info(\''.$tpl['data']['response']->AddAssessmentResponseResult->CL_EN_GEN_ID.'\');
			</script>';
	}
	echo $output;
	?>
	<?php // dval($tpl['data']['request']); ?>
	<?php // dval($tpl['data']['response']); ?>
</div>