<?php

require_once('../../lib/base.php');

$filename = $config['database'] . '-' . date('Ym') . '.backup';

exec('backup.bat ' . $config['password'] . ' ' . $config['host'] . ' ' . $config['port'] . ' ' . $config['username'] . ' ' . $filename . ' ' . $config['database']);

echo 'Complete! &nbsp; ';
echo '<a href="/lib/maintenance/' . $filename . '">Download BackUp</a>';
echo '<a href="/lib/maintenance/' . $filename . '"><img class="vam" src="/lib/images/download.png" alt="Download" /></a>';
?>