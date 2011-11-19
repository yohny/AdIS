<?php
/**
 * obsah pre "o systeme"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */

Context::getInstance()->getResponse()->setHeading('o systéme');
?>

<p>IS umožňuje zaregistrovaným používateľom spravovať internetovú reklamu a prístup k hodnoteniu efektivity ich reklamnej kampane. </p>
<h4>Do systému má prístup len obmedzená skupina osôb. Podmienkou pre získanie prístupu je registrácia. Používateľ sa môže zaregistrovať buď ako inzerent alebo ako zobrazovateľ. Na základe typu klienta sú mu následne sprístupnené rôzne možnosti systému.</h4>
Systém umožňuje pridávať a meniť reklamné bannery a zabepečuje ich distribúciu príslušným vebovým stránkam.<br>
Používatelia sú rodelení na dve skupiny.<br>
<span class="g">Inzerenti</span> sú tvorení tími, ktorí zadávajú reklamu a platia za jej publikovanienie  Po prihlásení do systému ako inzerent má používateľ možnosť uploadu svojho reklamného banneru na server a má k dispozícii informáciu o počte kliknití na jeho reklamu.<br>
<span class="g">Zobrazovatelia</span> plnia funkciu poskytovateľov reklamného priestoru a reklamné bannery zobrazujú na svojích webových stránkach. Každý používateľ z tejto kategórie má k dipozícii vygenerovaný HTML kód, ktorý po vložení do stránky zobrazovateľa zabezpečí získanie reklamného banneru a jeho zobrazenie na stránke.<br>

<p>Systém umožňje meranie efektivity reklamných kampaní používateľov. Využíva pritom metódu PPC (Pay Per Click). To znamená, že úspešnosť reklamy je hodnotená počtom kliknutí na danú reklamu.</p>
Navrhovaný IS je postavený na technológii PHP v spolupráci s databázovým systémom MySQL. Vyvíjaný bol na localhoste, na ktorom bol nainštalovaný LAMP stack v tejto konfigurácii:<br>
<ul>
<li>Apache 2.2.16</li>
<li>PHP 5.3.3</li>
<li>MySQL 5.1.49</li>
</ul>
