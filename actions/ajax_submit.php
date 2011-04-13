<?php

define(MODX_MANAGER_PATH , '../../../../manager/');//relative path for manager folder
include_once("../classes/assetmap.class.php");
require_once(MODX_MANAGER_PATH . '/includes/config.inc.php');
require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');
$org;
$id;
$content;
$stuff;
$spelt;

// Setup the MODx API
define('MODX_API_MODE', true);
// initiate a new document parser
include_once(MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
$modx = new DocumentParser;

$modx->db->connect(); // provide the MODx DBAPI
$edit = new assetMapDatabase();
$update = array (
    'comment' => $_POST['comment'],
'idnote' => $_POST['idnote'],
'subject' => $_POST['subject'],
'idauthor' => $_POST['spelt']
    );
if (!array_empty($update)){
    $edit->commentAdd($update);
}
else {
    return False;
}

function array_empty($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $value) {
            if (!array_empty($value)) {
                return false;
            }
        }
    }
    elseif (!empty($mixed)) {
        return false;
    }
    return true;
}

?>

