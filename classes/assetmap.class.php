<?php

  /**

   **             Community Asset Mapper - Base Class
   **             Copyright 2009, 2010 Halifax Public Libraries
   **
   **    This file is part of the Community Asset Mapper project developed by Ryan Deschamps, copyright Halifax Public Libraries 2009, 2010.

   **     The Community Asset Mapper is free software: you can redistribute it and/or modify
   **     it under the terms of the GNU General Public License as published by
   **     the Free Software Foundation, either version 3 of the License, or
   **     any later version.

   **     The Community Asset Mapper is distributed in the hope that it will be useful,
   **     but WITHOUT ANY WARRANTY; without even the implied warranty of
   **     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   **     GNU General Public License for more details.
   **
   **    You should have received a copy of the GNU General Public License
   **    along with the Community Asset Mapper (see COPYING.txt).  If not, see <http://www.gnu.org/licenses/>.

   **/


class assetMapDatabase implements itables, ibenchmarks, iimages  {

  function __construct() {    // constructor
    $this->checkForModx();
    $this->cam_setVars();
  }

  function __destruct () {    // destructor
    unset ($this->keyword);
  }

  /*
   **
   **    Variables
   **
   **/


  public $orgid = '';  // The id of the current organization
  public $topicid = '';  // The id of the current topic/tag
  public $keyword = '';  // Any keyword search.
  public $pagerows = 10;  // For pagination - total results per page.
  public $pagenum;        // The current page
  public $currentPath = self::TOPIC_CLOUD_PATH;   //  The current path for the page.
  public $last;  //  The final page of results.
  public $filter = False;
  public $headingid = '';
  public $selectval = 1;

  /*
   * Default templates
   */
    public $def_tpl = array (

        /*
         * Template to show an organization inside a table.
         */

        'orgShowByTopic' => '<tr  class="[+cam_class+]"><td  colspan="2"><strong><a href="index.php?id=11&orgid=[+cam_id+]">[+cam_name+] </a></strong>  &nbsp;<em>[+cam_status+]</em<br />
<span style="font-size: 0.8em;">c/o [+cam_contact_name+]<br />[+cam_loc_street+]<br />[+cam_loc_city+], [+am_loc_province+]  [+cam_loc_postal+]<br />[+cam_phone+]</span></td>
</tr>
<tr  class="[+cam_class+]"  style="font-size: 11px; padding: 4px;"><td>Topics: [+cam_topics+]</td><td style="text-transform: uppercase; font-size: 11px; padding: 4px;"><a href="/assetmap/index.php?id=13&orgid=[+cam_id+]">+&nbsp;Add</a></td></tr>
<tr class="table_separator" colspan="2"><td>&#160; </td></tr>',

        /*
         * Page One of an Organization
         */

        'orgShow' => '<div class="org-wrapper">
<script type="text/javascript"> var organization = [+cam_id+];  </script>
<h1>[+cam_name+] </h1>
<div class="edit_link"><em>(Items highlighted in <span class="showblue">blue</span> are editable)</em></div>
<h4>Overview</h4>
<table><tr><td><strong>Founded:</strong></td><td> [+cam_founded+].</td>
<td><strong>First collected</strong> </td><td> [+cam_collected+]</td><td><strong> Last Updated:</strong></td><td> [+cam_updated+]</td></tr>
</table>
<p>
[+cam_descript+]
</p>
<div class="edit_link">
<div class="map_outlay">
<strong>Detailed Asset Map</strong>  &nbsp;&nbsp;&nbsp;&nbsp; <a href="index.php?id=12&orgid=[+cam_id+]">  >> </a><br />
<sub>(Unique notes about the organization - requires staff level access)</sub><br />
</div>
<br />
<strong>View Contacts & Volunteers</strong>  <a href="index.php?id=41&orgid=[+cam_id+]"> >> </a><br /><br />
<strong>Edit [+cam_name+]</strong>  <a href="index.php?id=21&orgid=[+cam_id+]"> >> </a>
</div>
<h4>  Contact Information </h4>
<table class="address">
<tr><td><strong> Email:</strong></td><td>  [+cam_email+], </td></tr>
<tr><td><strong>Contact:</strong></td><td> [+cam_contact_name+]</td></tr>
<tr>
<td><strong>Address:</strong></td>
<td>
[+cam_loc_street+]</td><td> Apt #:[+cam_loc_apt+]</td></tr>
<tr><td>[+cam_loc_pobox+]</td></tr>
<tr>
<td></td><td>[+cam_loc_city+], [+cam_loc_province+]&nbsp;&nbsp;&nbsp;&nbsp; [+cam_loc_postal+]</td></tr>
    <tr><td></td><td> [+cam_phone+]</td><td></td></tr>
    <tr><td><strong>Website:</strong></td><td><a href="[+cam_website+]" target="_blank">[+cam_name+]</a></td></tr>
    </table>


<h4>Topics</h4>
<p> [+cam_topics+]
</p>

<h4>Staff / Users</h4>
<table class="edit"><tr><td> Total Number<br /> of staff: </td> <td class="edit" id="staff"> [+cam_staff+]</td><td>&nbsp;&nbsp;&nbsp; </td><td> Total Number<br /> of users: </td><td class="edit" id="users">[+cam_users+]</td><td> Percentage of staffing<br />provided by Volunteers: </td><td class="edit" id="vols">[+cam_vols+]</td></tr>
</table>
<h4> Open Hours </h4>
[+cam_hours+]
</div>',
        /*
         * Show the open hours for the organization in a table.
         */

        'orgShowHours' => '<table class="table_hours" id="[+cam_id+]">
<tr>
<td> </td><td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td></tr>
<tr><td>Open</td><td class="hours" id="open_Sun">[+open_Sun+]</td><td class="hours" id="open_Mon">[+open_Mon+]</td><td class="hours" id="open_Tue">[+open_Tue+]</td>
<td class="hours" id="open_Wed">[+open_Wed+]</td><td class="hours" id="open_Thu">[+open_Thu+]</td><td class="hours" id="open_Fri">[+open_Fri+]</td>
<td class="hours" id="open_Sat">[+open_Sat+]</td></tr>
<tr><td>Closed</td><td class="hours" id="closed_Sun">[+closed_Sun+]</td><td class="hours" id="closed_Mon">[+closed_Mon+]</td><td class="hours" id="closed_Tue">[+closed_Tue+]</td><td class="hours" id="closed_Wed">[+closed_Wed+]</td><td class="hours" id="closed_Thu">[+closed_Thu+]</td><td class="hours" id="closed_Fri">[+closed_Fri+]</td><td class="hours" id="closed_Sat">[+closed_Sat+]</td></tr>
</table>',


        'orgAdd' => '<p class="error">[+validationmessage+]</p>

<form method="post" action="[~[*id*]~]&orgid=[+cam_id+]" id="editOrg">
 		<h4> Overview</h4>

	<fieldset>
<div class="edit_link" id="didyoumean"></div>

		<input name="formid" type="hidden" value="editOrg" />
<input name="orgid" type="hidden" value="[+cam_id+]" />
<input name="bib_id" type="hidden" value="[+cam_bib_id+]" />

		<label for="cfName">Organization Name:
		<input name="name" id="cfName" type="text" value="[+cam_name+]" size="15" eform="Organization Name:safestring:1:Organization name is required and must have regular text only." /></label>
                <label for="cfFounded">Founded:
		<input name="founded" id="cfFounded" type="text" size="4" value="[+cam_founded+]" eform="Year Founded:integer:::" /></label>  <br /><br /><br />
                <label for="cfDescription"> Organization Description <br />(for comres only - eg. use mission taken from website):<br /><br />
<textarea name="descript" id="cfDescription" cols="45" rows="10"  eform="Description:textarea::Organization Descriptions will not accept non-text characters" />[+cam_descript+]</textarea></label>
</fieldset>
<h4>Location</h4>
<fieldset>
<div class="edit_link" id="val_address"></div>

<label for="cfContact">Contact Name::
<input name="contact_name" id="cfContact" type="text" size="39" value="[+cam_contact_name+]" eform="Contact Name:::" /></label><br />

<label for="cfOrgEmail">Organization Email:
<input name="email" id="cfOrgEmail" type="text" size="35" value="[+cam_email+]" eform="Organizations Email:email::Must be a valid email address:" /></label><br /><br />
                <label for="cfStreet">Street Name:
		<input name="loc_street" id="cfStreet" type="text" value="[+cam_loc_street+]" eform="Street:::" /></label>

<label for="cfApt">Apt Number:
<input name="loc_apt" id="cfApt" type="text" size="3" value="[+cam_loc_apt+]" eform="Apt:::" /></label><br />
                <label for="cfPobox">Street Name line 2:
		<input name="loc_pobox" id="cfPobox" size="35" type="text" value="[+cam_loc_pobox+]" eform="Street:::" /></label><br />
<label for="cfCity">City:
<input name="loc_city" id="cfCity" type="text" size="17" value="[+cam_loc_city+]" eform="City:::" /></label>
<label for="cfProvince">Prov.:
<input name="loc_province" id="cfProvince" type="text" size="2" value="[+cam_loc_province+]" eform="Province:::Province should two letter province code" /></label>
<label for="cfPostal"> Postal:
<input name="loc_postal" id="cfPostal" type="text" size="7" value="[+cam_loc_postal+]" eform="Postal Code::0:Postal Code is required and should be in a valid Canadian or U.S. Postal Code format" /></label><br />
<br /><label for="cfOrgPhone">Phone Number:
<input name="phone" id="cfOrgPhone" type="text" size="12" value="[+cam_phone+]" eform="Organizations Phone:::" /></label><br /><div class="edit_link" id="warn_address"></div>
<label for="cfWebsite">Website URL:
<input name="website" id="cfWebsite" type="text" value="[+cam_website+]" eform="Website:::Website URL must be a valid URL" /></label><br />
<br />
<label for="cfSpecialNote"> Special Note (eg. travel instructions):<br />
<textarea name="special_note" id="cfSpecialNote" cols="45" rows="10" eform="Special Note:textarea::">[+cam_special_note+]</textarea></label>
</fieldset>
<h4> Add Topics </h4>
<fieldset>
<label for="cfTopics"> Topics (comma separated) [+cam_topics+]:
<input name="topics" id="cfTopics" value="" eform="Topics:::" /></label><br />
</fieldset>
<h4> Number of Staff and Users </h4>
<fieldset><label for="cfstaff"> Number of Staff: [+cam_selectStaff+]</label>,
<label for="cfusers">Number of Users [+cam_selectUsers+]</label><br />  <label for="cfvols">Percentage of Staffing Done by Volunteers [+cam_selectVols+]</label>
</fieldset>
<h4>Your Name, Comments (for comres maintainer) and Submit</h4>
<fieldset>

		<label for="cfUserName">Your name:
		<p><input name="user_name" id="cfUserName" class="text" type="text" eform="Your Name::1:" /></p></label>

		<label for="cfUserEmail">Your Email Address:
		<p><input name="user_email" id="cfUserEmail" class="text" type="text" eform="Email Address:email:1" /></p> </label>

		<label for="cfMessage">Message:
		<p><textarea name="message" id="cfMessage" cols="80" rows="5" eform="Message:textarea:1"></textarea></p>
		</label>

		<label>&nbsp;</label><p><input type="submit" name="cam_checkmark" id="cfContact" class="button" value="Send This Message" /></p>

	</fieldset>

</form>',
        /*
         *  contacts & out_contacts template for showing contact information.
         */
        'contacts' => '<tr class="[+cam_con_class+]"><td id="[+cam_idcontacts+]" class="con_name">[+cam_nameLast+],
            [+cam_nameFirst+] [+cam_initial+]</td>
            <td id="con_address"> [+cam_con_street+], [+cam_con_apt+]<br />[+cam_con_pobox+]<br />[+cam_con_city+],
            [+cam_con_province+] [+cam_con_postal+]</td><td id="con_phone">Work: [+cam_con_phone+]<br />
            Fax: [+cam_con_fax+]</td><td id="con_web">[+cam_con_email+]<br />[+cam_con_blog+]</tr>',

        'out_contacts' => '<h4 class="org_contact"> Contacts for [+cam_name+] </h4>
        <div class="edit_link">
        Add New Contact <a href="index.php?id=42&orgid=[+cam_id+]"> >> </a><br />
        </div>
        <table id="[+cam_id+]" class="contacts"><tr><th>Name</th><th>Address</th><th>Phone</th><th> Website / Email etc. </th></tr>'
            );


