// -------------------------------------------------REGISTRATION---------------------------------------------
$('input[type=radio][name=accountType]').change(function() {
    var val = $(this).val();
    if (val == 'seller') {
        $('.seller_fields').css('display', 'flex');
    } else { $('.seller_fields').hide(); }
});

jQuery(document).ready(function($) {
    $("#register_form").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var errors = 0;

        if ($('#register_form #username').val() == '') {
            $('#register_form #username').css('border', '1px solid red');
            errors++;
        }
        if ($('#register_form #first_name').val() == '') {
            $('#register_form #first_name').css('border', '1px solid red');
            errors++;
        }
        if ($('#register_form #last_name').val() == '') {
            $('#register_form #last_name').css('border', '1px solid red');
            errors++;
        }
        if ($('#register_form #password').val() == '') {
            $('#register_form #password').css('border', '1px solid red');
            errors++;
        }
        if ($('#register_form #passwordConfirmation').val() == '') {
            $('#register_form #passwordConfirmation').css('border', '1px solid red');
            errors++;
        }
        if ($('#register_form #phone_number').val() == '') {
            $('#register_form #phone_number').css('border', '1px solid red');
            errors++;
        }
        if ($('input[type=radio][name=accountType]').val() == 'seller') {
            if ($('#register_form #address_1').val() == '') {
                $('#register_form #address_1').css('border', '1px solid red');
                errors++;
            }
            if ($('#register_form #address_2').val() == '') {
                $('#register_form #address_2').css('border', '1px solid red');
                errors++;
            }
            if ($('#register_form #city').val() == '') {
                $('#register_form #city').css('border', '1px solid red');
                errors++;
            }
            if ($('#register_form #post_code').val() == '') {
                $('#register_form #post_code').css('border', '1px solid red');
                errors++;
            }
        }
        if (!validateEmail($('#register_form #email').val())) {
            errors++;
            $('#register_form #email').css('border', '1px solid red');
            errors++;
        } else { $('#register_form #email').css('border', '1px solid #ced4da'); }


        if (errors == 0) {
            if ($('#register_form #password').val() != $('#register_form #passwordConfirmation').val()) {
                errors++;
                $('#register_form #passwordConfirmation').css('border', '1px solid red');
                $('#register_form #password').css('border', '1px solid red');
            } else {
                $('#register_form #password').css('border', '1px solid #ced4da');
                $('#register_form #passwordConfirmation').css('border', '1px solid #ced4da');
            }
        }

        if (errors == 0) {

            var form = $(this);

            $.ajax({
                type: "POST",
                url: 'ajax/process_registration.php',
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    if (data.indexOf('ok') !== -1) {
                        window.location.replace("browse.php");
                    } //redirect after registation ok
                    else {
                        $('#register_form .results').html(data); // show response from the php script.
                        $('#register_form .results').show();
                    }
                }
            });
        } else {
            $('#register_form .results').html('Errors found');
            $('#register_form .results').show();
            if ($('#register_form #password').val() != $('#register_form #passwordConfirmation').val()) { $('#register_form .results').html('Passwords don\'t match!'); }
        }

    });

    // ----------------------------------------------------LOGIN--------------------------------------------------

    $("#loginform").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var errors = 0;

        if ($('#loginform #username').val() == '') {
            $('#loginform #username').css('border', '1px solid red');
            errors = errors + 1;
        }
        if ($('#loginform #password').val() == '') {
            $('#loginform #password').css('border', '1px solid red');
            errors = errors + 1;
        }

        if (errors == 0) {

            var form = $(this);

            $.ajax({
                type: "POST",
                url: 'ajax/login_result.php',
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    if (data.indexOf('ok') !== -1) { window.location.replace("index.php"); } //redirect after login ok
                    else {
                        $('#loginform .results').html(data); // show response from the php script
                        $('#loginform .results').show();

                    }
                }
            });
        }

    });


    // -----------------------------------------------------CREATE AUCTION-----------------------------------------------------------------

    $("#create_auction").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var errors = 0;

        if (errors == 0) {

            var formData = new FormData($('#create_auction')[0]);

            $.ajax({
                type: "POST",
                url: 'ajax/create_auction_result.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.indexOf('Auction') !== -1) {
                        var arr = data.split('<br>Auction id is ');
                        window.location.replace("auction_success.php?itemid=" + arr[1]);
                    } //redirect after registation ok
                    else {
                        $('#create_auction .results').html(data); // show response from the php script.
                    }
                }
            });
        } else {
            $('.results').html('Errors found');
        }

    });


    // --------------------------------------------------CLEAR ME BUTTON: IMAGE IN CREATE AUCTION------------------------------------------------

    $(".clear_me").click(function(e) {
        e.preventDefault();
        document.getElementById('formFile').value = null;
        frame.src = "";
        $('.clear_me').css('display', 'none');
        $('#frame').css('display', 'none');
    });

    // -------------------------------------------------ADD TO WATCHLIST-----------------------------------------------------------------------

    $(".addToTheWatchlist").click(function(e) {
        e.preventDefault();
        var auction_id = $('input[name=auction_id]').val();
        // This performs an asynchronous call to a PHP function using POST method.
        // Sends item ID as an argument to that function.
        $.ajax({
            type: "POST",
            url: 'watchlist.php',
            data: { 'add_to_watchlist': functionname, 'auction_id': auction_id },

            success: function(obj, textstatus) {
                // Callback function for when call is successful and returns obj
                console.log("Success");
                var objT = obj.trim();

                if (objT == "success") {
                    $("#watch_nowatch").hide();
                    $("#watch_watching").show();
                } else {
                    var mydiv = document.getElementById("watch_nowatch");
                    mydiv.appendChild(document.createElement("br"));
                    mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
                }
            },

            error: function(obj, textstatus) {
                console.log("Error");
            }
        }); // End of AJAX call

    });







    // -------------------------------------------------PLACE BID BUTTON-----------------------------------------------------------------------

    $(".place_the_bid").click(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var bid = $('#bid').val();
        var auction_id = $('input[name=auction_id]').val();


        $.ajax({
            type: "POST",
            url: 'ajax/place_bid.php',
            data: { 'bid_amount': bid, 'auction_id': auction_id },
            success: function(data) {
                if (data.indexOf('Success') !== -1) {
                    var arr = data.split('|');
                    $('.bid_result').html(arr[0]);
                    $('.current_bid1').html(arr[1]);
                    $('.bid_history_header').after(arr[2]);

                } else {

                    $('.bid_result').html(data);
                }
            }
        });

    });

});

// -----------------------------------------------------EMAIL VALIDATION---------------------------------------------------------
function validateEmail(email) {
    if (email.trim() == '') return false;
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test(email);
}


//-----------------------------------------------------PREVIEW IMAGE PLUS CLEAR IMAGE BUTTON-----------------------------------------------------
function preview() {
    $('#frame').css('display', 'inline');
    frame.src = URL.createObjectURL(event.target.files[0]);
    $('.clear_me').css('display', 'inline');
}


//-----------------------------------------------------DATE AND TIME SET UP SELECTOR ON CREATE AUCTION-----------------------------------------------------

jQuery(function() {
    jQuery('input[name="auction_duration"]').daterangepicker({
        minDate: moment().format('DD-MM-YYYY h:mm A'),
        timePicker: true,
        "autoApply": true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(48, 'hour'),
        "drops": "up",
        locale: {
            format: 'DD-M-Y hh:mm A'
        }
    });
});