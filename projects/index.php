<?php include('../inc/header.php'); ?>
<?php 
	require_once( '../inc/buildlist.php' );
	$dir = getcwd();
	$ext = 'txt';
?>
	<section class="col-2-3 center">
		<header><h2>Projects</h2></header>
		<article>
			<header><h3>Things I've Created</h3></header>
			<p>I build websites, web applications, and have even tried my hand at Chrome Extensions.</p>
	</section>
<?php 
	$posts = buildFileList($dir, $ext);
	if ( $posts ) { foreach ( $posts as $post ) { echo file_get_contents( $post ); } } else { echo "No posts found."; }
?>
<?php include('../inc/footer.php'); ?>