<?php
require_once('lib/base.php');

PageHeader();

if (!isset($_GET['id'])) { ?>

<h1>An Unknown Error Has Occurred On This Site</h1>
	
<p>&nbsp;</p>

<p>Some unknown problem has prevented you from accessing the page you were after. This
	problem has been logged with the administrator. Please try again at a later date.</p>

<?php } elseif($_GET['id']==404) { ?>

<h1>File Not Found</h1>

<?php } elseif ($_GET['id']==1) { ?>

<h1>Information Has Been Lost</h1>

<p>&nbsp;</p>

<p>For some unknown reason some information has been lost whilst navigating through the database.</p>

<p>Please try again from the starting screen to get back to where you were.</p>

<?php } ?>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h2>Still Having Problems?</h2>

<p>If you are still having problems then you will need to contact Gareth Flowers at the earliest convienience.</p>

<?php PageFooter(); ?>