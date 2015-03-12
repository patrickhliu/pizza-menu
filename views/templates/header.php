<?php session_start(); // track cart ?> 
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <title><?php echo htmlspecialchars($title) ?></title>
    <link href='http://fonts.googleapis.com/css?family=Gentium+Book+Basic' rel='stylesheet' type='text/css'>
 	  <link rel="stylesheet" type="text/css" href="../css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
  </head>
  <body>
  	<header>
      <h1><a href="index.php">CS75 Project 0 - Pizza Menu</a></h1>
    </header> 
  	<div class="wrapper">
 		<nav>
 			<ul class="menu cf">
 				<li><a href="?page=pizzas.php">Pizzas & Calzones</a></li>
		 		<li><a href="?page=dinners.php">Dinners, Sides & Salads</a></li>
		 		<li><a href="?page=pastas.php">Pasta</a></li>
		 		<li><a href="?page=sandwhiches.php">Sandwhiches</a></li>
		 		<li><a href="?page=cart.php">Cart</a></li>
 			</ul>
 		</nav>
  	
  	
    