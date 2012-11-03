<?php
/**
 * generator obrazku captcha
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage images
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCaptcha();

define('BASE_DIR',__DIR__."/..");

session_name('adis_session');
session_set_cookie_params(0, "/", "", false, true);
session_start();

$img = new Securimage();
$img->ttf_file = BASE_DIR.'/img/Ubuntu-B.ttf';
$img->image_width = 200;
$img->image_height = 60;
$img->perturbation = 0.7;
$img->code_length = rand(5,6);
//$img->background_directory = 'BASE_DIR./app/lib/external/captcha/backgrounds';
//$img->image_bg_color = new Securimage_Color("#ffffff");
//$img->use_transparent_text = true;
//$img->use_multi_text = true;
//$img->text_transparency_percentage = 75; // 100 = completely transparent
$img->num_lines = 15;
$img->noise_level = 3;
//$img->image_signature = 'AdIS';
//$img->signature_font = BASE_DIR.'/img/Ubuntu-B.ttf';
//$img->signature_color = new Securimage_Color('#000000');
$img->show(BASE_DIR."/app/lib/external/captcha/backgrounds/bg4.jpg");
?>