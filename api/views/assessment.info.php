</div><!-- close out of .container_12 -->

<div id="assessment" class="modal_fake">
	<form action="<?php echo $config['base_url'].'assessment/submit/'; ?>" method="post" accept-charset="utf-8">
		<?php
		// NOTE: The below code is taken from a much, much, much more complex piece of code.
		// It will not work as is (it's missing some variable declarations and such), but is provided as a guide.
		$script = '';

		$output = '';
		$output .= '
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("#assessment").addClass("rubric_false");
			});
		</script>
		';
		$output .= '<div class="container_12">'."\n\t\t\t";
		if ( isset($layout['header_img'][ preg_replace('% ([0-9].*)$%i', '', $tpl['assessment_name']) ]) ) {
			$output .= '<div class="header"><p class="title">'.$tpl['assessment_name'].'</p>';
			$output .= '<img src="'.$config['base_url'].'images/'.$layout['header_img'][ preg_replace('% ([0-9].*)$%i', '', $tpl['assessment_name']) ].'" alt="'.$tpl['assessment_name'].'" />';
			$output .= '<a href="javascript:window.print();" class="print">Print Assessment</a>';
			$output .= '</div>';
		} else {
			$output .= '<div class="header"><h1 class="title">'.$tpl['assessment_name'].'</h1>';
			$output .= '<a href="javascript:window.print();" class="print">Print Assessment</a>';
			$output .= '</div>';
		}
		$output .= '</div>'."\n\t\t";
		$output .= '<div class="container_16">'."\n\t\t\t";
		$output .= "\n\t\t\t".'<div class="grid_12 prefix_2 suffix_2">';
		echo $output;

		foreach($tpl['data'] as $e) {
			// dval($e);
			$label = $e->Stimulus;
			$id = $e->SurveyElementID;
			$type_id = (int) $e->SurveyElementTypeID;
			$type = '';
			$row_class = '';
			$output = '';

			switch ($type_id) {
				case 5:
				case 15:
					$type = 'heading';
					$row_class = 'heading';
					$output = '<h3 class="legend">'.str_replace('<br>', '', $label).'</h3>';
					break;
				case 19:
					$type = 'paragraph';
					$row_class = 'paragraph';
					$output = preg_replace('%\*([0-9a-z\'":_&#; ]+)\*%i', '<strong>$1</strong>', $label);
					$output = '<p>'.str_replace('<br><br>', '</p><p>', $output).'</p>';
					break;
				case 3:
				case 8:
					$type = 'text';
					$row_class = 'text';
					$output = '<input type="text" class="text" name="'.$name.'" id="'.$name.'" />';
					break;
				case 4:
					$type = 'textarea';
					$row_class = 'textarea';
					$output = '<textarea name="'.$name.'" rows="4" cols="60"></textarea>';
					break;
				case 2:
					if (!is_array($e->ElementChoice)) break;
					if ($e->ExclChoiceInterface == 'Select') {
						$row_class = 'select';
						$output = "\n\t\t\t".'<select name="'.$name.'">';
						foreach ($e->ElementChoice as $key => $option) {
							$output .= "\n\t\t\t\t".'<option value="'.$option->ID.'">'.$option->Text.'</option>';
						}
						$output .= "\n\t\t\t".'</select>';
					} else {
						$row_class = 'radio';
						foreach ($e->ElementChoice as $key => $option) {
							$output .= "\n\t\t\t\t\t\t".'<div class="row_radio">';
							$output .= "\n\t\t\t\t\t\t\t".'<input type="radio" name="'.$name.'" value="'.$option->ID.'" id="'.$name.'_'.$option->ID.'" />';
							$output .= "\n\t\t\t\t\t\t\t".'<label for="'.$name.'_'.$option->ID.'" class="post post_radio">'.$option->Text.'</label>';
							$output .= "\n\t\t\t\t\t\t".'</div>';
						}
					}
					break;
				case 1:
					if (!is_array($e->ElementChoice)) break;
					$row_class = 'checkbox';
					foreach ($e->ElementChoice as $key => $option) {
						$output .= "\n\t\t\t\t\t\t".'<div class="row_checkbox">';
						$output .= "\n\t\t\t\t\t\t\t".'<input type="checkbox" name="'.$name.'" value="'.$option->ID.'" id="'.$name.'_'.$option->ID.'" />';
						$output .= "\n\t\t\t\t\t\t\t".'<label for="'.$name.'_'.$option->ID.'" class="post post_radio">'.$option->Text.'</label>';
						$output .= "\n\t\t\t\t\t\t".'</div>';
					}
					break;
				default:
					break;
			}

			$name = 'q'.$type_id.'_'.$id;
			if ($type_id != 15 && $type_id != 5 && $type_id != 19) {
				$label_html = '<label for="'.$name.'" class="pre large">'.$label.'</label>';
				$output = '
				<div id="row_'.$name.'" class="row row_assessment row_type_'.$row_class.'">
					'.$label_html.'
					<div class="question_container question_container_'.$row_class.'">
						'.$output.'
					</div>
					<span class="clear">&nbsp;</span>
				</div>';
			}
			echo $output;
			$output = '';
		}
		echo "\n\t\t\t".'</div><!-- .grid_8 -->'."\n\t\t".'</div><!-- .container_12 -->';

		echo $output;
		?>

		<div class="row action">
			<div class="container_12">
				<div class="grid_3">
					&nbsp;
				</div>
				<div class="grid_9">
					<input type="submit" id="button" class="submit blue large primary" value="Submit Assessment" />
					<p>or <a class="secondary" href="javascript:history.back();">Cancel and Select a New Assessment</a></p>
					<input type="hidden" name="participant" value="<?php echo $tpl['participant']; ?>" />
					<input type="hidden" name="assessment" value="<?php echo $tpl['assessment']; ?>" />
				</div>
			</div>
			<span class="clear">&nbsp;</span>
		</div>
	</form>
</div>


<?php echo $script; ?>

<div class="container_12"><!-- reopen .container_12 -->