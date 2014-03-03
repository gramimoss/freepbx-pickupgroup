<?php /* $Id */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';

//the item we are currently displaying
isset($_REQUEST['itemid'])?$itemid=$_REQUEST['itemid']:$itemid='';

$dispnum = "pickupgroups"; //used for switch on config.php

//if submitting form, update database
if(isset($_POST['action'])) {
	switch ($action) {
		case "add":
			pickupgroup_add($_POST);
                        pickupgroup_extension_update();
			needreload();
			redirect_standard();
		break;
		case "delete":
			pickupgroup_del($itemid);
                        pickupgroup_extension_update();
			needreload();
			redirect_standard();
		break;
		case "edit":
			pickupgroup_edit($itemid,$_POST);
                        pickupgroup_extension_update();
			needreload();
			redirect_standard('itemid');
		break;
	}
}

//get list of time conditions
$pickupgroupss = pickupgroup_list();
?>

<!-- right side menu -->
<div class="rnav"><ul>
    <li><a id="<?php echo ($itemid=='' ? 'current':'') ?>" href="config.php?display=<?php echo urlencode($dispnum)?>"><?php echo _("Add Pickup Group")?></a></li>
<?php
if (isset($pickupgroupss)) {
	foreach ($pickupgroupss as $pickupgroups) {
		echo "<li><a id=\"".($itemid==$pickupgroups['pickuproup_id'] ? 'current':'')."\" href=\"config.php?display=".urlencode($dispnum)."&itemid=".urlencode($pickupgroups['pickupgroup_id'])."\">{$pickupgroups['description']}</a></li>";
	}
}
?>
</ul></div>
<?php

if ($action == 'delete') {
	echo '<br><h3>'._("Pickup Group ").' '.$itemid.' '._("deleted").'!</h3>';
} else {
	if ($itemid){ 
		//get details for this time condition
		$thisItem = pickupgroup_get($itemid);
	}

	$delURL = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&action=delete';
	$delButton = "
			<form name=delete action=\"{$_SERVER['PHP_SELF']}\" method=POST>
				<input type=\"hidden\" name=\"display\" value=\"{$dispnum}\">
				<input type=\"hidden\" name=\"itemid\" value=\"{$itemid}\">
				<input type=\"hidden\" name=\"action\" value=\"delete\">
				<input type=submit value=\""._("Delete Pickup Group")."\">
			</form>";
	
?>
	<h2><?php echo ($itemid ? _("Pickup Group:")." ". $itemid : _("Add Pickup Group")); ?></h2>

<?php		if ($itemid){  echo $delButton; 	} ?>


<form autocomplete="off" name="edit" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return edit_onsubmit();">
	<input type="hidden" name="display" value="<?php echo $dispnum?>">
	<input type="hidden" name="action" value="<?php echo ($itemid ? 'edit' : 'add') ?>">
	
	<table>
	<tr><td colspan="2"><h5><?php echo ($itemid ? _("Edit Pickup Group") : _("New Pickup Group")) ?><hr></h5></td></tr>

<?php		if ($itemid){ ?>
		<input type="hidden" name="account" value="<?php echo $itemid; ?>">
<?php		}?>

	<tr>
		<td><?php echo _("Pickup Group Description:")?></td>
		<td><input type="text" size=23 name="description" value="<?php echo (isset($thisItem['description']) ? $thisItem['description'] : ''); ?>" tabindex="<?php echo ++$tabindex;?>"></td>
	</tr>
	<tr>
		<td><a href="#" class="info"><?php echo _("Pickup Group List:")?><span><?php echo _("Enter a list of one or more Extensions.  One Extension per line.")?></span></a></td>
		<td>
			<textarea rows=15 cols=20 name="extensions" tabindex="<?php echo ++$tabindex;?>"><?php echo (isset($thisItem['extensions']) ? $thisItem['extensions'] : ''); ?></textarea>
		</td>
	</tr>

	<tr>
		<td colspan="2"><br><h6><input name="submit" type="submit" value="<?php echo _("Submit Changes")?>" tabindex="<?php echo ++$tabindex;?>"></h6></td>		
	</tr>
	</table>
<script language="javascript">
<!--

var theForm = document.edit;
theForm.description.focus();

function edit_onsubmit() {
	
	defaultEmptyOK = false;
	if (!isAlphanumeric(theForm.description.value))
		return warnInvalid(theForm.description, "<?php _("Please enter a valid Description") ?>");
		
	return true;
}

-->
</script>

	</form>

<?php		
} //end if action == delete
?>
    