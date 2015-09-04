<?php include('../inc/header.php'); ?>
<?php if ( isset( $_GET['post'] ) ) {
$file = $_GET['post'] . '.txt';
$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT ); 
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2 class="date">';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= '<header><h3>';
			$output .= $lines[1];
			$output .= '</h3><header>';
			$n = 2;
			while ( $n <= count( $lines ) ) {
				$output .= '<p>';
				$output .= $lines[$n];
				$output .= '</p>';
				$n++;
			}
			$output .= '</article>';
			$output .= '</section>'; 
			echo $output;
		} else { echo "Nothing found."; } ?>
<?php include('../inc/footer.php'); ?>