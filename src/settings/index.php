<?php

require_once('../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

PageHeader();

TitleSettings(false, false);
?>

<p>&nbsp;</p>

<ul>
    <li><a href="/settings/dates">Dates</a></li>
    <li><a href="/settings/rooms">Rooms</a></li>
    <li><a href="/settings/religion">Religions</a></li>
    <li><a href="/settings/nationality">Nationalities</a></li>
</ul>

<p>&nbsp;</p>

<h2>Maintenance</h2>

<ul>
    <li><a href="#" id="opt">Optimise</a> &nbsp; <span id="optmsg"></span></li>
    <li><a href="#" id="back">Backup</a> &nbsp; <span id="backmsg"></span></li>
</ul>

<?php

$output = 'var req1 = new Request({url: "../lib/maintenance/optimise.php",';
$output .= 'onSuccess: function(txt){$("optmsg").innerHTML = "<strong>"+txt+"</strong>";},';
$output .= 'onFailure: function(){$("optmsg").innerHTML = "<strong>FAILED</strong>";}});';
$output .= '$("opt").addEvent(\'click\', function(e){$("optmsg").innerHTML = "<em>optimising the system, please wait...</em> <img src=\"/lib/images/load.gif\" alt=\"loading\" class=\"vam\" />";req1.send();});';
echo JsBlock($output);
$output = 'var req2 = new Request({url: "../lib/maintenance/backup.php",';
$output .= 'onSuccess: function(txt){$("backmsg").innerHTML = "<strong>"+txt+"</strong>";},';
$output .= 'onFailure: function(){$("backmsg").innerHTML = "<strong>FAILED</strong>";}});';
$output .= '$("back").addEvent(\'click\', function(e){$("backmsg").innerHTML = "<em>backuping up the system, please wait...</em> <img src=\"/lib/images/load.gif\" alt=\"loading\" class=\"vam\" />";req2.send();});';
echo JsBlock($output);

PageFooter();
?>
