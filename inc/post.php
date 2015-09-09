<?php 
require_once( 'buildlist.php' );

function writePosts($ext, $postsPerPage, $paginate, $comments) {
	
	$dir = getcwd();
	
	// collect post files
	$posts = buildFileList($dir, $ext);
	
	if ( $posts ) { 
		
		if ( $_GET['cat'] || $_GET['tag'] ) {
			$error = false;
			if( empty( $_GET ) ) { $error = true; }
			
			if ( $error == false ) {
				$_GET['cat'] ? $term = $_GET['cat'] : $term = $_GET['tag'];
				$_GET['cat'] ? $s = '[' : $s = '{';
				foreach ( $posts as $post ) {
					$handle = fopen( $post, 'r');
					$cat_posts = ''; // init as false
					while (($buffer = fgets($handle)) !== false) {
						if (strpos($buffer, $s) !== false) {
							if (strpos($buffer, $term ) !== false) {
								$term_posts[] = $post;
								break; // Once you find the string, you should break out the loop.
							} 
						}
					}
				fclose($handle);
				}
				if ( empty( $term_posts ) ) { $error = true; }
				if ( $error == false ) {
					$posts = $term_posts;
					echo "<h3>Posts on {$term}</h3>";
				}
				if ( $error ) {
				// if no term or term doesn't exist
					echo "<h3>Sorry, no posts found.</h3>";
					return;
				}
			}
			
		}
    
		$posts = str_replace( ".{$ext}", '', $posts );
	
		// sort array function included with buildlist
		usort($posts, 'blogsort');
	
		//set global max posts per page value
		define('MAX_PER_PAGE',10);
	
		// If $_GET['perpage'] is set, use it. If not use incoming $postsPerPage value.
		$numPosts = ctype_digit((string)$_GET['perpage']) ? $_GET['perpage'] : $postsPerPage;

		//Initialize page counting variables
		$ostart = $start = max(1, ctype_digit((string)$_GET['page']) ? $_GET['page'] : 1) - 1;
	
		// count total post files
		$totalPosts = count($posts);

		// get total number of pages
		$numPages = ceil($totalPosts / $numPosts);
	
		// make sure $numPosts doesn't exceed global max(also sets $ostart if it was invalid; used later)
		$numPosts = min(MAX_PER_PAGE, max(1, $numPosts));
	
		if ($start * $numPosts > $totalPosts ) {
			//if page number exceeds the max number of pages from posts, serve last 5 posts
			$ostart = $start = min(0, $totalPosts - $numPosts);
			echo "<p class='error'>Oops! That page does not exist. Retrieving newest posts.</p>";
		}
		else {
			//set $start to $numPosts * page number
			$start *= $numPosts;
		}

		// Only grab the part of the array we need
		$sliced = array_slice($posts, $start, $numPosts);


		// loop through posts, but break early if we run out
		for ($i = 0; $i < $numPosts && isset($sliced[$i]); $i++ ) { 
			$lines = file( $sliced[$i] . ".{$ext}", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT ); 
			$link = $sliced[$i];
			$comment_link = "single.php?post={$link}#disqus_thread";
			postContent( $lines, $link, $comment_link );
		}
		
	// add links if $paginate is set to true
		if ($paginate) {
			echo '<div class="col-2-3 center pagination">';
			// forward link
			if ($ostart + 1 < $numPages) {
				$next = $ostart + 2;
				echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?perpage={$numPosts}&page={$next}\">&larr; Older</a>";
			} else {
				echo "None Older";
			}
			echo " || ";
			// back link
			if ($ostart > 0) {
				echo "<a href=\"{$_SERVER['SCRIPT_NAME']}?perpage={$numPosts}&page={$ostart}\">Newer &rarr;</a>";
			} else {
				echo "None Newer";
			}
			
			echo '</div>';
		}

	} else { $output = "No posts found."; } 
	
}

function writeSingle( $ext, $comments ) {
	$error = false;
	if ( !strstr($_SERVER['REQUEST_URI'],'?post=')){
		$error = true;
	}
	if ( isset( $_GET['post'] ) ) {
		$file = $_GET['post'] . ".{$ext}";
		if ( !file_exists( getcwd().'/'.$file  ) ){
		$error = true;
		}
		if ( $error == false ) {
			$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT ); 
			$link = $_GET['post'];
			$comment_link = "#disqus_thread";
			postContent( $lines, $link, $comment_link );
			if ( $comments ) { ?>
					<div id="disqus_thread" class="col-2-3 center"></div>
			<script type="text/javascript">
				/* * * CONFIGURATION VARIABLES * * */
				var disqus_shortname = 'bekahsealey';
	
				/* * * DON'T EDIT BELOW THIS LINE * * */
				(function() {
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

		<?php }
		}
	}
	if ( $error ) {
		header('HTTP/1.0 404 Not Found');
		$lines = file( '../_error/error.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
		postContent( $lines );
		return;		
	}
}

function writePage( $file ) {
	$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
	postContent( $lines );
}

function postContent( $lines, $link, $comment_link ) {
	$n = 0;
	$output = '';
	$output .= '<section class="col-2-3 center">';
	$output .= "<header><h2 class='date'>{$lines[$n]}</h2></header>";
	$n++;
	$output .= '<article>';
	$output .= '<header><h3>';
	$link ? $output .= "<a href=\"single.php?post={$link}\">{$lines[$n]}</a>" : $output .= $lines[$n];
	$output .= '</h3><header>';
	$n++;
	$output .= "<ul class=\"meta\">";
	if ( strpos ( $lines[$n], '[' ) !== false ) { $cats = str_replace( array( '[', ']' ), '', $lines[$n] ); $cats = explode( ',', $cats); $output .= "<li><small>Categorized: "; foreach( $cats as $cat ) { $output .= "<a href=\"?cat={$cat}\">{$cat}</a>"; } $output .= "</small></li>"; $n++; }
	if ( strpos ( $lines[$n], '{' ) !== false ) { $tags = str_replace( array( '{', '}' ), '', $lines[$n] ); $tags = explode( ',', $tags); $output .= "<li><small>Tagged: "; foreach( $tags as $tag ) { $output .= "<a href=\"?tag={$tag}\">{$tag}</a>"; } $output .= "</small></li>"; $n++; }
	if ( $comment_link ) { $output .= "<li><small><a href=\"{$comment_link}\">Comments</a></small></li>"; }
	$output .= '</ul>';
	while ( $n <= count( $lines ) ) {
		$output .= '<p>';
		$output .= $lines[$n];
		$output .= '</p>';
		$n++;
	}
	$output .= '</article>';
	$output .= '</section>'; 
	echo $output;
}
?>