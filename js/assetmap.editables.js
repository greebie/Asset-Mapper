/*
 *
 * Editables script.   This manages all the possible ways that an item can be editable on the asset mapper.
 *
 */


$(document).ready(function() {
var organ = $('.table_hours').attr('id');    // for the hours data.   I've been thinking about losing this functionality.
var ship = $(this).text();
var contacts = $('.contacts').attr('id');    // for doing contact information.   It's in bad shape and could use work
$("div.comment").hide();                     // sets all comments to "hide" until clicked.
$(".comment_button").click(function() {      // SHOW vs HIDE comments script on detailed asset map page.
                           if ($(this).siblings("div.comment").is(":hidden")) {
                            $(this).siblings("div.comment").show("slow");
                            $(this).children(".show").replaceWith('<span class="show"> [hide] </span>');
}
else {
$(this).siblings("div.comment").hide("slow");
$(this).children(".show").replaceWith('<span class="show"> [show] </span>');
}
});

$(".show_form").click(function() {   //  pulls up the comment form.
    var noteid = $(this).attr('id');
$(this).after('<form class=\"comment_form\" style=\"display:none\" method=\"post\"><br />' +
                '<input type=\"hidden\" name=\"idnote\" id=\"idnote\" value=\"' + noteid + '\" />' +
                '<label>Subject: <input type=\"text\" size=\"50\" name=\"subject\" id=\"subject\" /></label>' +
                '<br /><label>Your Comment:<br /> <textarea cols=\"70\" rows=\"6\" name=\"comment\" id=\"comment\"></textarea></label><br />' +
                '</form>');
$("form.comment_form").show("slow");
$(this).siblings(".submit_comment").show();
$("button.show_form").hide();
});


$(".submit_comment").click(function() {  // comment submission script (does the magic AJAX-ey stuff)
var idnote = $("#idnote").attr("value");
var comment = $("#comment").val();
var subject = $("#subject").val();
    var dataString = 'idnote='+ idnote + '&comment=' + comment + '&subject=' + subject;
//alert (dataString);return false;
$.ajax({
  type: "POST",
  url: "assets/snippets/amapper/actions/ajax_submit.php",
  data: dataString,
  error: function() {
   $(this).siblings("div.message").append("<strong>Ooops!  Something went wrong.  :( </strong>");
   $("div.message").fadeOut(5000);

  },
  success: function() {

                      $(".show_form").show();
                    $("form.comment_form").slideUp(1000, function(){
                         $(this).siblings("div.message").append("<strong>Thanks!   Your comment has been submitted. (You will need to refresh to view).</strong>");

                       $("form.comment_form").remove();
                                      $("div.message").fadeOut(5000);
                    });

               $("button.submit_comment").hide();

}
});

});

$("#staff").editable("assets/snippets/amapper/actions/ajax_editnotes.php", {   //  number of staff form.
      type  : "select",
      data :  "{'0' : 'unknown', '1' : '0 to 1',  '2' : '2 to 5', '3' : '6 to 10', '4' : '11 to 20',  '5' : '21 to 50', '6' : '51 to 200', '7' : '200+'}",
      submitdata : {'ret' : ship, 'org' : organ},
     submit : 'OK'
});

$("#users").editable("assets/snippets/amapper/actions/ajax_editnotes.php", {   // number of users form.
      type  : "select",
      data :  "{'0' : 'unknown', '1' : '1 to 25',  '2' : '26 to 50', '3' : '51 to 100', '4' : '101 to 200',  '5' : '200+'}",
      submitdata : {'org' : organ},
      submit : 'OK'
});

$("#vols").editable("assets/snippets/amapper/actions/ajax_editnotes.php", {   // number of volunteers (not used I don't think)'
      type  : "select",
      data :  "{'0' : 'unknown', '1' : 'no volunteers',  '2' : '1-10%', '3' : '11-30%', '4' : '31-60%',  '5' : 'over 60%'}",
      submitdata : {'org' : organ},
      submit : 'OK'
});

 $(".hours").editable("assets/snippets/amapper/actions/ajax_editnotes.php", {  //  submit hours
        type       : "time",
        submitdata : {'org' : organ},
        submit     : "OK",
        style      : "display: inline",
        tooltip    : "Click to edit..."
});

 $(".con_name").editable("assets/snippets/amapper/actions/ajax_editnotes.php", {  // for the contact name
        submitdata : {'org' : contacts, 'contact' : 'name'},
        submit     : "OK",
        style      : "display: inline",
        tooltip    : "Click to edit..."
})

 });