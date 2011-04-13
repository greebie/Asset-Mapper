<?php
include_once("/home/webuser/www/assetmap/assets/snippets/amapper/classes/assetmap.class.php");
if (class_exists(assetMapDatabase)){
$cam = new assetMapDatabase;
echo $cam->orgShow('addNewOrg');
}
else {
print 'cant find the class';
}
?>
