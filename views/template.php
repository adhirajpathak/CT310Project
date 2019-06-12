<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?php echo Asset::css('styles.css'); ?>
</head>
<body>
	<header>
		<nav>
			<ul class="nav navbar-nav navbar-right">
     				<li class="active"><a href='<?php echo Uri::base(); ?>'>Home</a></li>
     				<li class="active"><a href='<?php echo Uri::create("index.php/home/about"); ?>'>About Us</a></li>
     				<li class="active"><a href='<?php echo Uri::create("index.php/home/whatif"); ?>'>What If</a></li>
     				<li class="active"><a href='<?php echo Uri::create("index.php/home/request"); ?>'>Send a Demo Request</a></li>
   			</ul>
		</nav>
	</header>
	<?php 
		echo $content
	?>
	<footer>
		<p>
        &copy; Copyright 2019 | Part of Colorado State University <a href="https://cs.colostate.edu/~ct310" target="blank">CT310</a> project
		</p>
        <p>Designed by T04: The National Analysis Hospital Performance Assoication (NAHPA)</p>
  </footer><!-- #footer -->
</body>
</html>
