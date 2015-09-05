<?php include('../inc/header.php'); ?>
<?php if ( !strstr($_SERVER['REQUEST_URI'],'?post=')){
    header('HTTP/1.0 404 Not Found');
    $lines = file_get_contents ( '../_error/error.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
    echo $lines;
    exit();
} ?>
<?php if ( isset( $_GET['post'] ) ) {
	$file = $_GET['post'] . '.txt';
	if ( !file_exists( getcwd().'/'.$file  ) ){
    header('HTTP/1.0 404 Not Found');
    $lines = file_get_contents ( '../_error/error.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
    echo $lines;
    exit();
}
		$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT ); 
		$link = $_GET['post'];
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2 class="date">';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= "<header><h3><a href=\"single.php?post={$link}\">";
			$output .= $lines[1];
			$output .= '</a></h3><header>';
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
		} ?>
<?php include('../inc/footer.php'); ?>