  /*
   **
   **    Arrays
   **
   **/
  
  

  public $cam_ph = array(   // Placeholder array - used in chunks (eg. [+cam_id+]) to output results.


			 "cam_id" => '',  // The organization id
			 "cam_showTopic" => '',    //  The current topic being used for output
			 "cam_collected" => '',    //  When the data was collected
			 "cam_founded" => '',      //  When the organization was founded
			 "cam_updated" => '',       //  Last Updated
			 "cam_name" => '',         //  Organization Name
			 "cam_website" => '',      //  The URL of the org's Website
			 "cam_special_note" => '', //  Any special notes (esp. for directions)
			 "cam_email" => '',        //  The main email for the organization (will include "contacts" for multiple emails)
			 "cam_contact_name" => '', //  The main organization contact
			 "cam_loc_street" => '',  //  Main mailing address
                         "cam_loc_pobox" => '',
			 "cam_loc_city" => '',     //  City
			 "cam_loc_province" => '', //  Province
			 "cam_loc_postal" => '',   //  Postal Code
			 "cam_loc_apt" => '',      //  Apartment
			 "cam_descript" => '',     //  Organization description
			 "cam_phone" => '',        //  Main Phone Number
			 "cam_bib_id" => '',       //  Bibliographic Number (if one was assigned in Comres)
			 "cam_status" => '',       //  Status settings  0 = comres, 1 = draft, 2 = assigned, but not comres
			 "cam_topics" => '',       //  The topics / tags for the organization in a series of comma separated links.
			 "cam_hours" => '',        //  A table showing the current operating hours for the organization
			 "cam_class" => '',        //  A class name for the <div>
			 "cam_selectquest" => '',   //  A select box to choose questions
			 "cam_users" => '',
			 "cam_staff" => '',
                         "cam_selectUsers" => '',
                         "cam_selectStaff" => ''
			 );

  public $cam_contacts = array (

                            "cam_idcontacts" => '',
                            "cam_nameLast" => '',
                            "cam_nameFirst" => '',
                            "cam_initial" => '',
                            "cam_sex" => '',
                            "cam_con_email" => '',
                            "cam_con_blog" => '',
                            "cam_con_street" => '',
                            "cam_con_pobox" => '',
                            "cam_con_city" => '',
                            "cam_con_province" => '',
                            "cam_con_postal" => '',
                            "cam_con_phone" => '',
                            "cam_con_fax" => '',
                            "cam_con_class" => ''
                            );

  public $cam_comments = array (

        "commentid" => '',
      "noteid" => '',
      "comment" => '',
      "author" => '',
      "date" => '',
      "subject" => ''
  );

  public $cam_con_ia = array (

                            "idcontacts" => '',
                            "nameLast" => '',
                            "nameFirst" => '',
                            "initial" => '',
                            "sex" => '',
                            "con_email" => '',
                            "con_blog" => '',
                            "con_street" => '',
                            "con_pobox" => '',
                            "con_city" => '',
                            "con_province" => '',
                            "con_postal" => '',
                            "con_phone" => '',
                            "con_fax" => '',
                            "con_notes" => ''
                            );


  public $cam_hours = array(    // Placeholder array - used for hours
			    "cam_id" => '',
			    "open_Mon" => '',
			    "open_Tue" => '',
			    "open_Wed" => '',
			    "open_Thu" => '',
			    "open_Fri" => '',
			    "open_Sat" => '',
			    "open_Sun" => '',
			    "closed_Mon" => '',
			    "closed_Tue" => '',
			    "closed_Wed" => '',
			    "closed_Thu" => '',
			    "closed_Fri" => '',
			    "closed_Sat" => '',
			    "closed_Sun" => ''
			    );

