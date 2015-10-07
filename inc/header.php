<?php 
	ini_set("date.timezone", "America/Chicago"); 
	$page = basename(getcwd());
	require_once( 'post.php' );
?>
<!doctype html>
<html class="no-js">
<head>
<meta charset="UTF-8">
<title><?php if ($page !== '') { echo ucwords( $page ), ' | '; } ?> Bekah Sealey | Portfolio</title>
<link href='https://fonts.googleapis.com/css?family=Josefin+Slab:400,300,400italic,300italic,700%7cSpecial+Elite%7cOverlock:900,900italic' rel='stylesheet' type='text/css'>
<link href="/css/screen.css" rel="stylesheet" media="screen">
<link href="/css/print.css" rel="stylesheet" media="print">
<link rel="alternate" type="application/rss+xml" href="http://bekahsealey.com/feed.rss" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script
<link rel="icon" type="image/x-icon" href="//bekahsealey.com/favicon.ico"/>
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
 </script>
</head>

<body <?php if ($page != '') { ?>class="<?php echo $page; ?>"<?php } ?>>
<div class="wrapper">
<header>
	<div class="grid">
		<div class="col-1-2">
		<h1 class="logo"><a href="/"><span class="icon icon-logo">&nbsp;</span>Bekah Sealey</a></h1>
		</div>
		<?php if ($page === 'resume') { ?>
		<div class="col-1-2">
			<h3>511 Frank Avenue, Algoma, WI  54201</h3>
			<h4>(920) 487-2031 – <a href="mailto:bekah@bekahsealey.com">bekah@bekahsealey.com</a></h4>
		</div>
		<?php } else { ?>
		<div class="col-1-2 reverse">
			<?php include('main-nav.php'); ?>
			<?php include('social-nav.php'); ?>
		</div>
		<?php } ?>
	</div>
</header>
<main class="grid">