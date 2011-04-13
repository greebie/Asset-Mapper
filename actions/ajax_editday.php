<?php
define(MODX_MANAGER_PATH , '../../../../manager/');//relative path for manager folder


include_once("../classes/assetmap.class.php");
require_once(MODX_MANAGER_PATH . '/includes/config.inc.php');
require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');


// Setup the MODx API
define('MODX_API_MODE', true);
// initiate a new document parser
include_once(MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
$modx = new DocumentParser;

$modx->db->connect(); // provide the MODx DBAPI

$id = $_POST['commid'];
$content = $_POST['content'];
$orgid = $_POST['orgid'];

print $id.$orgid.$content;
$edit = new assetMapDatabase();
$edit->hpl_addOpenHours($orgid, $id, $content);
echo htmlspecialchars($content);
?>

