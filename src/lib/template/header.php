<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Nursery Management System (NMS)." />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nursery Management System</title>
    <link href="/lib/styles/base.css" rel="stylesheet" />
    <link href="/lib/styles/global.css" rel="stylesheet" />
    <link href="/lib/styles/calendar.css" rel="stylesheet" />
    <link href="/favicon.ico" rel="shortcut icon" />
    <script src="/lib/resources/mootools-1.2.2.js"></script>
    <script src="/lib/resources/vlacal-2.1.js"></script>
    <script src="/lib/resources/sortingtable.js"></script>
    <script src="/lib/clientscripts/validation.js"></script>
</head>

<body>

    <ul id="header-menu">
        <?php if (LoginLevel(1)) { ?>
            <li><a href="/" title="Back to NMS Today">Home</a></li>
            <li class="menu"><a href="/child" title="View Child details">Children</a></li>
            <li class="menu"><a href="/staff" title="View Staff details">Staff</a></li>
            <li class="menu"><a href="/reports" title="View Reports">Reports</a></li>
        <?php
        }
        if (LoginLevel(2)) {
        ?>
            <li class="menu"><a href="/settings" title="Adjust Settings">Settings</a></li>
        <?php
        }
        if (LoginCheck()) {
        ?>
            <li class="menu"><a href="/login.php?o=t" title="Log Out">Log Out</a></li>
        <?php } ?>
    </ul>

    <div id="header">
        <?php if (LoginLevel(0)) { ?>
            <a href="/">Nursery Management System</a>
        <?php } else { ?>
            Nursery Management System
        <?php } ?>
    </div>

    <div id="body">

        <?php if (false) { ?>
    </div>
</body>

</html>
<?php }
?>