  public $cam_ia = array (  //  The input array - for new organizations.


			  "id" => '',
			  "collected" => '',
			  "founded" => '',
			  "name" => '',
			  "website" => '',
			  "special_note" => '',
			  "email" => '',
			  "contact_name" => '',
			  "loc_street" => '',
                          "loc_pobox" => '',
			  "loc_city" => '',
			  "loc_province" => '',
			  "loc_postal" => '',
			  "loc_apt" => '',
			  "descript" => '',
			  "phone" => '',
			  "bib_id" => '',
			  "status" => '',
			  "users" => '',
			  "staff" => '',
                          "vols" => '',


			  );









  /**
   **      --  Initialization Methods - Called by Constuctor  --
   **/




  private function cam_setVars() {  // Checks to see if the initialized values are set and if so, escapes them for security reasons.

    $varArray = array('orgid', 'topicid', 'keyword', 'pagenum', 'filter', 'headingid', 'selectval');
    foreach ($varArray AS $var) {
      if (isset($_GET["$var"])) {
	$this->$var = $this->hpl_clean($_GET["$var"]);
      }
      else {
	unset($this->$var);
      }
    }
    return True;
  }

  private function checkForModx () { // called by constructor - looks for DocumentParser API if it is not available (primarily for AJAX calls)
    if (class_exists(DocumentParser) != True) {
      require_once(MODX_MANAGER_PATH . '/includes/config.inc.php');
      require_once(MODX_MANAGER_PATH . '/includes/protect.inc.php');
      // Setup the MODx API
      define('MODX_API_MODE', true);
      // initiate a new document parser
      include_once(MODX_MANAGER_PATH . '/includes/document.parser.class.inc.php');
      $modx = new DocumentParser;
      $modx->db->connect(); // provide the MODx DBA
      $result = False;
    }
    else {
      $result = True;
    }
    Return $result;
  }

  private function get_tpl($try=False) {
      global $defTPL;
      if (!$defTPL) {
          include_once ('assets/snippets/amapper/tpl/output.tpl.php');
          if (!$try){
          $this->get_tpl(True);
          }
          else {
              print "Could not find defTPL in file.";
          }
      }

      $this->def_tpl = $defTPL;
      return True;
  }

  /*
   **   --  New Organization Methods  --
   **/
  protected function orgGet ($orgid) {
      return $this->hpl_selectOne('*', self::ORG_TABLE, 'id='.$orgid);
  }

  public function orgEdit($fields=Null) {
    $message='';
    $this->orgid = $_GET['orgid'];
    if (isset($this->orgid)) {
      if ($fields) {
	if (isset ($fields['cam_checkmark'])){   // $fields is sometimes used by eForm, otherwise use $_POST.
	      foreach ($this->cam_ia AS $cam => $ia) {
		$this->cam_ia[$cam] = $fields[$cam];
	      }
	      $this->cam_ia['id'] = $this->orgid;
              $this->cam_ia['updated'];
	      $message = $this->updateNewOrg();
                $_SESSION['orgid'] = $this->orgid;
	      unset($fields['cam_checkmark']);
	    }
        if (isset($fields['topics'])){
	$success = $this->topicAdd($field['topics']);
      }
      }
      if (isset ($_POST['cam_checkmark'])) {
	foreach ($this->cam_ia AS $cam => $ia) {
	  $this->cam_ia[$cam] = $_POST[$cam];
	}
	$this->cam_ia['id'] = $this->orgid;
	$message = $this->updateNewOrg();
        $_SESSION['orgid'] = $this->orgid;
	unset($_POST['cam_checkmark']);
      }
              if (isset($_POST['topics'])){
	$success = $this->topicAdd($_POST['topics']);
      }
    }
    else {
      $message =  '<p>I do not seem to have a valid organization to edit.</p> <p>You can:<ul><li> <a href="'.self::SYSTEM_PATH.self::ADD_ORG_PATH.'"> Add a New Organization </a>  OR </li><li>Do a <a href="'.self::SYSTEM_PATH.self::SEARCH_PATH.'"> new search </a></li></ul>';
    }
    return $message;
  }

  public function orgNew() {
    if (isset($_POST['cam_checkmark'])) {
      foreach ($this->cam_ia AS $cam => $ia){
	$this->cam_ia[$cam] = $_POST[$cam];
      }
    $this->orgInsert();
    $this->orgid = $this->hpl_insertId();
    $_SESSION['orgid'] = $this->orgid;
      if (isset($_POST['topics'])){
	$success = $this->topicAdd($_POST['topics']);
      }
      return $this->orgid;
    }
    else {
      return False;
    }
  }

//  where I left off July 2010.
  private function orgInsert() {
    $this->cam_ia['status'] = '1';   // set organization status to "Draft"
    return $this->hpl_insert($this->cam_ia, self::ORG_TABLE);
  }

  private function updateNewOrg () {
    $this->cam_ia['status'] = '1';  // set organization status to "Draft"
    $this->hpl_update('`updated` = NOW( )', self::ORG_TABLE, '`id`='.$this->orgid);
    return $this->hpl_update($this->cam_ia, self::ORG_TABLE, '`id`='.$this->orgid);
  }


  /**
   **   --  Topics / Tags Methods    --
   **/

  public function topicDrop($orgid, $topicid) {  // remove topic from the database
    $result = $this->hpl_delete(self::TOP_ORG, 'id="'.$orgid.'" AND idtopic="'.$topicid.'"');
    if ($result) {
      print "Dropped the Topic";
    }
    else {
      print "Failed.   Contact your administrator.";
    }
  }

  public function topicAdd ($str){  //  insert topic into the database
    $topicArray = $this->topicExplode($str);
    foreach ($topicArray AS $topicAr){
      $newArr = array('id' => $this->orgid, 'idtopic' => $topicAr[0]);
      $inserted = $this->topicOrgInsert($newArr);
    }
  }

  protected function topicShowAll()  // output a comma separated list of links for the current organization.
  {
    $output="";
    $members = $this->hpl_selectArray(
				      self::TOPICS_TABLE.'.topicName, '.self::TOPICS_TABLE.'.idtopic',
				      self::TOPICS_TABLE.' JOIN '.self::TOP_ORG.' ON '.self::TOP_ORG.'.idtopic='.self::TOPICS_TABLE.'.idtopic',
				      self::TOP_ORG.'.id='.$this->orgid
				      );
    foreach($members as $val) {
      $output .= '<a href="'.self::TOPIC_CLOUD_PATH.'&topicid='.$val['idtopic'].'">'.$val['topicName'].'</a>,  ';
    }
    return $output;
  }

  protected function topicShowCurrent()  // show the current topic
  {
    $titleOutput = $this->hpl_selectOne("topicName", self::TOPICS_TABLE, 'idtopic="'.$this->topicid.'"');
    foreach ($titleOutput AS $out) {
      $end = $out;
    }
    return $end;
  }

  public function topicCloud () {   //  output a tag cloud
    $topicListArray = $this->hpl_selectArray(
					   self::TOPICS_TABLE.'.topicName, '.self::TOP_ORG.'.idtopic, COUNT('.self::TOP_ORG.'.idtopic)',
					   self::TOP_ORG.' JOIN '.self::TOPICS_TABLE.' ON '.self::TOP_ORG.'.idtopic='.self::TOPICS_TABLE.'.idtopic GROUP BY '.self::TOP_ORG.'.idtopic',
					   '',
					   self::TOPICS_TABLE.'.topicName'
					   );
    foreach ($topicListArray AS $topic) {
      if ($topic['COUNT('.self::TOP_ORG.'.idtopic)'] >= self::TOPIC_BENCHMARK) {
	$place = round(sqrt((int)$topic['COUNT('.self::TOP_ORG.'.idtopic)']));
	$output .= '<a href="'.self::TOPIC_CLOUD_PATH.'&topicid='.$topic['idtopic'].'" class="topic_value_'.$place.'">'.$topic['topicName'].'</a>&nbsp;&nbsp;';
      }
    }
    return $output;
  }

