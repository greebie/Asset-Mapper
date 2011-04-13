<?php
define(MODX_MANAGER_PATH , '../../../../manager/');//relative path for manager folder
include_once("../classes/assetmap.class.php");
require_once(MODX_MANAGER_PATH . '/includes/config.inc.php');
require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');
$edit;
$modx;
$tpl_inner = 'errorbox';

// Setup the MODx API
define('MODX_API_MODE', true);
// initiate a new document parser
include_once(MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
    $modx = new DocumentParser;
    $modx->db->connect(); // provide the MODx DBAPI
    if (isset($_GET['where'])){
echo sendItOut($_GET['where'], 'errorbox');
    }
    if (isset($_GET['postal'])){
        echo validPostal($_GET['postal']);
    }
    if (isset($_GET['contact'])){
        echo validContact($_GET['contact']);
    }

function validContact($contact) {
    $contact = trim($contact);
    $output = 'failed';
    if (preg_match('/^[^0-9!@#$%^&*()+=]+$/', $contact)) {
                $edit = new assetMapDatabase();
        $query = $edit->hpl_selectArray('id, name', 'cam_orgs', 'cam_orgs.contact_name LIKE "%'.$contact.'%"', 'cam_orgs.name LIMIT 0,5');
        if (empty($query)){
            $output = 'success';
        }
        else{
            $output = '';
            foreach($query AS $skip) {
            $output .= '<li><a href="index.php?id=11&orgid='.$skip['id'].'">'.$skip['name'].'</a>';
        }

       }

    }
    return $output;
}

function sendItOut ($where, $tpl_inner='errorbox') {
    $output ='<ul>';
    $data;
    $edit = new assetMapDatabase();
    $query = $edit->hpl_selectArray('id, name', 'cam_orgs', 'cam_orgs.name LIKE "%'.$where.'%"', 'cam_orgs.name LIMIT 0,5');
    foreach ($query AS $skip) {
            $output .= '<li><a href="index.php?id=11&orgid='.$skip['id'].'">'.$skip['name'].'</a></li>';
    }
    return $output.'</ul>';
}

function validPostal($postal){
    $postal = trim(strtoupper($postal));
    $output = "failed";
    if (preg_match("/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/", $postal)){
        $edit = new assetMapDatabase();
        $query = $edit->hpl_selectArray('id, name', 'cam_orgs', 'cam_orgs.loc_postal LIKE "%'.$postal.'%"', 'cam_orgs.name LIMIT 0,5');
        if (empty($query)){
            $output = 'success';
        }
        else{
            $output = '';
            foreach($query AS $skip) {
            $output .= '<li><a href="index.php?id=11&orgid='.$skip['id'].'">'.$skip['name'].'</a>';
        }

       }
    }
        return $output;
    }
?>

