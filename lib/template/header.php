<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xml:lang="en-GB">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="EN-GB" />
        <meta name="Rating" content="General" />
        <meta name="Robots" content="index,follow" />
        <meta name="Title" content="NMS (Nursery Management System) by Gareth Flowers" />
        <meta name="keywords" content="nms, nursery, management, system, gareth, flowers, gareth flowers, gaz, projects" />
        <meta name="description" content="NMS (Nursery Management System) project minisite designed by Gareth Flowers." />
        <meta name="Abstract" content="NMS (Nursery Management System) project minisite designed by Gareth Flowers." />
        <meta name="Author" content="Gareth Flowers" />
        <meta name="Designer" content="Gareth Flowers" />
        <meta name="Copyright" content="&copy; Gareth Flowers <?php echo date('d-m-Y'); ?>" />
        <meta name="verify-v1" content="dpUp8hpWB2VEXcjRTQ8Glwm085I/YrqX8PnstQhyYKc=" />
        <title>NMS &ndash; Nursery Management System (by Gareth Flowers)</title>
        <link type="text/html" rev="made" href="mailto:gareth@garethflowers.com" title="Gareth Flowers" />
        <link type="text/html" rel="home" href="http://www.garethflowers.com/project" title="NMS Project" />
        <link type="text/css" href="/lib/styles/base.css" rel="stylesheet" />
        <link type="text/css" href="/lib/styles/global.css" rel="stylesheet" />
        <link type="text/css" href="/lib/styles/calendar.css" rel="stylesheet" />
        <link href="/favicon.ico" rel="shortcut icon" />
        <script type="text/javascript" src="/lib/resources/mootools-1.2.2.js"></script>
        <script type="text/javascript" src="/lib/resources/vlacal-2.1.js"></script>
        <script type="text/javascript" src="/lib/resources/sortingtable.js"></script>
        <script type="text/javascript" src="/lib/clientscripts/validation.js"></script>
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
