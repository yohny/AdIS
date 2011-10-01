<?php
mb_internal_encoding("utf-8");
require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCaptcha();

session_name('adis_session');
session_start();

if (!preg_match('/^http:\/\/'.quotemeta($_SERVER['HTTP_HOST']).'/', $_SERVER['HTTP_REFERER']))//len 'odtialto' povolene
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$img = new Securimage();
//Change some settings
$img->ttf_file = './Ubuntu-B.ttf';
$img->image_width = 200;
$img->image_height = 60;
$img->perturbation = 0.7;
$img->code_length = rand(5,6);
//$img->background_directory = '../classes/captcha/backgrounds';
//$img->image_bg_color = new Securimage_Color("#ffffff");
//$img->use_transparent_text = true;
//$img->use_multi_text = true;
//$img->text_transparency_percentage = 75; // 100 = completely transparent
$img->num_lines = 15;
//$img->image_signature = '';
//$img->text_color = new Securimage_Color("#000000");
//$img->line_color = new Securimage_Color("#cccccc");
$img->show('../classes/captcha/backgrounds/bg3.jpg'); 
?>
