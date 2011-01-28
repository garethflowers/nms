<?php
require_once('../../lib/base.php');

$query = 'reindex database ' . $config['database'] . ';';
Db::ExecuteQuery($query);

$query = 'vacuum full verbose analyze;';
Db::ExecuteQuery($query);

echo 'Complete!';
?>