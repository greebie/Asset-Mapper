$(document).ready(function() {

        $('#cfContact').keyup(function() {
            var contact = $('#cfContact').attr('value');

        if (contact.length >= 1){
            $.ajax({
             type: "GET",
             datatype: "json",
             url: "assets/snippets/amapper/actions/ajax.validate.php",
             data: 'contact=' + contact,
             error: function () {
                 $("#val_address").html("Something went wrong");
             },
             success: function (msg) {

                    if (msg == 'failed') {
                         $('#val_address').html('Contact Name: <img src=\"assets/images/icons/remove.png\" /> is not valid - Only letters and hyphen allowed in contact name.</p>');
                    }
                    else if (msg == 'success') {
                        $('#val_address').html('Contact Name: <img src=\"assets/images/icons/accept.png\" /> is valid!');
                    }
                    else {
                        $('#val_address').html('Contact Name: <img src=\"assets/images/icons/accept.png\" /> is valid!');
                        $('#warn_address').html('Contact Name: <img src=\"assets/images/icons/warning.png\" /><p class="caution">' +  msg + '<br />have the same contact name');
                    }

             }
    });
        }
    });

    $('#cfPostal').keyup(function() {

        var postal = $('#cfPostal').attr('value');
        if (postal.length >= 1){
            $.ajax({
             type: "GET",
             datatype: "json",
             url: "assets/snippets/amapper/actions/ajax.validate.php",
             data: 'postal=' + postal,
             error: function () {
                 $("#val_address").html("Something went wrong");
             },
             success: function (msg) {

                    if (msg == 'failed') {
                         $('#val_address').html('Postal Code: <img src=\"assets/images/icons/remove.png\" /> <p>Not a valid postal code</p>');
                    }
                    else if (msg == 'success') {
                        $('#val_address').html('Postal Code: <img src=\"assets/images/icons/accept.png\" /> is valid!');
                    }
                    else {
                        $('#val_address').html('Postal Code: <img src=\"assets/images/icons/accept.png\" /> is valid!');
                        $('#warn_address').html('Postal Code: <img src=\"assets/images/icons/warning.png\" /><p class="caution">' +  msg + '<br />have the same postal code');
                    }

             }
    });
        }
    });


    $('#cfName').keyup(function(event){
        var where = $('#cfName').attr('value');
        if (where.length >= 3) {
        $.ajax({
             type: "GET",
             datatype: "html",
             url: "assets/snippets/amapper/actions/ajax_validate.php",
             data: 'where=' + where,
             error: function () {
                 $("div#didyoumean").html("Something went wrong");
             },
             success: function (msg) {


             $("div#didyoumean").html("<h2>Did You Mean...</h2><p>(these are already in cam)</p>" + msg + "");
             }
        });
        }

    });

 });



