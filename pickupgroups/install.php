<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

global $db;
global $amp_conf;

$autoincrement = (($amp_conf["AMPDBENGINE"] == "sqlite") || ($amp_conf["AMPDBENGINE"] == "sqlite3")) ? "AUTOINCREMENT":"AUTO_INCREMENT";

$sql[] = "CREATE TABLE IF NOT EXISTS pickupgroup ( 
	pickupgroup_id INTEGER NOT NULL PRIMARY KEY $autoincrement, 
	extensions LONGTEXT, 
	description VARCHAR( 50 )
)";

foreach($sql as $q){
	$check = $db->query($q);
	if(DB::IsError($check)) {
    die_freepbx("Can not create pickup group tables\n".$check->getDebugInfo());
	}
}