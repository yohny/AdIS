<!DOCTYPE html>
<html lang="sk">
    <head>
        <meta charset="utf-8" />
        <title><?= Context::getInstance()->getResponse()->getTitle(); ?></title>
        <meta name="generator" content="Netbeans IDE, www.netbeans.org" />
        <meta name="description" content="AdIS - reklamný systém" />
        <meta name="keywords" content="internetová reklama, online reklama, reklama, ad-is, banner, ppc, is" />
        <meta name="application-name" content="AdIS" />
        <meta name="author" content="Yohny - jan.nescivera@gmail.com" />
        <link rel="shortcut icon" href="/img/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="/css/style_basic.css" />
        <link rel="stylesheet" type="text/css" href="/css/style_green.css" />
        <link rel="stylesheet" type="text/css" href="/css/style_orange.css" />
        <link rel="stylesheet" type="text/css" href="/css/style_blue.css" />
        <script type="text/javascript" src="/js/script.js"></script>
        <?php foreach (Context::getInstance()->getResponse()->getResources() as $resource) echo $resource . "\n"; ?>
    </head>
    <body>
        <div id="top">
            <h1 class="above">Ad-IS</h1>
            <h1 class="below">Ad-IS</h1>
            <h2>Reklamný systém</h2>
        </div>

        <div id="left">
            <div id="menu">
                <h3>menu</h3>

                <fieldset>
                    <legend>Ad-IS</legend>
                    <a href="/about">O systéme</a>
                    <a href="/faq">FAQ</a>
					<a href="/registracia">Registrácia</a>
                </fieldset>

                <?php require_once TEMPLATES_DIR.'/partials/userSection.php'; ?>
            </div>

            <div id="nove">
                <h3>info</h3>
                <p class="g">bežný oznam</p>
                <p class="r">dôležitý oznam</p>
                <p>
                    <a href="http://validator.w3.org/check?uri=referer">
                        <img src="/img/valid-xhtml10.png" alt="val_xhtml" />
                    </a>
                </p>
                <p>
                    <a href="http://jigsaw.w3.org/css-validator/check/referer">
                        <img src="/img/vcss.gif" alt="val_css" />
                    </a>
                </p>
                <p>
                    schéma:
                    <img id="img1" src="/img/sch1.png" onclick="set_scheme(1)" alt="s1" />
                    <img id="img2" src="/img/sch2.png" onclick="set_scheme(2)" alt="s2" />
                    <img id="img3" src="/img/sch3.png" onclick="set_scheme(3)" alt="s3" />
                </p>
            </div>
            <a href="mailto:jan.nescivera@gmail.com" id="ja" title="admin&amp;webmaster">&lt;Yohny&gt; &copy; <?php echo date('Y'); ?></a>
        </div><!-- end div#left --><div id="main">
            <h3><?= Context::getInstance()->getResponse()->getHeading(); ?></h3>
            <?php if($flash=Context::getInstance()->getResponse()->getFlash()): ?>
            <div class="flash">
                <?= $flash; ?>
            </div>
            <?php endif; ?>
            <?php if(Context::getInstance()->getResponse()->error){ ?>
            <div class="error">
                <?= Context::getInstance()->getResponse(); ?>
            </div>
            <?php } else echo Context::getInstance()->getResponse(); ?>
            <hr/>
        </div><!-- end div#main -->
    </body>
</html>