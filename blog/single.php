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
	
			$output = '';
			$output .= '<section class="col-2-3 center">';
			$output .= '<header><h2 class="date">';
			$output .= $lines[0];
			$output .= '</h2></header>';
			$output .= '<article>';
			$output .= "<header><h3><a href=\"single.php?post={$link}\">";
			$output .= $lines[1];
			$output .= '</a></h3><header>';
			$output .= "<small><a href=\"single.php?post={$link}#disqus_thread\">Comments</a></small>";
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
		<div id="disqus_thread"></div>
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
<?php include('../inc/footer.php'); ?>