  private function topicIsNew($topic){  // When we have a new topic to the database
    $topicId="";
    $output="";
    $topic = $this->hpl_clean(strtolower(trim($topic)));
    $topicId = $this->hpl_selectOne("idtopic", self::TOPICS_TABLE, self::TOPICS_TABLE.'.topicName="'.$topic.'"');
    if ($topicId['idtopic']){
      $output= array(False, $topicId["idtopic"], $topic);
      unset ($topicid);
    }
    else {
      $output = array(True, False, $topic);
      unset ($topicid);
    }
    return $output;

  }

  private function  topicExplode($str) {  //  puts a comma separated list of tags into an array
    $topics = explode(",", $str);
    array_filter($topics);
    $newTopic = "";
    $topicArray="";

    foreach ($topics AS $topic){
      $newTopic = $this->topicIsNew($topic);
      if ($newTopic[0]==True){
	$topicArray = array('topicName' => trim($newTopic[2]));
	$output[] = array ($this->topicInsert($topicArray), $newTopic[2]);
        unset ($newTopic);
      }
      else {
	$output[] = array ($newTopic[1], trim($newTopic[2])) ;
        unset ($newTopic);

      }
    }
    return $output;

  }

  private function  topicInsert($topicArray)  {  // basic insert command for new topics/tags
    return $this->hpl_insert($topicArray, self::TOPICS_TABLE);
  }

  private function  topicOrgInsert($topicArray) {  // basic insert command to apply a topic/tag to an organization
    $inserted = $this->hpl_insertIgnore($topicArray, self::TOP_ORG);
    unset ($topicArray);
    return $inserted;
  }

/**
 *
 * -- New Contact Methods--
 *
 */

  public function contactsShow($tpl_inner=False, $tpl_outer=False) {
      $output="";
      $org = $this->orgGet($this->orgid);
      foreach ($org AS $org_ph =>$data){
          $this->cam_ph['cam_'.$org_ph] = $data;
      }
      $contacts = $this->hpl_selectArray('*', self::CONTACTS_TABLE, self::CONTACTS_TABLE.'.id='.$this->orgid);
      foreach ($contacts AS $key=>$data){
          foreach ($data AS $ph => $contact) {
              if ($data['sex']==1){
                  $data['sex'] = 'male';
              }
              else {
                  $data['sex'] = 'female';
              }

	  $this->cam_contacts['cam_'.$ph] = $contact;
          if ((int)$key % 2 == 0) {
	    $this->cam_contacts['cam_con_class'] = 'table_row_2';
	  }
	  else {
	    $this->cam_contacts['cam_con_class'] = 'table_row_1';
	  }



          }
          if ($tpl_inner) {
        $output .= $this->hpl_parseChunk($tpl_inner, $this->cam_contacts);
          }
          else {
              $output .= $this->hpl_parseChunk($this->def_tpl['contacts'], $this->cam_contacts, True);
          }
      }
          if ($tpl_outer) {
        return $this->hpl_parseChunk($tpl_outer, $this->cam_ph).$output;
          }
          else {
              return $this->hpl_parseChunk($this->def_tpl['out_contacts'], $this->cam_ph, True).$output;
          }
  }

  public function contactEdit($nameArray, $contactid){
    return $this->hpl_update($nameArray, self::CONTACTS_TABLE, 'idcontacts = "' .$contactid .'"');
  }

  public function contactNew ($nameArray) {
    if (isset($nameArray['cam_checkmark'])){
        foreach ($this->cam_con_ia AS $name => $field){
            $this->cam_con_ia[$name] = $nameArray[$name];
        }
        $this->cam_con_ia['id'] = $nameArray['orgid'];
        unset($nameArray['orgid']);
    }
    $result = $this->hpl_insert($this->cam_con_ia, self::CONTACTS_TABLE);
    return $result;
  }

  /**
   **    --  Notes / Fieldnotes / Note Comments functions --
   **/

  public function notesShow($tpl){  // Output the notes for the current organization using current template

    global $modx;
    $output="";
    $userid = $this->hpl_getUserId();
    $fieldnotes = $this->hpl_selectArray('*', self::NOTES_TABLE, self::NOTES_TABLE.'.id='.$this->orgid, self::NOTES_TABLE.'.idquest');
    $name = $this->hpl_selectOne(self::ORG_TABLE.'.name',
				 self::ORG_TABLE,
				 self::ORG_TABLE.'.id='.$this->orgid,
				 FALSE
				 );
    $nameArray = array(
		       self::PREFIX."name" => $name['name'],
		       self::PREFIX."id"=>$this->orgid,
                       self::PREFIX."userid"=>$userid
		       );
    $output = $this->hpl_parseChunk('fieldtitle', $nameArray);
    if (empty($fieldnotes)) {
      $output .= 'There are currently no field notes for this organization. <br /><a href="'.self::FIELD_NOTE_PATH.'&orgid='
	.$this->orgid.
	'&create=yes"> Create a New Map for this Organization </a>';
    }
    else {
      foreach ($fieldnotes AS $note){
	$name = $name['name'];
	$text = $note['notetext'];
	$date = $note['dateEntered'];
	$author = $this->notesAuthor($note['idauthor']);

	$headings = $this->notesGetHeading($note['idquest']);
	$heading = $headings['questionTitle'];
	$help = $headings['questionHelp'];
        $comments = $this->commentShow($note['idnote']);
	$fieldnote_array = array(
				 self::PREFIX."note_id"=>$note['idnote'],
				 self::PREFIX."note"=>$text,
				 self::PREFIX."author"=>$author,
				 self::PREFIX."date"=>$date,
				 self::PREFIX."heading"=>$heading,
				 self::PREFIX."trunc_heading"=> substr(str_replace(" ", "", $heading), 0, 10),
				 self::PREFIX."help" =>$help,
                                 self::PREFIX."comments" => $comments,
                                 self::PREFIX."userid" => $userid
				 );
	$output .= $this->hpl_parseChunk($tpl, $fieldnote_array);
      }
    }
    return $output;
  }

  protected function notesTest ($id) {
    return $this->hpl_selectOne ('*', self::NOTES_TABLE, self::NOTES_TABLE.'.id='.$id);
  }

  protected function commentShow ($noteid) {
      $output;
     $comments = $this->hpl_selectArray("*", self::COMMENTS_TABLE, self::COMMENTS_TABLE.'.idnote='.$noteid);
     foreach ($comments AS $comment) {
         foreach ($comment AS $key => $value) {
         $this->cam_comments['cam_'.$key] = $value;
         $this->cam_comments['cam_author'] = $this->notesAuthor($comment['idauthor']);
     }
     $output .= $this->hpl_parseChunk('commentsInner', $this->cam_comments);
     }
     return $output;
  }

  public function commentAdd ($commentArray) {
      return $this->hpl_insert($commentArray, self::COMMENTS_TABLE);
  }

  public function selectquestions (){  // creates select box of field note headings.


    $output = "";
    $members = $this->hpl_selectArray("*", self::HEADINGS_TABLE);
    $output .= '<select name="quests" id="quests" class="questform">';
    foreach ($members AS $p_val) {
      $output .= '<option class="quests" value="'.$p_val['idquest'].'">'.$p_val['questionTitle'].'</option> <img src="" id="help" title="'.$p_val['questionHelp'].'" />'."\n";
    }
    $output .= '</select>';
    return $output;
  }


  public function  noteUpdate ($commentid, $text, $author) { 	// updates a field note

    $result = $this->hpl_update('notetext = "'.$text.'", dateEntered=now(), idauthor= "'.$author.'"', self::NOTES_TABLE, 'idnote ="'.$commentid.'"');
    return $result;		// Returns 'true' on success, 'false' on failure.
  }

  public function updateStaff($id, $org, $value) {  // updates user, staff or vols data based on inputs
    $result = $this->hpl_update($id.' = "'.$value.'"', self::ORG_TABLE, 'id = "'.$org.'"');
    return $result;

  }

