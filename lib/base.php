<?php
/*
 * Base.php
 */


// ensure error reporting is on so we can see any problems
error_reporting(E_ALL);


// for the system to use UTC for dates
date_default_timezone_set('UTC');


// for the timeout limit
set_time_limit(60);


// send to browser text/html
header('vary: accept');
header('Content-Type: text/html; charset=utf-8');


// define global constants
define('PHP_SELF', strtolower($_SERVER['PHP_SELF']));
define('PHP_REQUEST', strtolower(str_replace('index.php', '', $_SERVER['REQUEST_URI'])));
define('DOCUMENT_ROOT', strtolower($_SERVER['DOCUMENT_ROOT']));


// include functions
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/format.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/forms.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/javascript.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/login.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/sessions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/navigation.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/validation.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/common/variables.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/child.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/child_session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/child_contact.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/staff.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/staff_contact.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/dates.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/rooms.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/religion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/model/nationality.php');


// ensure sessions are working
session_start();


// connect to the database
$_SESSION['db'] = new Db($config['host'], $config['port'], $config['database'], $config['username'], $config['password']);


// id checker
function CheckID($id) {
    if (isset($id) && !empty($id) && is_numeric($id)) {
        return intval($id);
    } else {
        header('location: /error.php?i=1');
        exit;
    }
}

if (!LoginLevel(1) && PHP_SELF!='/login.php') {
    header('location: /login.php');
}
?>