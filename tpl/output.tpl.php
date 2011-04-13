<?php

$defTPL;

$defTPL['tpl'] = <<< TPL

TPL;

$defTPL['orgShowByTopic'] = <<< TPL
<tr  class="[+cam_class+]">
<td  colspan="2"><strong><a href="index.php?id=11&orgid=[+cam_id+]">[+cam_name+] </a></strong>  &nbsp;<em>[+cam_status+]</em<br />
<span style="font-size: 0.8em;">c/o [+cam_contact_name+]<br />[+cam_loc_street+]<br />[+cam_loc_city+], [+am_loc_province+]  [+cam_loc_postal+]<br />[+cam_phone+]</span></td>
</tr>
<tr  class="[+cam_class+]"  style="font-size: 11px; padding: 4px;"><td>Topics: [+cam_topics+]</td><td style="text-transform: uppercase; font-size: 11px; padding: 4px;"><a href="/assetmap/index.php?id=13&orgid=[+cam_id+]">+&nbsp;Add</a></td></tr>
<tr class="table_separator" colspan="2"><td>&#160; </td></tr>
TPL;

$defTPL['orgShow'] = <<< TPL
<div class="org-wrapper">
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
</div>
TPL;

$defTPL['org_list'] = <<< TPL
<tr  class="[+cam_class+]">
<td  colspan="2"><strong><a href="index.php?id=11&orgid=[+cam_id+]">[+cam_name+] </a></strong>  &nbsp;<em>[+cam_status+]</em<br />
<span style="font-size: 0.8em;">c/o [+cam_contact_name+]<br />[+cam_loc_street+]<br />[+cam_loc_city+], [+am_loc_province+]  [+cam_loc_postal+]<br />[+cam_phone+]</span></td>
</tr>
<tr  class="[+cam_class+]"  style="font-size: 11px; padding: 4px;"><td>Topics: [+cam_topics+]</td><td style="text-transform: uppercase; font-size: 11px; padding: 4px;"><a href="/assetmap/index.php?id=13&orgid=[+cam_id+]">+&nbsp;Add</a></td></tr>
<tr class="table_separator" colspan="2"><td>&#160; </td></tr>
TPL;

$defTPL['hours'] = <<< TPL
<table class="table_hours" id="[+cam_id+]">
<tr>
<td> </td><td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td></tr>
<tr><td>Open</td><td class="hours" id="open_Sun">[+open_Sun+]</td><td class="hours" id="open_Mon">[+open_Mon+]</td><td class="hours" id="open_Tue">[+open_Tue+]</td>
<td class="hours" id="open_Wed">[+open_Wed+]</td><td class="hours" id="open_Thu">[+open_Thu+]</td><td class="hours" id="open_Fri">[+open_Fri+]</td>
<td class="hours" id="open_Sat">[+open_Sat+]</td></tr>
<tr><td>Closed</td><td class="hours" id="closed_Sun">[+closed_Sun+]</td><td class="hours" id="closed_Mon">[+closed_Mon+]</td><td class="hours" id="closed_Tue">[+closed_Tue+]</td><td class="hours" id="closed_Wed">[+closed_Wed+]</td><td class="hours" id="closed_Thu">[+closed_Thu+]</td><td class="hours" id="closed_Fri">[+closed_Fri+]</td><td class="hours" id="closed_Sat">[+closed_Sat+]</td></tr>
</table>
TPL;


?>