  public function  noteAdd ($questionid, $orgid, $text, $author="") {  // add a new field note
    $text = trim($text);
    $noteArray = array (
			"idquest" => $questionid,
			"id" => $orgid,
			"notetext" => $text,
			"idauthor" => $author,
			);
    $result = $this->hpl_insert($noteArray, self::NOTES_TABLE);
    return $result;
  }



  protected function  notesGetHeading($headingID){  //  Get the heading based on the id.


    $output="";
    $fieldnotes = $this->hpl_selectOne (
					self::HEADINGS_TABLE.'.questionTitle, '.self::HEADINGS_TABLE.'.questionHelp',
					self::HEADINGS_TABLE,
					self::HEADINGS_TABLE.'.idquest='.$headingID,
					FALSE
					);
    return $fieldnotes;
  }

  private function  notesAuthor($authorID){  // Get the name of the current user

    if ($authorID == False){
      $authorID == "1";
    }
    $output="";
    $fieldnotes = $this->hpl_selectOne("modx_web_user_attributes.fullname", "modx_web_user_attributes", 'modx_web_user_attributes.id='.$authorID);
    $name=$fieldnotes['fullname'];
    return $name;
  }

  public function mapCreate(){    // Create a starter map for an organization
      $result = 0;
      $ques = '';
      header('location:'.self::FIELD_NOTE_PATH.'&orgid='.$this->orgid);
      if ($this->hpl_selectOne('id', self::NOTES_TABLE, 'id="'.$this->orgid.'"')){
          print 'there is some error - records show this organization already has a map!';
          $result = False;
      }
      else {
      $ques = $this->hpl_selectArray('idquest', self::HEADINGS_TABLE);
      foreach ($ques AS $que) {
        $array = array('idquest' => $que['idquest'],
                	     'id' => $this->orgid,
                         'notetext' => '[No Data]');
        if ($this->hpl_insertIgnore($array, self::NOTES_TABLE)) {
        $result ++;
        }
        }
      }
    return $result;
  }



  /**
   **    --  Output / Data dump functions  --   including Search etc.
   **/


  private function selectUser ($type='staff', $userValue=1) {
      $output = '';
    if ($type == 'staff') {
        $stop = 7;
    }
    else {
        $stop = 5;
    }
    for ($i=0; $i <= $stop; $i++) {
          if ($i == $userValue){
              $output .= '<option value="'.$i.'" selected="selected">'.$this->convertEnums($type, $i).'</option>';
          }
          else {
              $output .= '<option value="'.$i.'">'.$this->convertEnums($type, $i).'</option>';
          }

      }
      return '<select name="'.$type.'" id="cf'.$type.'">'.$output.'</select>';
    }

  private function searchSave ($searchString, $numberResults) {
    $saveArray = array(
		       'searchString' => $searchString,
		       'numberResults' => $numberResults
		       );
    return $this->hpl_insert($saveArray, self::SAVE_SEARCH_TABLE);
  }


  public function orgSearch ($tpl="orgSummary"){  //  Outputs all orgs for a given keyword ($this->keyword)
    $output = "";
    $keyword = "";
    $keyword = str_replace(' ', ' +', $this->keyword);
    $keyword = str_replace('+-', '-', $keyword);
    if($this->filter){
      $join = ' JOIN cam_notes ON cam_notes.id=cam_orgs.id ';
      $andTopic = ' AND cam_notes.idquest="1"';
    }
    $count = $this->hpl_selectCount('*', self::ORG_TABLE.$join, 'MATCH('.self::ORG_TABLE.'.name,'.self::ORG_TABLE.'.contact_name,'.self::ORG_TABLE.'.loc_city,'.self::ORG_TABLE.'.descript) AGAINST("'.$keyword.'" IN BOOLEAN MODE)'.$andTopic);
    $this->searchSave($keyword, $count);
    if ($count) {
      $max = $this->getPageQuery($count);
      $this->currentPath = self::SEARCH_PATH;
      $output .= '<p>Show Organizations for "'.$this->keyword.'"</p><br />'."\n";
      $sweet = $this->hpl_selectArray('*', self::ORG_TABLE.$join, 'MATCH(name,contact_name,loc_city,descript) AGAINST("'.$keyword.'" IN BOOLEAN MODE)'.$andTopic, self::ORG_TABLE.'.name '.$max);
      $pagination =  $this->pagination();
      $output .= '<table><div class="pagination">'.$pagination.'</div>';
      foreach ($sweet as $members) {
	$this->orgid = $members['id'];
	$orgTopics = $this->topicShowAll();
	foreach ($members AS $ph => $da) {
	  $this->cam_ph['cam_'.$ph] = $da;
	}
	if ($this->cam_ph['cam_status'] == '1') {
	  $this->cam_ph['cam_status'] = '(Draft)';
	  $class = 'draft';
	}
	else if ($this->cam_ph['cam_status'] == '1') {
	  $this->cam_ph['cam_status'] = '(Not Included in Comres)';
	  $class = 'not_comres';
	}
	else {
	  $this->cam_ph['cam_status'] = '';
	  if ((int)$key % 2 == 0) {
	    $class = 'table_row_2';
	  }
	  else {
	    $class = 'table_row_1';
	  }
	}
	$this->cam_ph['cam_topics'] = $orgTopics;
	$this->cam_ph['cam_class'] = $class;
	$output .= $this->hpl_parseChunk($tpl, $this->cam_ph);
      }
      $output .= '</table>';
    }
    else {
      $output = '<p>No search results for "'. $this->keyword .'."  Please try another search.</p>';
    }
    return $output;
  }



  public function orgShowByTopic ($tpl=False) // Outputs all the orgs for $this->topicid, using $tpl (a chunk) as a template
  {
    $orgid = "";
    $output = "";
    $pagination = "";

    $output .='<h2>Show Organizations for >>> '.$this->topicShowCurrent().' <<< </h2>'."\n";
    $max = $this->getPageQuery($this->hpl_selectCount('*',
				    self::ORG_TABLE.' JOIN '.self::TOP_ORG.' ON '.self::ORG_TABLE.'.id='.self::TOP_ORG.'.id',
				    self::TOP_ORG.'.idtopic='.$this->topicid,
							 self::ORG_TABLE.'.name'));
    $sweet = $this->hpl_selectArray('*',
				    self::ORG_TABLE.' JOIN '.self::TOP_ORG.' ON '.self::ORG_TABLE.'.id='.self::TOP_ORG.'.id',
				    self::TOP_ORG.'.idtopic='.$this->topicid,
				    self::ORG_TABLE.'.name '.$max);
    $this->currentPath = self::TOPIC_CLOUD_PATH;
    $pagination =  $this->pagination();
    $output .= '<table class="summ_table"><div class="pagination">'.$pagination.'</div>';
    foreach( $sweet as $key => $members ) {
      unset ($this->cam_ph);
      $this->orgid = $members['id'];


      $orgTopics = $this->topicShowAll();
      foreach ($members AS $ph => $da) {
	$this->cam_ph['cam_'.$ph] = $da;
      }
      if ($this->cam_ph['cam_status'] == '1') {
	$this->cam_ph['cam_status'] = '(Draft)';
	$class = 'draft';
      }
      else if ($this->cam_ph['cam_status'] == '1') {
	$this->cam_ph['cam_status'] = '(Not Included in Comres)';
	$class = 'not_comres';
      }
      else {
	$this->cam_ph['cam_status'] = '';
	if ((int)$key % 2 == 0) {
	  $class = 'table_row_2';
	}
	else {
	  $class = 'table_row_1';
	}
      }
      $this->cam_ph['cam_topics'] = $orgTopics;
      $this->cam_ph['cam_class'] = $class;
      if ($tpl) {
        $output .= $this->hpl_parseChunk($tpl, $this->cam_ph);
      }
      else {
        $output .= $this->hpl_parseChunk($this->def_tpl['orgShowByTopic'], $this->cam_ph, True);
      }
    }
    $output .= '</table><div class="pagination">'.$pagination.'</div>';
    return $output;
  }

