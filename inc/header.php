<?php 
	ini_set("date.timezone", "America/Chicago"); 
	$page = str_replace( '/', '', $_SERVER['REQUEST_URI']);
	require_once( 'post.php' );
?>
<!doctype html>
<html class="no-js">
<head>
<meta charset="UTF-8">
<title>Bekah Sealey | Portfolio</title>
<link href="/css/screen.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="/css/print.css" media="print">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>

<body <?php if ($page != '') { ?>class="<?php echo $page; ?>"<?php } ?>>
<div class="wrapper">
<header>
	<div class="grid">
		<?php if ($page === 'resume') { ?>
		<h1 class="hero col-1-2"><a href="/">Rebekah Sealey</a></h1>
		<div class="col-1-2">
			<h3>511 Frank Avenue, Algoma, WI  54201</h3>
			<h4>(920) 487-2031 – <a href="mailto:rebekah@nmomedia.com">rebekah@nmomedia.com</a></h4>
		</div>
		<?php } else { ?>
		<a href="/"><div class="hero col-1-3"><img src="/images/headshot.jpg"><h1>Bekah Sealey | Portfolio</h1></div></a>
		<div class="col-2-3 reverse">
			<?php include('main-nav.php'); ?>
			<?php include('social-nav.php'); ?>
		</div>
		<?php } ?>
	</div>
</header>
<main class="grid">