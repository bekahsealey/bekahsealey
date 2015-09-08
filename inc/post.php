<?php 
require_once( 'buildlist.php' );

function writePosts($ext, $postsPerPage, $paginate, $comments) {
	
	$dir = getcwd();
	
	// collect post files
	$posts = buildFileList($dir, $ext);
	
	if ( $posts ) { 
    
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
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2 class="date">';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= "<header><h3><a href=\"single.php?post={$link}\">";
			$output .= $lines[1];
			if ( $comments ) {
				$output .= '</a></h3><header>';
				$output .= "<small><a href=\"single.php?post={$link}#disqus_thread\">Comments</a></small>";
			}
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
		}
		
	// add links if $paginate is set to true
		if ($paginate) {
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
		}

	} else { $output = "No posts found."; } 
	
}

function writeSingle( $ext, $comment ) {
if ( !strstr($_SERVER['REQUEST_URI'],'?post=')){
    header('HTTP/1.0 404 Not Found');
    $lines = file_get_contents ( '../_error/error.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
    echo $lines;
    exit();
}
if ( isset( $_GET['post'] ) ) {
	$file = $_GET['post'] . ".{$ext}";
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
			if ( $comment ) { $output .= "<small><a href=\"#disqus_thread\">Comments</a></small>"; }
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
		}
if ( $comment ) { ?>
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

function writePage( $file ) {
	$lines = file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2>';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= "<header><h3>";
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
}
?>