  public function orgShow($tpl=False) {  // outputs all data from the Organization Table to chunk $tpl
    $output ="";
    $members = $this->hpl_selectOne(
				    '*',
				    self::ORG_TABLE,
				    'id='.$this->orgid,
				    False
				    );
    $orgTopics = $this->topicShowAll();
    $hours = $this->orgShowHours();

    foreach ($members AS $ph => $da) {
      $this->cam_ph['cam_'.$ph] = $da;
    }
    $users = $this->cam_ph['cam_users'];
    $staff = $this->cam_ph['cam_staff'];
    $vols = $this->cam_ph['cam_vols'];
    $this->cam_ph['cam_staff'] = $this->convertEnums('staff', $staff);
    $this->cam_ph['cam_users'] = $this->convertEnums('users', $users);
    $this->cam_ph['cam_vols'] = $this->convertEnums('vols', $vols);
    $this->cam_ph['cam_selectStaff'] = $this->selectUser('staff', $staff);
    $this->cam_ph['cam_selectUsers'] = $this->selectUser('users', $users);
    $this->cam_ph['cam_selectVols'] = $this->selectUser('vols', $vols);
    $this->cam_ph['cam_hours'] = $hours;
    $this->cam_ph['cam_topics'] = $orgTopics;
    $this->cam_ph['cam_selectquest'] = $this->selectquestions();
    if ($tpl) {
        return $this->hpl_parseChunk($tpl, $this->cam_ph);
    }
    else {
        return $this->hpl_parseChunk($this->def_tpl['orgShow'], $this->cam_ph, True);
    }
  }



  /*  Open Hours */

  public function convertEnums($type='staff', $value=1) {
      switch ($type) {
      case 'staff':
	switch ($value){
          case 0:
              $output = 'unknown';
          break;
	case 1:
	  $output = '0 or 1';
	  break;
	case 2:
	  $output = '2 to 5';
	  break;
	case 3:
	  $output = '6 to 10';
	  break;
	case 4:
	  $output = '11 to 20';
	  break;
	case 5:
	  $output = '21 to 50';
	  break;
	case 6:
	  $output = '51 to 200';
	  break;
	case 7:
	  $output = '200 +';
	  break;
	default:
	  $output = 'unknown';
	}
        break; // end case 'staff'

        case 'users':
	switch ($value){
        case 0:
          $output = 'unknown';
          break;
	case 1:
	  $output = '1-25';
	  break;
	case 2:
	  $output = '26-50';
	  break;
	case 3:
	  $output = '51-100';
	  break;
	case 4:
	  $output = '101-200';
	  break;
	case 5:
	  $output = '200+';
	  break;
	default:
	  $output = 'unknown';
	}
        break;   // end case 'users'

        case 'vols' :
            switch ($value) {
            case 0:
                $output = 'unknown';
            break;
            case 1:
                $output = 'no volunteers';
                break;
            case 2:
                $output = '1-10%';
            break;
            case 3:
                $output = '11-30%';
            break;
            case 4:
                $output = '31-60%';
            break;
            case 5:
                $output = '60%+';
            break;

            default:
                $output = 'unknown';

        }
      }
    return $output;
  }

  private function  orgShowHours ($tpl=False) {  // shows the open Hours for the current organization
    $hoursArray = $this->orgHoursGet();
    if (!$hoursArray[0]["Monday"]){
      $this->orgHoursInsert();
    }
    else {
      $this->cam_hours['cam_id'] = $this->orgid;
      $today = getdate();
      foreach ($hoursArray[0] AS $key=>$data){
	$this->cam_hours['open_'.substr($key,0,3)] = $data;
      }
      foreach ($hoursArray[1] AS $ckey=>$cdata){
	$this->cam_hours['closed_'.substr($ckey,0,3)] = $cdata;
      }
    if ($tpl) {
        return $this->hpl_parseChunk($tpl, $this->cam_hours);
    }
    else {
        return $this->hpl_parseChunk($this->def_tpl['orgShowHours'], $this->cam_hours, True);
    }
    }


  }


  private function  orgHoursInsert () {  //  What to do if the organization has no hours data


    header('location:'.self::SYSTEM_PATH.self::ORG_PAGE_PATH.'&orgid='.$this->orgid);
    $openArray = array('id' => $this->orgid);
    $closeArray = array('id' => $this->orgid);
    if ($this->hpl_insertIgnore($openArray, self::HOURS_TABLE)){
      return $this->hpl_insertIgnore($closeArray, self::HOURS_TABLE);
    }
    unset ($hoursArray);
    exit;

  }


  private function  orgHoursGet (){    //  Get the open hours from the database


    return $this->hpl_selectArray(
				'*',
				self::HOURS_TABLE,
				self::HOURS_TABLE.'.id='.$this->orgid,
				False
				);
  }

  public function  addOpenHours ($orgid='1', $idhours='1', $day='Sunday', $data='[no data]') {  //  Add open hours to the database
    $orgid = $this->hpl_clean($orgid);
    $day = $this->hpl_clean($day);
    $data = trim($this->hpl_clean($data));
    return $this->hpl_update($day.' = "'.$data.'"', self::HOURS_TABLE, 'id="'.$orgid.'" && idhours="'.$idhours.'"');
  }

  /*  Pagination */

  private function getPageQuery($rows) {

    if (!(isset($this->pagenum))) {
      $this->pagenum = 1;
    }
    $this->last = ceil($rows / $this->pagerows);
    if ($this->pagenum < 1)
      {
	$this->pagenum = 1;
      }
    elseif ($this->pagenum > $this->last)
      {
	$this->pagenum = $this->last;
      }
    return 'LIMIT ' . ($this->pagenum - 1) * $this->pagerows . ',' . $this->pagerows;

  }

  private function pagination () {
    $pagination = "";
    if ($this->topicid){
      $link = $this->currentPath.'&topicid='.$this->topicid.'&pagenum=';
    }
    else {
      $link = $this->currentPath.'&keyword='.$this->keyword.'&pagenum=';
    }
    $pagination = '<p>-- Page '.$this->pagenum.' of '.$this->last. '.</p>';
    if ($this->pagenum == 1)
      {

      }
    else
      {
	$pagination .= '&nbsp;&nbsp; <a href="'.$link.'1"> &lt;&lt; First </a> &nbsp;&nbsp;';
	$pagination .=  " ";
	$previous = $this->pagenum - 1;
	$pagination .= ' &nbsp;&nbsp;<a href="'.$link.$previous.'"> &lt; Prev </a>&nbsp;&nbsp; ';
      }

    for ($ipage = -3; $ipage <= 2; $ipage++) {

      if (($this->pagenum + $ipage) <= 0) {
	$ipage ++;
      }

      elseif (($this->pagenum + $ipage) == $this->pagenum) {
	$pagination .= '&nbsp;'.$this->pagenum.'&nbsp;';
      }
      elseif (($this->pagenum + $ipage)==$this->pagenum){
	$ipage ++;
      }
      elseif (($this->pagenum + $ipage) > $this->last) {
	break;
      }
      else{
	$pagination .= '&nbsp;<a href="'.$link.($this->pagenum+$ipage).'">'.($this->pagenum+$ipage).'</a>&nbsp;';
      }

    }
    if (($this->pagenum + 2) < ($this->last-2)) {
      $pagination .= ' ... ';
      for ($epage = -2; $epage <= 0; $epage++) {
	$pagination .= '&nbsp;<a href="'.$link.($this->last+$epage).'">'.($this->last+$epage).'</a>';
      }
    }


    $pagination .= ' -- ';
    if ($this->pagenum == $this->last)
      {
      }
    else {
      $next = $this->pagenum + 1;
      $pagination .= '&nbsp;&nbsp; <a href="'.$link.$next.'"> Next &nbsp;&gt;&nbsp; </a> &nbsp;&nbsp;';
      $pagination .= " ";
      $pagination .= '&nbsp;&nbsp; <a href="'.$link.$this->last.'"> Last&nbsp;&gt;&gt;&nbsp; </a>&nbsp;&nbsp; ';
    }
    return $pagination;


  }


