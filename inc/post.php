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
			if ( $comments ) { $comment_link = "single.php?post={$link}#disqus_thread"; }
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
			if ( $comments ) { $comment_link = "#disqus_thread"; }
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
	$elems = array('address','article','aside','blockquote','br','button','canvas','caption','col','colgroup','dd','div','dl','dt','embed','fieldset','figcaption','figure','footer','form','h1','h2','h3','h4','h5','h6','header','hr','li','main','nav','noscript','map','object','ol','output','p','pre','progress','section','table','tbody','textarea','tfoot','th','thead','tr','ul','video');
			
	$n = 0;
	$output = '';
	$output .= '<section class="col-2-3 center">';
	$output .= "<header><h2 class='date'>{$lines[$n]}</h2></header>";
	$n++;
	$output .= '<article>';
	$output .= '<header><h3>';
	$link ? $output .= "<a href=\"single.php?post={$link}\">{$lines[$n]}</a>" : $output .= $lines[$n];
	$output .= '</h3></header>';
	$n++;
	$output .= "<ul class=\"meta\">";
	if ( $lines[$n][0] == '[' ) { $cats = str_replace( array( '[', ']' ), '', $lines[$n] ); $cats = explode( ',', $cats); $output .= "<li class=\"term\"><small>Categorized: "; foreach( $cats as $cat ) { $output .= "<a href=\"?cat={$cat}\">{$cat}</a>"; } $output .= "</small></li>"; $n++; }
	if ( $lines[$n][0] == '{' ) { $tags = str_replace( array( '{', '}' ), '', $lines[$n] ); $tags = explode( ',', $tags); $output .= "<li class=\"term\"><small>Tagged: "; foreach( $tags as $tag ) { $output .= "<a href=\"?tag={$tag}\">{$tag}</a>"; } $output .= "</small></li>"; $n++; }
	if ( $comment_link ) { $output .= "<li><small><a href=\"{$comment_link}\">Comments</a></small></li>"; }
	$output .= '</ul>';
	while ( $n <= count( $lines ) ) {
		$wrap = true;
		// if the line begins with < 
		if ( $lines[$n][0] == '<' ) {
			foreach( $elems as $str ) {
			// check if it is a block level element and print without wrapping
			$pos = strpos($lines[$n], $str );
				if( $pos === 1 ) {
					$wrap = false; 
					break;
				} 
			} 
		}
			
		$wrap ? $output .= "<p>{$lines[$n]}</p>" : $output .= $lines[$n];
		$n++;
	}
	$output .= '</article>';
	$output .= '</section>'; 
	echo $output;
}
 
function writeRss( $title, $link, $description, $copyright, $dir, $ext, $language = 'en-us' )
  {
  	// check dir for existence and readability
	$path = '../' . $dir . '/';
	$exists = file_exists( $path );
	$link = urlencode( $link );
	$success = '';
	$rssFile = '../feed.rss';
	
	if (!$exists) {
		die( "Directory '{$path}' does not exist!" );
	} else {
		$rss = new XMLWriter();
		$rss->openMemory();
		$rss->startDocument('1.0', 'UTF-8');
		
		$rss->setIndent(4);
		// declare it as an rss document
		$rss->startElement('rss');
		$rss->writeAttribute('version', '2.0');
		$rss->writeAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
 
		$rss->startElement( 'channel' );
		 
		$rss->writeElement( 'title', $title );
		$rss->writeElement( 'description', $description );
		$rss->writeElement( 'language', $language );
		$rss->writeElement('link',  urldecode( $link ) );
		$rss->writeElement('pubDate', date("D, d M Y H:i:s e"));
		$rss->writeElement( 'copyright', $copyright );
		
		// collect post files
		
		$results = buildFileList($path, $ext);
		if ( !$results ) {
			die( 'Files not found.' );
		} else { 
			$results = str_replace( ".{$ext}", '', $results );
			// sort array function included with buildlist
			usort($results, 'blogsort');
			$sliced_array = array_slice($results, 0, 10);
		
			for ( $i=0; $i <= count($sliced_array)-1; $i++ ) {
				$link = $path . $results[$i] . '.txt';
				//echo $link; exit;
				$error = '';
				if( !is_readable( $link ) ) { $error .= "{$link}: file is unreadable or does not exist.\n";
				} else {
					$lines = file( $link, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT );
					$itemlink = str_replace( array( '../', '.txt' ), '', $link );
					$itemlink = str_replace( '/', '/single.php?post=', $ilink );
					$itemlink = urlencode('http://bekahsealey.com/' . $ilink);
					$r = 0;
					$itempubDate = strtotime( $lines[$r] );
					$itempubDate = date("D, d M Y H:i:s e", $itempubDate );
					$r++;
					$itemtitle = $lines[$r];
					$r++; 
					if ( $lines[$r][0] == '[' ){ $r++; }
					if ( $lines[$r][0] == '{' ){ $r++; }
					$itemdescription = $lines[$r];
				
					$rss->startElement("item");
					$rss->writeElement('title', $itemtitle );
					$rss->writeElement('link', 'http://bekahsealey.com/blog/' );
					$rss->writeElement('description', $itemdescription );
					$rss->writeElement('guid', $itemlink );

					$rss->writeElement('pubDate', $itempubDate );

					// End Item
					$rss->endElement();

				}
			}  
		}	
		
		// End channel
		$rss->endElement();

		// End rss
		$rss->endElement();

		$rss->endDocument();
		$success = file_put_contents( $rssFile, $rss->outputMemory() );
		
		$rss->flush(); 
		if ( $success != false || $success != '' ) { echo "RSS written!"; } else { echo "There was a problem."; }
		if ( $error != false || $error != '' ) { echo $error; }
		}

  }
 
  
?>