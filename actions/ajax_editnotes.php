<?php
define(MODX_MANAGER_PATH , '../../../../manager/');//relative path for manager folder

require_once(MODX_MANAGER_PATH . '/includes/config.inc.php');
require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');
include_once("../classes/assetmap.class.php");
$org;
$id;
$content;
$stuff;

// Setup the MODx API
define('MODX_API_MODE', true);
// initiate a new document parser
include_once(MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
$modx = new DocumentParser;

$modx->db->connect(); // provide the MODx DBAPI
$edit = new assetMapDatabase();
$user = $_POST['spell'];
$org = $_POST['org'];
$staff = $_POST['ret'];
$content = $_POST['value'];
$id = $_POST['id'];
$newid = explode ('_', $id);
$contact = $_POST['contact'];


if ($contact == 'name') {
    $explodeName = preg_split('/\s+/', $content, '3');
    $nameArray['initial'] =  preg_replace('/[\$,]/', '', $explodeName[2]);
    $nameArray['nameLast'] =  preg_replace('/[\$,]/', '', $explodeName[0]);
    $nameArray['nameFirst'] =  preg_replace('/[\$,]/', '', $explodeName[1]);
    $nameArray['id'] = $org;
    $edit->contactEdit($nameArray, $id);
    echo $content;
    }

if ($id == 'staff' || $id == 'users' || $id == 'vols') {
    $edit->updateStaff($id, $org, $content);
    echo $edit->convertEnums($id, $content);
    unset ($id);
    }

if ($newid[0]=='desc') {
    $edit->noteUpdate($newid[1], $content, $user);
    echo $content;
    }

if  ($newid[0] == 'open' || $newid[0] == 'closed'){
    if ($newid[0]=='open') {
       $idhours='1';
     }
     else {
       $idhours='2';
     }
     switch ($newid[1]) {
     case 'Mon':
       $day = 'Monday';
       break;
     case 'Tue':
       $day = 'Tuesday';
       break;
     case 'Wed':
       $day = 'Wednesday';
       break;
     case 'Thu':
       $day = 'Thursday';
       break;
     case 'Fri':
       $day = 'Friday';
       break;
     case 'Sat':
       $day = 'Saturday';
       break;
     case 'Sun':
       $day = 'Sunday';
       break;

     }
     $edit->addOpenHours($org, $idhours, $day, $content);
     echo $content;
   }
?>