  /**
   **    Deprecated for now.   May be revisited when / if we include a "contact list" element to the database
   protected function  getOrgPhone ($orgid)
   {
   global $modx;
   $output="";
   $orgid = $modx->db->escape($orgid);
   $phone = $modx->db->select("hpl_am_phone.area_code, hpl_am_phone.number, hpl_am_phone.type", "hpl_am_phone", "hpl_am_phone.id=$orgid");
   $phones = $modx->db->makeArray($phone);
   foreach ($phones AS $number) {
   switch($number['type']) {
   case "fax":
   $type = '(f)';
   break;
   case "home":
   $type = '(h)';
   break;
   case "cell":
   $type = '(c)';
   break;
   default:
   $type = '(w)';
   break;
   }
   $output .= '('.$number['area_code'].') '.substr_replace($number['number'], "-", 3, 0).' '.$type;
   }
   return $output;
   }
  */


  /*
   **
   **  Also deprecated as Address data moved to main "organization" table.
   **
   protected function hpl_getOrgAddress ($orgid)
   {
   global $modx;
   $output="";
   $orgid = $modx->db->escape($orgid);
   $address = $modx->db->select("hpl_am_addresses.careOf, hpl_am_addresses.street, hpl_am_addresses.city, hpl_am_addresses.province, hpl_am_addresses.postalCode, hpl_am_addresses.email, hpl_am_addresses.websiteURL", "hpl_am_addresses", "hpl_am_addresses.id=$orgid");
   $addresses = $modx->db->makeArray($address);
   foreach ($addresses AS $number) {
   $contact .= 'c/o: '.$number['careOf'].'<br />'.$number['street'].'<br />'.$number['city'].', '.$number['province'].'  '.$number['postalCode'];
   $link = $number['websiteURL'];
   $email = $number['email'];
   }
   $output = array('address'=>$contact, 'link'=>$link, 'email'=>$email);
   return $output;
   }
  */



    /*
     **
     **             The following methods really just are ModX api calls.   They are mostly here for a) to avoid calling globals and b)
     **             for future modifications to support other CMSs etc.   And yes, I do know they should go into another class.
     **
     **
     **/



  private function hpl_clean ($data) {
    global $modx;
    return $modx->db->escape($data);
  }

  private function hpl_cleanArray($dataArray) {
      if (!is_array($dataArray)){
          return $dataArray;
      }
      else {
      foreach ($dataArray AS $key=>$data){
          $newArray[$key] = $this->hpl_clean($data);
      }
      return $newArray;
      }
  }

  public function hpl_getUserId() {
      global $modx;
      $userid = $modx->getLoginUserID();
      return $userid;
  }

  public function hpl_selectArray ($field, $table, $where="", $sortby="", $count=False) {
    global $modx;
    $res = $modx->db->select($field,
			     $table,
			     $where,
			     $sortby
			     );
    return $modx->db->makeArray($res);
  }

  private function hpl_selectCount ($field, $table, $where="", $sortby="") {
    global $modx;
    $res = $modx->db->select($field,
			     $table,
			     $where,
			     $sortby
			     );
    return $modx->db->getRecordCount($res);
  }

  public function hpl_selectOne ($field, $table, $where="", $sortby="") {
    global $modx;
    $res = $modx->db->select($field,
			     $table,
			     $where,
			     $sortby
			     );
    return $modx->db->getRow($res);
  }

  private function hpl_insert ($array, $table) {
    global $modx;
    return $modx->db->insert($this->hpl_cleanArray($array), $table);
  }

  private function hpl_insertIgnore ($array, $table) {
    global $modx;
    foreach ($array AS $key=>$element) {
      $newRay["$key"] = $this->hpl_clean($element);
      $values .= '"'.$newRay["$key"].'",';
      $columns .= $key.',';
    }
    $values = substr($values, 0, -1);
    $columns = substr($columns, 0, -1);
    return $modx->db->query('INSERT IGNORE INTO '.$table.' ('.$columns.') VALUES ('.$values.')');
  }

  public function hpl_parseChunk ($chunk, $chunkArray, $def=False) {
    global $modx;
    if ($def) {
        foreach ($chunkArray as $key => $value) {
            $chunk = str_replace('[+' . $key . '+]', $value, $chunk);
        }
        return $chunk;
    }
    else {
    return $modx->parseChunk($chunk, $chunkArray, "[+", "+]");
    }
  }

  private function hpl_update ($update, $table, $where){
    global $modx;
    return $modx->db->update($update, $table, $where);
  }

  private function hpl_delete ($from, $where, $fields="") {
    global $modx;
    return $modx->db->delete($from, $where, $fields);
  }

  public function hpl_insertId () {
    global $modx;
    return $modx->db->getInsertId();
  }
  /**
   * Remove HTML tags, including invisible text such as style and
   * script code, and embedded objects.  Add line breaks around
   * block-level tags to prevent word joining after tag removal.
   */




  //end of class
}


/*
 **
 **         Interfaces / Constants
 **
 **
 **/



interface itables {  //  Table names
  const ORG_TABLE = 'cam_orgs';
  const NOTES_TABLE = 'cam_notes';
  const HEADINGS_TABLE = 'cam_headings';
  const HOURS_TABLE = 'cam_hours';
  const CONTACTS_TABLE = 'cam_contacts';
  const TOPICS_TABLE = 'cam_topics';
  const TOP_ORG = 'cam_topics_orgs';
  const SAVE_SEARCH_TABLE = 'cam_search_data';
  const COMMENTS_TABLE = 'cam_comments';
  const LOG_TABLE = 'cam_log';

}

interface ibenchmarks {

  const SYSTEM_PATH = 'http://intranet/assetmap/';
  const TOPIC_BENCHMARK = '4';   // The minimum number for inclusion in a topic cloud.
  const FIELD_NOTE_PATH = 'index.php?id=12';
  const TOPIC_CLOUD_PATH = 'index.php?id=9';
  const ORG_PAGE_PATH = 'index.php?id=11';
  const TOPIC_DROP_PATH = 'index.php?id=18';
  const SEARCH_PATH = 'index.php?id=2';
  const ADD_ORG_PATH = 'index.php?id=4';
  const WORDTOP_PATH = 'index.php?id=27';
  const WORDCOMP_PATH = 'index.php?id=34';
  const PREFIX = 'cam_';
  const MODX_MANAGER_PATH = '../../../../manager/';
}

interface iimages {

  const IMG_DELETE = 'assets/snippets/amapper/images/icons/delete.png';

}



class camSummary extends assetMapDatabase {


  public $catchwords = array (
			      'this', 'that','with','here','they','there','their',
                              'into','were','when','which','from','under','where','while',
                              'without'
			      );

  public $sum_out = array(
			  'cam_topic' => '',
			  'cam_cloud' => '',
			  'cam_heading' => '',
                          'cam_name' => '',
                          'cam_note' => '',
                          'cam_email' => '',
                          'cam_id' => '',
                          'cam_entered' => ''

			  );

  public $sum_tpl_inner = 'cam_summ';
  public $sum_tpl_outer = 'cam_summ_outer';
  public $sum_def_tpl = array (
      'compare' => '<div class="small_box">
<h3><a href="index.php?id=11&orgid=[+cam_id+]">[+cam_name+]</a></h3>
<p>[+cam_note+]</p>
</div>',
      'compare_out' => '<p> Organizations with topic "[+cam_topic+]"</p>
<h4> [+cam_heading+]</h4>',
      'choose_question' => 'Choose a question
<table class="word_cloud">
<tr><td>
<a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=1"> Detailed Community Activities<br /> and Services  </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=2"> Types of Users  </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=3">  Challenges </a></td>
</tr>
<tr>
<td>
<a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=4">Funding <br /> </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=5"> Current Use of the Library </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=6"> Perceptions of the library </a></td>
</tr>
<tr>
<td>
<a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=7"> What Could the Library Be Doing<br /> for Your Organization  </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=8"> Potential Partnerships  </a></td>
<td><a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=9"> Comments  </a></td>
</tr>
<tr>
<td>
<a href="index.php?id=[*id*]&[+get+]=[+topic+]&headingid=10"> Organization History <br /> </a></td>
<td></td>
<td></td>
</tr>
</table>
<hr />'

  );

