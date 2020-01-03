<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>     <html class="no-js"> <![endif]-->
<html>
<head>
<?php echo $this->Html->charset('UTF-8'); ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0,initial-scale=1.0,user-scalable=yes" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,700">

  <?php 
        echo $this->Html->css('style'); 
        echo $this->Html->css('media');
        echo $this->Html->script('jquery-latest');
        echo $this->Html->script('custom');
    ?>
    
<!--[if lt IE 9]>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>Ritzy</title>
</head>

<body class="homescreen">
<section id="main" class="home-content">
	<section class="main-container">
        <?php echo $this->fetch('content'); ?>    
    </section>
    <section class="push"></section>
</section>
<footer>
 <?php echo $this->element('footer'); ?>
 </footer>
</body>
</html>
