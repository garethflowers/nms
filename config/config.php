<?php
/*****************
 * NMS v0.2
 */


/*
 * database connection
 */
$config['host']     = 'localhost'; // host name or ip
$config['port']     = '5432'; // host port
$config['database'] = 'nms'; // database name
$config['username'] = 'postgres'; // database username
$config['password'] = 'postgres'; // database password


/*
 * error pages
 */
$config['error_page'] = '/error.php';


/*
 * security level
 *
 * 0 = restricted
 * 1 = standard
 * 2 = none
 */
$config['security_level'] = 0;
?>