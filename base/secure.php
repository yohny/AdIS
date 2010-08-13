<?php
if(!isset($_SESSION['user']) || !isset($_SESSION['group']))
{
?>
<h4>Pre prístup musíte byť prihlásený</h4>
<hr>
</div>

</div>
</body>
</html>
<?php
exit();
}
?>