  public $noteArray;

  public function summBySize($type) {
    $check = $this->cloudUp($this->getFieldNotesBySize($type, $this->selectval));
    $this->sum_out['cam_topic'] = 'Total '.$type.' range of '.$this->convertEnums($type, $this->selectval);

    if ($check){
        foreach ($this->noteArray AS $key => $value){
            $this->sum_out['cam_cloud'] = $value;
            $heading = $this->notesGetHeading($key);
            $this->sum_out['cam_heading'] = $heading['questionTitle'];
            if ($this->sum_tpl_inner) {
            $output .= $this->hpl_parseChunk ($this->sum_tpl_inner, $this->sum_out);
            }
            else {
                $output .= $this->hpl_parseChunk ($this->sum_def_tpl['compare'], $this->sum_out, True);
            }
        }
    }
    else {
        $output = 'Sorry, there are no notes for any organizations in this range.';
    }
    if ($this->sum_tpl_outer) {
            return $this->hpl_parseChunk($this->sum_tpl_outer, $this->sum_out).$output;
    }
    else {
        return $this->hpl_parseChunk($this->sum_def_tpl['compare_out'], $this->sum_out, True).$output;
    }
  }


  public function summByTopic () {
    $this->cloudUp($this->getFieldNotesByTag());
    $this->sum_out['cam_topic'] = $this->topicShowCurrent();

    foreach ($this->noteArray AS $key => $value){
      $this->sum_out['cam_cloud'] = $value;
      $heading = $this->notesGetHeading($key);

      $this->sum_out['cam_heading'] = $heading['questionTitle'];
      $output .= $this->hpl_parseChunk ($this->sum_tpl_inner, $this->sum_out);
    }
    return $this->hpl_parseChunk($this->sum_tpl_outer, $this->sum_out).$output;
  }
    public function sizeComparison ($type) {
      $members = $this->getFieldNotesBySize($type, $this->selectval);
      $this->sum_out['cam_topic'] = 'Total '.$type.' range of '.$this->convertEnums($type, $this->selectval);
      if ($members){
        foreach ($members AS $key => $value) {
              if ($value['idquest'] == $this->headingid){
                $this->sum_out['cam_id'] = $value['id'];
                $orgName = $this->orgGet($value['id']);
                $this->sum_out['cam_name'] = $orgName['name'];
                $heading = $this->notesGetHeading($value['idquest']);
                $this->sum_out['cam_heading'] = $heading['questionTitle'];
                $this->sum_out['cam_note'] = $value['notetext'];
                $output .= $this->hpl_parseChunk($this->sum_tpl_inner, $this->sum_out);
            }
        }
      }
      else {
          $output = "Sorry there are no topic comparisons for organizations in this range.";
      }
      return $this->hpl_parseChunk($this->sum_tpl_outer, $this->sum_out).$output;
    }

  public function topicComparison () {
      $members = $this->getFieldNotesByTag();
      $this->sum_out['cam_topic'] = $this->topicShowCurrent();
      foreach ($members AS $key => $value) {
          if ($value['idquest'] == $this->headingid){
          $this->sum_out['cam_id'] = $value['id'];
          $orgName = $this->orgGet($value['id']);
          $this->sum_out['cam_name'] = $orgName['name'];
          $heading = $this->notesGetHeading($value['idquest']);
          $this->sum_out['cam_heading'] = $heading['questionTitle'];
          $this->sum_out['cam_note'] = $value['notetext'];
          $output .= $this->hpl_parseChunk($this->sum_tpl_inner, $this->sum_out);
          }
      }
      return $this->hpl_parseChunk($this->sum_tpl_outer, $this->sum_out).$output;
  }


  public function wordTopicCloud ($path='summ') {   //  output a tag cloud
       if ($path=='summ'){
           $path = self::WORDTOP_PATH;
       }
       else {
           $path = self::WORDCOMP_PATH;
       }
     $topicListArray = $this->hpl_selectArray(
					      self::TOPICS_TABLE.'.topicName, '.self::TOP_ORG.'.idtopic, COUNT('.self::TOP_ORG.'.idtopic), '.self::TOP_ORG.'.id ',
					      self::TOP_ORG.' JOIN '.self::TOPICS_TABLE.' ON '.self::TOP_ORG.'.idtopic='.self::TOPICS_TABLE.'.idtopic GROUP BY '.self::TOP_ORG.'.idtopic',
					      '',
					      self::TOPICS_TABLE.'.topicName'
					      );
     foreach ($topicListArray AS $topic) {
       if ($this->notesTest($topic['id'])){
	 if ($topic['COUNT('.self::TOP_ORG.'.idtopic)'] >= 1) {
	   $place = round(sqrt((int)$topic['COUNT('.self::TOP_ORG.'.idtopic)']));
	   $output .= '<a href="'.$path.'&topicid='.$topic['idtopic'].'" class="topic_value_'.$place.'">'.$topic['topicName'].'</a>&nbsp;&nbsp;';
	 }
       }
     }
     return $output;
   }


  private function createCloud ($array) {

    foreach ($array AS $key => $value) {
      $place = 	round((int)$value ^2);
      $output .= ' <span class="topic_value_'.$place.'"> '.$key.' </span> ';
    }

    return $output;

  }

  private function getFieldNotesByTag () {
    $cloud = $this->hpl_selectArray(
			     '*',
			     self::NOTES_TABLE.' JOIN '.self::TOP_ORG.' ON '.self::NOTES_TABLE.'.id='.self::TOP_ORG.'.id',
			     self::TOP_ORG.'.idtopic='.$this->topicid);

    return $cloud;

  }

  private function getFieldNotesBySize ($type='staff', $value=1) {
      $cloud = $this->hpl_selectArray(
                              '*',
                              self::NOTES_TABLE.' JOIN '.self::ORG_TABLE.' ON '.self::NOTES_TABLE.'.id='.self::ORG_TABLE.'.id',
			     self::ORG_TABLE.'.'.$type.'='.$value);
      return $cloud;
  }

  private function cloudUp ($cloud) {
      if ($cloud){
        foreach ($cloud AS $value){
            $newcloud[$value['idquest']][] = $value['notetext'];
            }
        foreach ($newcloud AS $key=>$data) {
            $this->noteArray[$key] .= $this->createCloud($this->cleanCloud(implode($data)));
            }
        return True;
      }
      else {
          return False;
      }
  }

  private function cleanCloud ($str) {
    $str = strtolower($this->strip_html_tags(str_replace('[No Data]', '', $str)));
    $str = str_replace('&nbsp;', ' ', $str);
    $str = preg_replace('/\W/', ' ', $str);
    $str = explode (' ', $str);
    foreach($str as $key => $value) {
      if($value == "" || strlen($value) <= 3) {
	unset($str[$key]);
      }
      if(in_array($value, $this->catchwords)){
	unset($str[$key]);
      }
    }
    $array = array_count_values($str);
    ksort($array);
    return $array;
  }
  private function strip_html_tags($text)
  {
    $text = preg_replace(
			 array(
			       // Remove invisible content
			       '@<head[^>]*?>.*?</head>@siu',
			       '@<style[^>]*?>.*?</style>@siu',
			       '@<script[^>]*?.*?</script>@siu',
			       '@<object[^>]*?.*?</object>@siu',
			       '@<embed[^>]*?.*?</embed>@siu',
			       '@<applet[^>]*?.*?</applet>@siu',
			       '@<noframes[^>]*?.*?</noframes>@siu',
			       '@<noscript[^>]*?.*?</noscript>@siu',
			       '@<noembed[^>]*?.*?</noembed>@siu',
			       // Add line breaks before and after blocks
			       '@</?((address)|(blockquote)|(center)|(del))@iu',
			       '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
			       '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
			       '@</?((table)|(th)|(td)|(caption))@iu',
			       '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
			       '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
			       '@</?((frameset)|(frame)|(iframe))@iu',
        ),
			 array(
			       ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
			       "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
			       "\n\$0", "\n\$0",
			       ),
			 $text );
    return strip_tags( $text );
  }

}


?>

