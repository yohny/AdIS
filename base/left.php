<?php
  require_once 'model/User.php'; //triedu musi poznat pred session
  session_start();   //na kazdej stranke, kt pouziva $_SESSION musi byt..
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php
$title = "AdIS";
if(isset($nadpis))
  $title .= " &gt; ".$nadpis;
echo "<title>".$title."</title>\n";  //pridanie nadpisu
?>
<meta name="generator" content="Netbeans IDE, www.netbeans.org">
<meta name="description" content="IS pre správu reklamy">
<meta name="keywords" content="internetova reklama, online reklama, adis, banner, ppc, is">
<meta name="copyright" content="Copyright (c) Jan Nescivera">
<meta name="author" content="Designed by Yohny, jan.nescivera@student.tuke.sk">
<meta name="content-language" content="sk">
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/style_basic.css">
<link rel="stylesheet" type="text/css" href="css/style_green.css">
<link rel="stylesheet" type="text/css" href="css/style_orange.css">
<link rel="stylesheet" type="text/css" href="css/style_blue.css">
<script language="javascript" type="text/javascript" src="js/head_script.js"></script>
<?php
if(isset($added))
  echo $added; //pridanie dalsich zdrojov
?>
</head>

<body onload="setContainerHeight();">
<div id="container">
<div class="top_left contcorner"></div>
<div class="top_right contcorner"></div>
<div class="bottom_left contcorner"></div>
<div class="bottom_right contcorner"></div>

<div id="top">
<div class="top_left corner"></div>
<div class="top_right corner"></div>
<div class="bottom_left corner"></div>
<div class="bottom_right corner"></div>
<h1 class="above">Ad-IS</h1>
<h1 class="below">Ad-IS</h1>
<h2>IS pre správu internetovej reklamy</h2>
</div>

<div id="left">
<div id="menu">
<div class="top_left corner"></div>
<div class="top_right corner"></div>
<div class="bottom_left corner"></div>
<div class="bottom_right corner"></div>
<h3>menu</h3>

<fieldset>
<legend>Úvod</legend>
<a href="index.php">O systéme</a>
</fieldset>

<fieldset>
<legend>Systém</legend>
<a href="registracia.php">Registrácia</a>
<a href="faq.php">FAQ</a>
</fieldset>

<h3>používateľ</h3>
<?php
require 'user.php'; 
?>
</div>

<div id="nove">
<div class="top_left corner"></div>
<div class="top_right corner"></div>
<div class="bottom_left corner"></div>
<div class="bottom_right corner"></div>
<h3>info</h3>
<p class="g">nornal message</p>
<p class="r">important message</p>
<p>
<a href="http://validator.w3.org/check?uri=referer">
<img src="img/valid-html401.png" alt="val_html">
</a>
</p>
<p>
<a href="http://jigsaw.w3.org/css-validator/check/referer">
<img src="img/vcss.gif" alt="val_css">
</a>
</p>
<p>
schéma:
&nbsp;&nbsp;<img id="img1" src="img/sch1.png" onClick="set_scheme(1)" alt="s1">
&nbsp;&nbsp;<img id="img2" src="img/sch2.png" onClick="set_scheme(2)" alt="s2">
&nbsp;&nbsp;<img id="img3" src="img/sch3.png" onClick="set_scheme(3)" alt="s3">
</p>
<script language="javascript" type="text/javascript">set_scheme(schema);</script>
</div>
    <a href="mailto:jan.nescivera@student.tuke.sk" id="ja" title="admin&amp;webmaster">&lt;Yohny&gt;</a>
</div>

<div id="main">
<div class="top_left corner"></div>
<div class="top_right corner"></div>
<div class="bottom_left corner"></div>
<div class="bottom_right corner"></div>
<h3><?php echo (isset($nadpis)?$nadpis:$title); ?></h3>
<?php if(isset($_SESSION['flash'])): ?>
<div class="flash">
    <?php echo $_SESSION['flash']; session_unregister('flash'); ?>
</div>
<?php endif ?>