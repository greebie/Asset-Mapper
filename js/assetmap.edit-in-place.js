/*
 *
 *    Asset map edit-in-place scripts and tiny.mce configuration file.
 *
 *    So far, this works for modx evolution, creating a click-and-edit
 *    script to add and remove data from the detailed assetmap section.
 *
 *    It works with an edit in place script to update / insert data.
 *
 *
 *
 */



$.fn.tinymce = function(options){  // add the tinyMCE editor to an edit box.
   return this.each(function(){
      tinyMCE.execCommand("mceAddControl", true, this.id);
   });
}

function initMCE(){   // set up tinyMCE with buttons etc.
   tinyMCE.init({mode : "none",
      plugins : "paste",
      theme : "advanced",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      theme_advanced_buttons1 : "bold,italic,underline,separator,pastetext,pasteword,selectall,separator,fontsizeselect,removeformat,separator,bullist,numlist,outdent,indent,separator,undo,redo",
      theme_advanced_buttons2 : "",
      theme_advanced_buttons3 : "",
      theme_advanced_resizing : true

});
}


initMCE();   // call the tinyMCE setup function
$.editable.addInputType('mce', {   // add an input type called 'mce' that adds a box with the tinyMCE editor included.
   element : function(settings, original) {
      var textarea = $('<textarea id="'+$(original).attr("id")+'_mce"/>');  // add _mce to all tinyMCE editor boxes.
      if (settings.rows) {
         textarea.attr('rows', settings.rows);
      } else {
         textarea.height(settings.height);
      }
      if (settings.cols) {
         textarea.attr('cols', settings.cols);
      } else {
         textarea.width(settings.width);
      }
      $(this).append(textarea);
         return(textarea);
      },
   plugin : function(settings, original) {
      tinyMCE.execCommand("mceAddControl", true, $(original).attr("id")+'_mce');
      },
   submit : function(settings, original) {
      tinyMCE.triggerSave();
      tinyMCE.execCommand("mceRemoveControl", true, $(original).attr("id")+'_mce');
      },
   reset : function(settings, original) {
      tinyMCE.execCommand("mceRemoveControl", true, $(original).attr("id")+'_mce');
      original.reset();
   }
});

$(function(){
 //  $('.field_note').tinymce();
var chum = $('h2.title').attr('id');  // gets the id from the question title for database purposes.

   $('.field_note').editable("assets/snippets/amapper/actions/ajax_editnotes.php", {  // file for managing data
      type : 'mce',
      submit : 'OK',
     submitdata: { 'spell' : chum},
      cancel: 'cancel',
      indicator : "Saving...",
      width : '600px',
      height : '150px'
   });
});

