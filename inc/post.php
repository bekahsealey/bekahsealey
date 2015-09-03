<?php 

function writePosts($ext, $postsPerPage) {
	require_once( 'buildlist.php' );
	$dir = getcwd();


	$posts = buildFileList($dir, $ext);
	
	if ( $posts ) { 
		foreach ( $posts as $post ) { 
			$lines = file( $post, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT ); 
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2>';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= '<header><h3>';
			$output .= $lines[1];
			$output .= '</h3><header>';
			$i = 2;
			while ( $i <= count( $lines ) ) {
				$output .= '<p>';
				$output .= $lines[$i];
				$output .= '</p>';
				$i++;
			}
			$output .= '</article>';
			$output .= '</section>';
		} echo $output;
	} else { $output = "No posts found."; } 
}

?>