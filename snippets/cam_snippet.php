<?php
/*
 *
 * This is the main ModX snippet for using the Asset Mapper Tool.   It should be copied into modx as "CAM".
 *
 * Typical snippet call would be
 *
 * [[cam? &op=`OPERATION_NAME` &jax=`CHUNK_FOR_JS_CALLS` &tpl_inner=`INNER_TEMPLATE_CHUNK` &tpl_outer`OUTER_TEMPLATE_CHUNK`]]
 *
 */

include_once("assets/snippets/amapper/classes/assetmap.class.php");

$var;
$_SESSION['orgid'] = $_GET['orgid'];


insertJS($jax, $op);
if ($op) {

   useOp($op, $tpl_inner, $tpl_outer, $var);

}



function useOp($op=False, $tpl_inner=False, $tpl_outer=False, $var=False){

    global $cam;
    global $summ;
    global $modx;

    $cam = new assetMapDatabase;  // for main commands
    $summ = new camSummary;       // for summaries / comparisons / wordclouds etc.


if ($tpl_inner) {
    $summ->sum_tpl_inner = $tpl_inner;
}
if ($tpl_outer) {
    $summ->sum_tpl_outer = $tpl_outer;
}

    switch ($op) {
        case 'bytag':    // show organizations for a given topic id.   or Summary is the main template.
            echo $cam->orgShowByTopic($tpl_inner);
            break;

        case 'contacts':   // show contact page for an organization
         echo $cam->contactsShow($tpl_inner, $tpl_outer);
         break;

       case 'byRange':  // show a comparison by the size of the organization
            echo $summ->summBySize($var);
            break;

        case 'compare':  // compare organizations based on a topic.
            echo $summ->wordTopicCloud('compare');
            break;

       case 'compareBySize':  // compare organizations based on their size
            echo $summ->sizeComparison($var);
            break;

        case 'comparison':  //
            echo $summ->topicComparison();
            break;

        case 'summCloud' :
            echo $summ->wordTopicCloud('summ');
            break;

        case 'addOrg':
            $cam->orgNew();   // add a new organization
            $_SESSION['orgid'] = $cam->orgid;
            break;

        case 'summary':
            echo $summ->summByTopic();
            break;

        case 'topicCloud':   // show a cloud of all the topics.
            echo $cam->topicCloud();
            break;

        case 'show':   // show an organization based on $cam->orgid (called by class)
              echo $cam->orgShow($tpl_inner);
             break;

        case 'field':   // creates a set of field notes if asked.  Otherwise shows the asset map.
            if ($_GET['create'] == "yes") {
            $cam->mapCreate();
            }
            else {
            echo $cam->notesShow("fieldnote");
            }
            break;

        case 'search':   // show search results
            if(isset($_POST['checkmark'])){
            $cam->keyword = $_POST['keyword'];
            $cam->filter = $_POST['filter'];
            echo $cam->orgSearch("orgSummary");}

            elseif(isset($_GET['keyword'])) {
            $cam->keyword = $_GET['keyword'];
            echo $cam->orgSearch("orgSummary");}
            break;

        case 'cloud':   // duplicate of 'topicCloud' choose one and lose the other pls.
            echo $cam->topicCloud();
           break;

        case 'add':    // add a topic or tag.   This form needs improvement.
            if (isset($_POST['submit'])) {
            if ($_GET['orgid']) {
            $str = $_POST['tags'];
            print $str;
            echo $cam->topicAdd($str);
            unset ($_GET['orgid']);
            unset ($_POST['submit']);
            }
            else{
            print "No organization parameters listed - tags will not be applied";
            }
            }
            break;

        case 'formMessage':  // create a message after a form has been submitted.
            if (!$_SESSION['orgid']) {
                $whatever = $modx->db->getInsertId();
                }
            else {
                $whatever = $_SESSION['orgid'];
            }

            $whateverArray = array(
                'notesLink' => '<a href="'.ibenchmarks::SYSTEM_PATH.ibenchmarks::FIELD_NOTE_PATH.'&orgid='.$whatever.'"> update the notes for this organization. </a>',
                'orgLink' => '<a href="'.ibenchmarks::SYSTEM_PATH.ibenchmarks::ORG_PAGE_PATH.'&orgid='.$whatever.'"> organization\'s summary </a>'
                );
            echo $whateverArray[$param];
            break;

        case 'eformTPL':
            if(!class_exists (assetMapDatabase)) {
            print 'cant find the class';
            }
            else {
            echo $cam->orgShow('addNewOrg');
            }
            break;

        default:
        }
}

function replaceChunksAndSnippets(&$fields, &$templates) {
    global $modx;
    $templates['tpl']=$modx->mergeChunkContent($templates['tpl']);
    $templates['tpl']=$modx->evalSnippets($templates['tpl']);
    return true;
}

function updateOrg (&$fields){   // update and organization.
    global $cam;
   $cam = new assetMapDatabase;
    $cam->orgEdit($fields);
    return true;
}

function getOrgId (&$fields) {  // way of retrieving the Organization id for ajax calls
   global $cam;
   $cam = new assetMapDatabase;
   if (!$_SESSION['orgid']) {
       if ($cam->orgid) {
                    $fields['orgid'] = $cam->orgid;
                 return True;
                }
      else {
              if ($cam->hpl_insertId()) {
                   $fields['orgid'] = $cam->hpl_insertId();
                     return True;
                   }
               else {
                       return False;
                    }
             }
    }
   else {
     $fields['orgid'] = $_SESSION['orgid'];
           return True;
         }
}

function newContact(&$fields) {   // add a new contact
   global $cam;
   $cam = new assetMapDatabase;
   $cam->orgid = $_SESSION['orgid'];
 if($cam->contactNew($fields)){
return true;
}
else {
return false;
}
}

function insertJS ($jax=False, $op=False) {

    global $modx;


    $def_tpl['orgEdit'] = <<<TPL
<script src="assets/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/jquery.jeditable.mini.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/jquery.timepicker.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.editables.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.validate.js" type="text/javascript"></script>
TPL;
    $def_tpl['fieldEdit'] = <<<TPL
<script src="assets/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/jquery.jeditable.mini.js" type="text/javascript"></script>
<script src="assets/plugins/tinymce3241/jscripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.edit-in-place.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.editables.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.validate.js" type="text/javascript"></script>
TPL;

    $def_tpl['validate'] = <<<TPL
<script src="assets/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="assets/snippets/amapper/js/assetmap.validate.js" type="text/javascript"></script>
TPL;

        if (isset($jax)):
		$js = $modx->getChunk($jax);
        else :  // no Chunk, so using default templates
            switch ($op){
            case 'show':
                $js = $def_tpl['orgEdit'];
            break;

            case 'contacts':
                $js = $def_tpl['orgEdit'];
            break;

            case 'field':
                $js = $def_tpl['fieldEdit'];
            break;

            default:
                $js = $def_tpl['validate'];

        }
	endif;
        $modx->regClientStartupScript($js);
}
?>
