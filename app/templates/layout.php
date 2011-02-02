<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title><?php echo Context::getInstance()->getResponse()->getTitle(); ?></title>
        <meta name="generator" content="Netbeans IDE, www.netbeans.org">
        <meta name="description" content="IS pre správu reklamy">
        <meta name="keywords" content="internetova reklama, online reklama, adis, banner, ppc, is">
        <meta name="copyright" content="Copyright (c) Jan Nescivera">
        <meta name="author" content="Designed by Yohny, jan.nescivera@student.tuke.sk">
        <meta name="content-language" content="sk">
        <link rel="shortcut icon" href="/img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="/css/style_basic.css">
        <link rel="stylesheet" type="text/css" href="/css/style_green.css">
        <link rel="stylesheet" type="text/css" href="/css/style_orange.css">
        <link rel="stylesheet" type="text/css" href="/css/style_blue.css">
        <script language="javascript" type="text/javascript" src="/js/head_script.js"></script>
        <?php foreach (Context::getInstance()->getResponse()->getResources() as $resource) echo $resource . "\n"; ?>
    </head>
    <body onload="setContainerHeight();">
        <div id="container">
            <div class="top_left corner"></div>
            <div class="top_right corner"></div>
            <div class="bottom_left corner"></div>
            <div class="bottom_right corner"></div>

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
                        <legend>Ad-IS</legend>
                        <a href="/about">O systéme</a>
                        <a href="/faq">FAQ</a>
                    </fieldset>

                    <?php require_once TEMPLATES_DIR.'/partials/userSection.php'; ?>
                </div>

                <div id="nove">
                    <div class="top_left corner"></div>
                    <div class="top_right corner"></div>
                    <div class="bottom_left corner"></div>
                    <div class="bottom_right corner"></div>
                    <h3>info</h3>
                    <p class="g">common message</p>
                    <p class="r">important message</p>
                    <p>
                        <a href="http://validator.w3.org/check?uri=referer">
                            <img src="/img/valid-html401.png" alt="val_html">
                        </a>
                    </p>
                    <p>
                        <a href="http://jigsaw.w3.org/css-validator/check/referer">
                            <img src="/img/vcss.gif" alt="val_css">
                        </a>
                    </p>
                    <p>
                        schéma:
                        <img id="img1" src="/img/sch1.png" onClick="set_scheme(1)" alt="s1">
                        <img id="img2" src="/img/sch2.png" onClick="set_scheme(2)" alt="s2">
                        <img id="img3" src="/img/sch3.png" onClick="set_scheme(3)" alt="s3">
                    </p>
                    <script language="javascript" type="text/javascript">set_scheme(schema);</script>
                </div>
                <a href="mailto:jan.nescivera@student.tuke.sk" id="ja" title="admin&amp;webmaster">&lt;Yohny&gt;</a>
            </div><!-- end div#left -->

            <div id="main">
                <div class="top_left corner"></div>
                <div class="top_right corner"></div>
                <div class="bottom_left corner"></div>
                <div class="bottom_right corner"></div>
                <h3><?php echo Context::getInstance()->getResponse()->getHeading(); ?></h3>
                <?php if($flash=Context::getInstance()->getResponse()->getFlash()): ?>
                <div class="flash">
                    <div class="top_left corner"></div>
                    <div class="top_right corner"></div>
                    <div class="bottom_left corner"></div>
                    <div class="bottom_right corner"></div>
                    <?php echo $flash; ?>
                </div>
                <?php endif; ?>
                <?php if(Context::getInstance()->getResponse()->error){ ?>
                <div class="error">
                    <div class="top_left corner"></div>
                    <div class="top_right corner"></div>
                    <div class="bottom_left corner"></div>
                    <div class="bottom_right corner"></div>
                    <?php echo Context::getInstance()->getResponse(); ?>
                </div>
                <?php } else echo Context::getInstance()->getResponse(); ?>
                <hr>
            </div>
        </div><!-- end div#container -->
    </body>
</html>