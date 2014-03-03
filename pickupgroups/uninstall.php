<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

global $db;
global $amp_conf;

$pickupgroups = pickupgroup_list();
foreach ($pickupgroups as $item) {
	echo "removing ".$item['description']."..";
	pickupgroup_del($item['pickupgroup_id']);
	echo "done<br>\n";
}

echo "dropping table pickupgroup..";
sql('DROP TABLE IF EXISTS `pickupgroup`');
echo "done<br>\n";

?>