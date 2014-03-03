<?php /* $Id */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

//get the existing meetme extensions
function pickupgroup_list($getAll=false) {
	$results = sql("SELECT * FROM pickupgroup","getAll",DB_FETCHMODE_ASSOC);
	if(is_array($results)){
		foreach($results as $result){
                    $allowed[] = $result;
		}
	}
	if (isset($allowed)) {
		return $allowed;
	} else { 
		return null;
	}
}

function pickupgroup_get($id){
	$results = sql("SELECT * FROM pickupgroup WHERE pickupgroup_id = '$id'","getRow",DB_FETCHMODE_ASSOC);
	return $results;
}

function pickupgroup_del($id){
	$results = sql("DELETE FROM pickupgroup WHERE pickupgroup_id = '$id'","query");
}

function pickupgroup_add($post){
	extract($post);
	if(empty($description)) $description = _('Unnamed');
	$results = sql("INSERT INTO pickupgroup (description,extensions) values (\"$description\",\"$extensions\")");
}

function pickupgroup_edit($id,$post){
	extract($post);
	if(empty($description)) $description = _('Unnamed');
	$results = sql("UPDATE pickupgroup SET description = \"$description\", extensions = \"$extensions\" WHERE pickupgroup_id = \"$id\"");
}

function pickupgroup_extension_update(){
    $pickupgroup = pickupgroup_list();
    //print_r($pickupgroup[0]);
    
    $extensions_list = sql("SELECT * FROM `sip` WHERE keyword = 'pickupgroup'","getAll",DB_FETCHMODE_ASSOC);
	if(is_array($extensions_list)){
            foreach($extensions_list as $result){
                $list = array(); 
                foreach($pickupgroup as $group){
                    $id = $result['id'];
                    if (array_find($id, $group) == TRUE){
                        $list[] = $group[pickupgroup_id]; 
                    }
                }
                $app_pickup = "";
                foreach($list as $item){
                  $app_pickup .= $item.",";
                }
                $app_pickup = rtrim($app_pickup,',');
                if(!empty($app_pickup)){
                    $update_pickup = sql("UPDATE sip SET data = \"$app_pickup\" WHERE id = \"$id\" AND keyword = 'pickupgroup'");
                    $update_pickup = sql("UPDATE sip SET data = \"$app_pickup\" WHERE id = \"$id\" AND keyword = 'callgroup'");
                }
            }
	}
}

function array_find($needle, $haystack)
{
   foreach ($haystack as $item)
   {
      if (strpos($item, $needle) !== FALSE)
      {
         return TRUE;
         break;
      }
   }
}

?>