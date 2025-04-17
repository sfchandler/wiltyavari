(function() {
    var widget, initAddressFinder = function() {
        widget = new AddressFinder.Widget(
            document.getElementById('address'),
            'DYM7UE36AWQV8F94PKJH',
            'AU', {
                "address_params": {
                    "gnaf,paf" : "1",
                }
            }
        );

        widget.on('result:select', function(fullAddress, metaData) {
            // need to update these ids to match those in your form
            document.getElementById("unit_no").value = metaData.unit_identifier;
            document.getElementById("street_number_1").value = metaData.street_number_1;
            document.getElementById("street_name").value = metaData.street;
            document.getElementById("suburb").value = metaData.locality_name;
            /*var state = "";
            switch (metaData.state_territory) {
                case "ACT":
                    state = "AUSTRALIAN CAPITAL TERRITORY";
                    break;
                case "NSW":
                    state = "NEW SOUTH WALES";
                    break;
                case "VIC":
                    state = "VICTORIA";
                    break;
                case "NT":
                    state = "NORTHERN TERRITORY";
                    break;
                case "QLD":
                    state = "QUEENSLAND";
                    break;
                case "SA":
                    state = "SOUTH AUSTRALIA";
                    break;
                case "TAS":
                    state = "TASMANIA";
                    break;
                case "WA":
                    state = "WESTERN AUSTRALIA";
                    break;
            }*/
            document.getElementById("state").value = metaData.state_territory;
            document.getElementById("postcode").value = metaData.postcode;

        });

    }

    function downloadAddressFinder() {
        var script = document.createElement('script');
        script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
        script.async = true;
        script.onload = initAddressFinder;
        document.body.appendChild(script);
    };

    document.addEventListener('DOMContentLoaded', downloadAddressFinder);
    let addr_1 = document.getElementById('address')
})();

$(document).ready(function () {
    /* AJAX loading animation */
    $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });
    /* -  end  -*/
    $.ajaxSetup({
        headers: {
            'CsrfToken': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /*$('.container').hide();
    $(document).on('click','#nextBtn', function (){
        var emCheck = $('#emCheck').val();
        $.ajax({
            url: "./emCheck.php",
            type: 'POST',
            dataType: 'text',
            data: {
                emCheck: emCheck
            },
            beforeSend: function(xhr, options){
                if (emCheck == '') {
                    alert('Please enter email address');
                    $('.container').hide();
                }
            },
            success: function (data) {
                console.log('emcheck'+data);
                if(data != 'FALSE') {
                    $('#empId').val(data);
                }
                if (emCheck != '') {
                    $('#email').val(emCheck);
                    $('.container').show();
                    $('#validateMail').hide();
                    $sigdiv.jSignature({ lineWidth: 1, height: 100 });
                }
            },
            error: function (jqXHR, exception) {
                if (jqXHR.status === 0) {
                    console.log('Not connect.\n Verify Network.');
                } else if (jqXHR.status == 404) {
                    console.log('Requested page not found. [404]');
                } else if (jqXHR.status == 500) {
                    console.log('Internal Server Error [500].');
                } else if (exception === 'parsererror') {
                    console.log('Requested JSON parse failed.');
                } else if (exception === 'timeout') {
                    console.log('Time out error.');
                } else if (exception === 'abort') {
                    console.log('Ajax request aborted.');
                } else {
                    console.log('Uncaught Error.\n' + jqXHR.responseText);
                }
            }
        }).done(function (data) {

        });
    });*/


    $('input[name="dob_selected"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: true,
        autoApply: false,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'), 10),
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    $('input[name="dob_selected"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#dob').val(picker.startDate.format('YYYY-MM-DD'));
        $('#dob_selected').val(picker.startDate.format('YYYY-MM-DD'));
    });
    $("input[name='jobActive']").change(function () {
        if ($("input[name='jobActive']:checked").val() == 'Yes') {
            $('#jobActiveDesc').val('');
            $('#jobActiveDesc').show();
        } else {
            $('#jobActiveDesc').val('');
            $('#jobActiveDesc').hide();
        }
    });
    $('input[name="crimeDate1"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="crimeDate1"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#crimeDate1').val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('input[name="crimeDate2"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="crimeDate2"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#crimeDate2').val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('input[name="visaExpiry"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="visaExpiry"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#visaExpiry').val(picker.startDate.format('YYYY-MM-DD'));
    });
    /*********** file upload ***************/
    var options = {
        beforeSend: function () {
            $("#passportprogress").show();
            $("#passportbar").width('0%');
            $("#passportmessage").html("");
            $("#passportpercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#passportbar").width(percentComplete + '%');
            $("#passportpercent").html(percentComplete + '%');

        },
        success: function () {
            $("#passportbar").width('100%');
            $("#passportpercent").html('100%');

        },
        complete: function (response) {
            console.log('response>>>>>>>>>>' + response.responseText);
            if (response.responseText != 'Error Uploading') {
                $('#passportFileSubmitted').val(response.responseText);
            } else {
                $("#passportmessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#passportmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmPassport").ajaxForm(options);

    var birthoptions = {
        beforeSend: function () {
            $("#birthprogress").show();
            $("#birthbar").width('0%');
            $("#birthmessage").html("");
            $("#birthpercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#birthbar").width(percentComplete + '%');
            $("#birthpercent").html(percentComplete + '%');

        },
        success: function () {
            $("#birthbar").width('100%');
            $("#birthpercent").html('100%');

        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#birthFileSubmitted').val(response.responseText);
            } else {
                $("#birthmessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#birthmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmBirth").ajaxForm(birthoptions);

    var citizenoptions = {
        beforeSend: function () {
            $("#citizenprogress").show();
            $("#citizenbar").width('0%');
            $("#citizenmessage").html("");
            $("#citizenpercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#citizenbar").width(percentComplete + '%');
            $("#citizenpercent").html(percentComplete + '%');

        },
        success: function () {
            $("#citizenbar").width('100%');
            $("#citizenpercent").html('100%');

        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#citizenFileSubmitted').val(response.responseText);
            } else {
                $("#citizenmessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#citizenmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmCitizen").ajaxForm(citizenoptions);

    var drivingoptions = {
        beforeSend: function () {
            $("#drivingprogress").show();
            $("#drivingbar").width('0%');
            $("#drivingmessage").html("");
            $("#drivingpercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#drivingbar").width(percentComplete + '%');
            $("#drivingpercent").html(percentComplete + '%');

        },
        success: function () {
            $("#drivingbar").width('100%');
            $("#drivingpercent").html('100%');

        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#drivingFileSubmitted').val(response.responseText);
            } else {
                $("#drivingmessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#drivingmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmDriving").ajaxForm(drivingoptions);

    var medicareoptions = {
        beforeSend: function () {
            $("#medicareprogress").show();
            $("#medicarebar").width('0%');
            $("#medicaremessage").html("");
            $("#medicarepercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#medicarebar").width(percentComplete + '%');
            $("#medicarepercent").html(percentComplete + '%');

        },
        success: function () {
            $("#medicarebar").width('100%');
            $("#medicarepercent").html('100%');
        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#medicareFileSubmitted').val(response.responseText);
            } else {
                $("#medicaremessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#medicaremessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmMedicare").ajaxForm(medicareoptions);

    var studentoptions = {
        beforeSend: function () {
            $("#studentprogress").show();
            $("#studentbar").width('0%');
            $("#studentmessage").html("");
            $("#studentpercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#studentbar").width(percentComplete + '%');
            $("#studentpercent").html(percentComplete + '%');

        },
        success: function () {
            $("#studentbar").width('100%');
            $("#studentpercent").html('100%');
        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#studentFileSubmitted').val(response.responseText);
            } else {
                $("#studentmessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#studentmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmStudent").ajaxForm(studentoptions);

    var policeoptions = {
        beforeSend: function () {
            $("#policeprogress").show();
            $("#policebar").width('0%');
            $("#policemessage").html("");
            $("#policepercent").html("0%");
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $("#policebar").width(percentComplete + '%');
            $("#policepercent").html(percentComplete + '%');

        },
        success: function () {
            $("#policebar").width('100%');
            $("#policepercent").html('100%');
        },
        complete: function (response) {
            if (response.responseText != 'Error Uploading') {
                $('#policeFileSubmitted').val(response.responseText);
            } else {
                $("#policemessage").html("<font color='green'>" + response.responseText + "</font>");
            }
        },
        error: function () {
            $("#policemessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmPolice").ajaxForm(policeoptions);

    /*===================== uploads according to resident status=========================== */

    /*$(document).on('click','.rsStatus', function(){
        if($(this).val() === 'Australian Citizen'){
            $('#passportFile')
        }else if($(this).val() === 'Australian Permanent Resident'){

        }else if(($(this).val() === 'Working Visa') || ($(this).val() === 'Temporary Resident')){

        }
    });*/


    /*===================== end =========================== */
    var $sigdiv = $("#signature");
    $(document).on('click', '#reset', function () {
        $sigdiv.jSignature("reset");
    });
    $sigdiv.jSignature({
        'UndoButton': true, 'background-color': 'transparent',
        'decor-color': 'transparent'
    });
    /*,
    'background-color': 'transparent',
        'decor-color': 'transparent',
        */
    $sigdiv.jSignature("reset");
    // Getting signature as SVG and rendering the SVG within the browser.
    // (!!! inline SVG rendering from IMG element does not work in all browsers !!!)
    // this export plugin returns an array of [mimetype, base64-encoded string of SVG of the signature strokes]
    $("#signature").on('change', function (e) {
        $("#imgSig").html('');
        var datapair = $sigdiv.jSignature("getData", "image");
        var i = new Image();
        i.id = 'signatureImg';
        i.src = "data:" + datapair[0] + "," + datapair[1];
        $(i).appendTo($("#imgSig")); // append the image (SVG) to DOM.
    });
    // Getting signature as "base30" data pair
    // array of [mimetype, string of jSIgnature"s custom Base30-compressed format]
    //datapair = $sigdiv.jSignature("getData","base30");
    // reimporting the data into jSignature.
    // import plugins understand data-url-formatted strings like "data:mime;encoding,data"
    //$sigdiv.jSignature("setData", "data:" + datapair.join(","));
    $('#jobActiveDesc').hide();
    $('#medConditionDesc').hide();
    $('#psycoConditionDesc').hide();
    $('#alergyConditionDesc').hide();
    $('#medicalTreatmentDesc').hide();
    $('#drowsinessConditionDesc').hide();
    $('#chronicConditionDesc').hide();
    $('#workInjuryDesc').hide();
    $('#surgeryInformationDesc').hide();
    $("input[name='medicalCondition']").change(function () {
        if ($("input[name='medicalCondition']:checked").val() == 'Yes') {
            $('#medConditionDesc').val('');
            $('#medConditionDesc').show();
        } else {
            $('#medConditionDesc').val('');
            $('#medConditionDesc').hide();
        }
    });
    $("input[name='psycoCondition']").change(function () {
        if ($("input[name='psycoCondition']:checked").val() == 'Yes') {
            $('#psycoConditionDesc').val('');
            $('#psycoConditionDesc').show();
        } else {
            $('#psycoConditionDesc').val('');
            $('#psycoConditionDesc').hide();
        }
    });
    $("input[name='alergyCondition']").change(function () {
        if ($("input[name='alergyCondition']:checked").val() == 'Yes') {
            $('#alergyConditionDesc').val('');
            $('#alergyConditionDesc').show();
        } else {
            $('#alergyConditionDesc').val('');
            $('#alergyConditionDesc').hide();
        }
    });
    $("input[name='medicalTreatment']").change(function () {
        if ($("input[name='medicalTreatment']:checked").val() == 'Yes') {
            $('#medicalTreatmentDesc').val('');
            $('#medicalTreatmentDesc').show();
        } else {
            $('#medicalTreatmentDesc').val('');
            $('#medicalTreatmentDesc').hide();
        }
    });
    $("input[name='drowsinessCondition']").change(function () {
        if ($("input[name='drowsinessCondition']:checked").val() == 'Yes') {
            $('#drowsinessConditionDesc').val('');
            $('#drowsinessConditionDesc').show();
        } else {
            $('#drowsinessConditionDesc').val('');
            $('#drowsinessConditionDesc').hide();
        }
    });
    $("input[name='chronicCondition']").change(function () {
        if ($("input[name='chronicCondition']:checked").val() == 'Yes') {
            $('#chronicConditionDesc').val('');
            $('#chronicConditionDesc').show();
        } else {
            $('#chronicConditionDesc').val('');
            $('#chronicConditionDesc').hide();
        }
    });
    $("input[name='workInjury']").change(function () {
        if ($("input[name='workInjury']:checked").val() == 'Yes') {
            $('#workInjuryDesc').val('');
            $('#workInjuryDesc').show();
        } else {
            $('#workInjuryDesc').val('');
            $('#workInjuryDesc').hide();
        }
    });
    $("input[name='jobActive']").change(function () {
        if ($("input[name='jobActive']:checked").val() == 'Yes') {
            $('#jobActiveDesc').val('');
            $('#jobActiveDesc').show();
        } else {
            $('#jobActiveDesc').val('');
            $('#jobActiveDesc').hide();
        }
    });
    $("input[name='surgeryInformation']").change(function () {
        if ($("input[name='surgeryInformation']:checked").val() == 'Yes') {
            $('#surgeryInformationDesc').val('');
            $('#surgeryInformationDesc').show();
        } else {
            $('#surgeryInformationDesc').val('');
            $('#surgeryInformationDesc').hide();
        }
    });
    $('#bsb').keyup(function () {
        var bsb = $(this).val().split("-").join(""); // remove hyphens
        if (bsb.length > 0) {
            bsb = bsb.match(new RegExp('.{1,3}', 'g')).join("-");
        }
        $(this).val(bsb);
    });
    $(document).on('change', '#supercheck', function () {
        if ($("#supercheck").is(':checked')) {
            $('.nosuper').hide();
            $('#superAccountName').hide();
            $('#superFundName').hide();
            $('#superMembershipNo').hide();
            $('#superFundAddress').hide();
            $('#superPhoneNo').hide();
            $('#superWebsite').hide();
            $('#superFundABN').hide();
            $('#superFundUSI').hide();
        } else {
            $('.nosuper').show();
            $('#superAccountName').show();
            $('#superFundName').show();
            $('#superMembershipNo').show();
            $('#superFundAddress').show();
            $('#superPhoneNo').show();
            $('#superWebsite').show();
            $('#superFundABN').show();
            $('#superFundUSI').show();
        }
    });
    /*document.getElementById('firstName').onkeypress = function () {
        if (event.keyCode === 39) { // apostrophe
            return false;
        }
    };*/
    $('input[type="text"]').change(function () {
        this.value = $.trim(this.value);
    });

    $('.alphaonly').bind('keyup blur', function () {
            var node = $(this);
            node.val(node.val().replace(/[^a-zA-Z]/g, ''));
        }
    );
    $('.authFrm').hide();
    $('.statdec').hide();
    $('#policeFileDisplay').hide();
    $('#crimeTbl').hide();
    $('#fit2wrk').hide();
    $('#crimeHistory').hide();
    $('#vsExp').hide();
    $(document).on('click', '.rsStatus', function (evt) {
        $("label.error").hide();
        $(".error").removeClass("error");
        let residentStatus = $('input[name=residentStatus]:checked', '#frmJotForm').val();
        if ((residentStatus === 'Australian Citizen') || (residentStatus === 'Australian Permanent Resident')) {
            $('.authFrm').show();
            $('#vsExp').hide();
        } else if ((residentStatus === 'Working Visa') || (residentStatus === 'Temporary Resident') || (residentStatus === 'Student Visa')) {
            $('#vsExp').show();
            $('.authFrm').hide();
            $('#fit2wrk').find('input:text').val('');
        } else {
            $('#visaExpiry').hide();
            $('.authFrm').hide();
            $('#fit2wrk').find('input:text').val('');
        }
    });
    $(document).on('click', '.policeCheck', function (evt) {
        let residentStatus = $('input[name=residentStatus]:checked', '#frmJotForm').val();
        if ($('input[name=policeCheck]:checked', '#frmJotForm').val() === 'No') {
            $('.statName').text($('#firstName').val() + ' ' + $('#lastName').val());
            $('.statAddress').text($('textarea#address').val());
            $('.statdec').show();
            $('#policeFileDisplay').hide();
            $('#crimeHistory').show();
            if ((residentStatus === 'Australian Citizen') || (residentStatus === 'Australian Permanent Resident')) {
                $('.authFrm').show();
            }
        } else {
            $('.authFrm').hide();
            $('#fit2wrk').find('input:text').val('');
            $('.statdec').hide();
            $('#crimeHistory').hide();
            $('.crimeCheck').attr('checked', false);
            $('#crimeDate1').val('');
            $('#crime1').val('');
            $('#crimeDate2').val('');
            $('#crime2').val('');
            $('#policeFileDisplay').show();
        }
    });

    $(document).on('click', '.crimeCheck', function (evt) {
        if ($('input[name=crimeCheck]:checked', '#frmJotForm').val() === 'Yes') {
            $('.authFrm').show();
            $('#fit2wrk').show();
            $('#crimeTbl').show();
            $('.statdec').hide();
        } else {
            $('.authFrm').show();
            $('#fit2wrk').show();
            $('#crimeTbl').show();
            $('.statdec').show();
            $('#fit2wrk').find('input:text').val('');
        }
    });

    $(document).on('click', '.optionChk', function (evt) {
        if ($('input[name=optionChk]:checked', '#frmJotForm').val() === 'option2') {
            if ($('input[name=policeCheck]:checked', '#frmJotForm').val() === 'No') {
                $('#fit2wrk').show();
            } else {
                $('#fit2wrk').hide();
                $('#fit2wrk').find('input:text').val('');
            }
        } else {
            $('#fit2wrk').hide();
            $('#fit2wrk').find('input:text').val('');
        }
    });
    jQuery.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[\w.]+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    $(document).on('click', '.regBtn', function (evt) {
        var jotform = $('#frmJotForm').validate({
            rules: {
                firstName: {
                    required: true,
                },
                lastName: {
                    required: true,
                },
                dob: {
                    required: true
                },
                address: {
                    required: true,
                },
                street_number_1: {
                    required: true,
                    alphanumeric: true
                },
                street_name: {
                    required: true,
                },
                suburb: {
                    required: true
                },
                state: {
                    required: true
                },
                postcode: {
                    required: true
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                email: {
                    required: true
                },
                jobActive: {
                    required: true
                },
                jobActiveDesc: {
                    required: function (element) {
                        return $("input[name='jobActive']:checked").val() == 'Yes';
                    }
                },
                residentStatus: {
                    required: true
                },
                policeCheck: {
                    required: true
                },
                crimeCheck:{
                    required: function (element) {
                        return $("input[name='policeCheck']:checked").val() == 'No';
                    }    
                },
                passportFile: {
                    required: function (element) {
                        if ($("#Citizen").is(':checked')) {
                            return true;
                        }else if ($("#PR").is(':checked')) {
                            return true;
                        } else if ($("#WorkingVisa").is(':checked')) {
                            return true;
                        } else if ($("#TemporaryResident").is(':checked')) {
                            return true;
                        } else if ($("#Student").is(':checked')) {
                            return true;
                        }
                    }
                },
                drivingFile: {
                    required: function (element) {
                        if ($("#Citizen").is(':checked')) {
                            return true;
                        }else if ($("#PR").is(':checked')) {
                            return true;
                        }else if ($("#Student").is(':checked')) {
                            return true;
                        }
                    }
                },
                medicareFile: {
                    required: function (element) {
                        if ($("#Citizen").is(':checked')) {
                            return true;
                        } else if ($("#PR").is(':checked')) {
                            return true;
                        }
                    }
                },
                policeFile: {
                    required: function (element) {
                        return $("input[name='policeCheck']:checked").val() == 'Yes';
                    }
                },
                statOccupation: {
                    required: function (element) {
                        return $("input[name='policeCheck']:checked").val() == 'No';
                    }
                },
                crimeDate1: {
                    required: function (element) {
                        return $("input[name='crimeCheck']:checked").val() == 'Yes';
                    }
                },
                crime1: {
                    required: function (element) {
                        return $("input[name='crimeCheck']:checked").val() == 'Yes';
                    }
                },
                visaExpiry: {
                    required: function (element) {
                        if ($("#WorkingVisa").is(':checked')) {
                            return true;
                        } else if ($("#TemporaryResident").is(':checked')) {
                            return true;
                        } else if ($("#Student").is(':checked')) {
                            return true;
                        }
                    }
                },
                optionChk: {
                    required: true
                },
                pb_suburb: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                pb_state: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                pb_country: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_first_name: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_middle_name: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_last_name: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_type: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_street_number1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_street_name1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_suburb1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_state1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_postcode1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_country1: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_licence: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_licence_state: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_passport_no: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_passport_country: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                fw_passport_type: {
                    required: function (element) {
                        return $("input[name='optionChk']:checked").val() == 'option2';
                    }
                },
                video_check1:{
                    required: true
                },
                video_check2:{
                    required: true
                },
                video_check3:{
                    required: true
                },
                video_check4:{
                    required: true
                },
                partnerName: {
                    required: true
                },
                relationship: {
                    required: true
                },
                partnerMobile: {
                    required: true
                },
                referee1Name: {
                    required: true
                },
                referee1CompanyName: {
                    required: true
                },
                referee1Position: {
                    required: true
                },
                referee1Mobile: {
                    required: true
                },
                referee2Name: {
                    required: true
                },
                referee2CompanyName: {
                    required: true
                },
                referee2Position: {
                    required: true
                },
                referee2Mobile: {
                    required: true
                },
                bankAccountName: {
                    required: true
                },
                bankName: {
                    required: true
                },
                bsb: {
                    required: true
                },
                bankAccountNumber: {
                    required: true
                },
                tfn: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 9
                },
                paidBasis: {
                    required: true,
                },
                taxClaim: {
                    required: true,
                },
                taxHelp: {
                    required: true,
                },
                taxResident: {
                    required: true,
                },
                superAccountName: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superFundName: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superMembershipNo: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superFundAddress: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superPhoneNo: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superWebsite: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superFundABN: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                superFundUSI: {
                    required: function (element) {
                        if ($("#supercheck").is(':checked')) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                },
                medicalCondition: {
                    required: true
                },
                medConditionDesc: {
                    required: function (element) {
                        return $("input[name='medicalCondition']:checked").val() == 'Yes';
                    }
                },
                psycoCondition: {
                    required: true
                },
                psycoConditionDesc: {
                    required: function (element) {
                        return $("input[name='psycoCondition']:checked").val() == 'Yes';
                    }
                },
                alergyCondition: {
                    required: true
                },
                alergyConditionDesc: {
                    required: function (element) {
                        return $("input[name='alergyCondition']:checked").val() == 'Yes';
                    }
                },
                pregnantCondition: {
                    required: true
                },
                shoulderCondition: {
                    required: true
                },
                armCondition: {
                    required: true
                },
                strainCondition: {
                    required: true
                },
                epilepsyCondition: {
                    required: true
                },
                hearingCondition: {
                    required: true
                },
                stressCondition: {
                    required: true
                },
                fatiqueCondition: {
                    required: true
                },
                asthmaCondition: {
                    required: true
                },
                arthritisCondition: {
                    required: true
                },
                dizzinessCondition: {
                    required: true
                },
                headCondition: {
                    required: true
                },
                speechCondition: {
                    required: true
                },
                backCondition: {
                    required: true
                },
                kneeCondition: {
                    required: true
                },
                persistentCondition: {
                    required: true
                },
                skinCondition: {
                    required: true
                },
                stomachStrains: {
                    required: true
                },
                visionCondition: {
                    required: true
                },
                boneCondition: {
                    required: true
                },
                bloodCondition: {
                    required: true
                },
                lungCondition: {
                    required: true
                },
                surgeryInformation: {
                    required: true
                },
                surgeryInformationDesc:{
                    required: function (element) {
                        return $("input[name='surgeryInformation']:checked").val() == 'Yes';
                    }
                },
                stomachCondition: {
                    required: true
                },
                heartCondition: {
                    required: true
                },
                infectiousCondition: {
                    required: true
                },
                medicalTreatment: {
                    required: true
                },
                medicalTreatmentDesc: {
                    required: function (element) {
                        return $("input[name='medicalTreatment']:checked").val() == 'Yes';
                    }
                },
                drowsinessCondition: {
                    required: true
                },
                drowsinessConditionDesc: {
                    required: function (element) {
                        return $("input[name='drowsinessCondition']:checked").val() == 'Yes';
                    }
                },
                chronicCondition: {
                    required: true
                },
                chronicConditionDesc: {
                    required: function (element) {
                        return $("input[name='chronicCondition']:checked").val() == 'Yes';
                    }
                },
                workInjury: {
                    required: true
                },
                workInjuryDesc: {
                    required: function (element) {
                        return $("input[name='workInjury']:checked").val() == 'Yes';
                    }
                },
                crouchingCondition: {
                    required: true
                },
                sittingCondition: {
                    required: true
                },
                workShoulderHeight: {
                    required: true
                },
                hearingConversation: {
                    required: true
                },
                workAtHeights: {
                    required: true
                },
                groundCondition: {
                    required: true
                },
                handlingFood: {
                    required: true
                },
                shiftWork: {
                    required: true
                },
                standingMinutes: {
                    required: true
                },
                liftingCondition: {
                    required: true
                },
                grippingObjects: {
                    required: true
                },
                repetitiveMovement: {
                    required: true
                },
                walkingStairs: {
                    required: true
                },
                handTools: {
                    required: true
                },
                protectiveEquipment: {
                    required: true
                },
                workConfinedSpaces: {
                    required: true
                },
                workHotColdEnvironment: {
                    required: true
                },
                covid19File1: {
                    required: true
                }
            },
            messages: {
                mobile: {
                    required: "Required input",
                    minlength: "Please enter at least {10} digits"
                },
                tfn: {
                    required: "Required input",
                    minlength: "Please enter all {9} digits"
                },
                video_check: {
                    required: "Please watch the videos and tick the checkbox here"
                }
            },
            submitHandler: function (form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($sigdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                } else {
                    var formData = new FormData(form);
                    formData.append('passportFile', $('#passportFile')[0].files[0]);
                    formData.append('birthFile', $('#birthFile')[0].files[0]);
                    formData.append('citizenFile', $('#citizenFile')[0].files[0]);
                    formData.append('drivingFile', $('#drivingFile')[0].files[0]);
                    formData.append('medicareFile', $('#medicareFile')[0].files[0]);
                    formData.append('studentFile', $('#studentFile')[0].files[0]);
                    formData.append('policeFile', $('#policeFile')[0].files[0]);
                    formData.append('profileFile', $('#profileFile')[0].files[0]);
                    formData.append('whiteFile', $('#whiteFile')[0].files[0]);
                    formData.append('forkliftFile', $('#forkliftFile')[0].files[0]);
                    formData.append('covid19File1', $('#covid19File1')[0].files[0]);
                    formData.append('covid19File2', $('#covid19File2')[0].files[0]);
                    formData.append('covid19File3', $('#covid19File3')[0].files[0]);

                    formData.append('empId', $('#empId').val());
                    formData.append('imageSrc', $("#signatureImg").attr('src'));
                    formData.append('title', $.base64.encode($('#title :selected').val()));
                    formData.append('paidBasis', $.base64.encode($('input[name=paidBasis]:checked', '#frmJotForm').val()));
                    formData.append('taxClaim', $.base64.encode($('input[name=taxClaim]:checked', '#frmJotForm').val()));
                    formData.append('taxHelp', $.base64.encode($('input[name=taxHelp]:checked', '#frmJotForm').val()));
                    formData.append('taxResident', $.base64.encode($('input[name=taxResident]:checked', '#frmJotForm').val()));
                    formData.append('firstName', $.base64.encode($('#firstName').val()));
                    formData.append('middleName', $.base64.encode($('#middleName').val()));
                    formData.append('lastName', $.base64.encode($('#lastName').val()));
                    formData.append('gender', $.base64.encode($('#gender :selected').val()));
                    formData.append('dob', $.base64.encode($('#dob').val()));
                    formData.append('address', $.base64.encode($('textarea#address').val()));
                    formData.append('unit_no', $.base64.encode($('#unit_no').val()));
                    formData.append('street_number_1', $.base64.encode($('#street_number_1').val()));
                    formData.append('street_name', $.base64.encode($('#street_name').val()));
                    formData.append('suburb', $.base64.encode($('#suburb').val()));
                    formData.append('state', $.base64.encode($('#state').val()));
                    formData.append('postcode', $.base64.encode($('#postcode').val()));
                    formData.append('mobile', $.base64.encode($('#mobile').val()));
                    formData.append('email', $.base64.encode($('#email').val()));
                    formData.append('jobActive', $.base64.encode($('input[name=jobActive]:checked', '#frmJotForm').val()));
                    formData.append('jobActiveDesc', $.base64.encode($('textarea#jobActiveDesc').val()));
                    formData.append('residentStatus', $.base64.encode($('input[name=residentStatus]:checked', '#frmJotForm').val()));
                    formData.append('emcName', $.base64.encode($('#emcName').val()));
                    formData.append('emcRelationship', $.base64.encode($('#emcRelationship').val()));
                    formData.append('emcMobile', $.base64.encode($('#emcMobile').val()));
                    formData.append('emcHomePhone', $.base64.encode($('#emcHomePhone').val()));
                    formData.append('referee1Name', $.base64.encode($('#referee1Name').val()));
                    formData.append('referee1CompanyName', $.base64.encode($('#referee1CompanyName').val()));
                    formData.append('referee1Position', $.base64.encode($('#referee1Position').val()));
                    formData.append('referee1Relationship', $.base64.encode($('#referee1Relationship').val()));
                    formData.append('referee1Mobile', $.base64.encode($('#referee1Mobile').val()));
                    formData.append('referee2Name', $.base64.encode($('#referee2Name').val()));
                    formData.append('referee2CompanyName', $.base64.encode($('#referee2CompanyName').val()));
                    formData.append('referee2Position', $.base64.encode($('#referee2Position').val()));
                    formData.append('referee2Relationship', $.base64.encode($('#referee2Relationship').val()));
                    formData.append('referee2Mobile', $.base64.encode($('#referee2Mobile').val()));
                    formData.append('bankAccountName', $.base64.encode($('#bankAccountName').val()));
                    formData.append('bankName', $.base64.encode($('#bankName').val()));
                    formData.append('bsb', $.base64.encode($('#bsb').val()));
                    formData.append('bankAccountNumber', $.base64.encode($('#bankAccountNumber').val()));
                    formData.append('tfn', $.base64.encode($('#tfn').val()));
                    formData.append('superAccountName', $.base64.encode($('#superAccountName').val()));
                    formData.append('superFundName', $.base64.encode($('#superFundName').val()));
                    formData.append('superMembershipNo', $.base64.encode($('#superMembershipNo').val()));
                    formData.append('superFundAddress', $.base64.encode($('#superFundAddress').val()));
                    formData.append('superPhoneNo', $.base64.encode($('#superPhoneNo').val()));
                    formData.append('superWebsite', $.base64.encode($('#superWebsite').val()));
                    formData.append('superFundABN', $.base64.encode($('#superFundABN').val()));
                    formData.append('superFundUSI', $.base64.encode($('#superFundUSI').val()));
                    formData.append('medicalCondition', $.base64.encode($('input[name=medicalCondition]:checked', '#frmJotForm').val()));
                    formData.append('medConditionDesc', $.base64.encode($('textarea#medConditionDesc').val()));
                    formData.append('psycoCondition', $.base64.encode($('input[name=psycoCondition]:checked', '#frmJotForm').val()));
                    formData.append('psycoConditionDesc', $.base64.encode($('textarea#psycoConditionDesc').val()));
                    formData.append('alergyCondition', $.base64.encode($('input[name=alergyCondition]:checked', '#frmJotForm').val()));
                    formData.append('alergyConditionDesc', $.base64.encode($('textarea#alergyConditionDesc').val()));
                    formData.append('pregnantCondition', $.base64.encode($('input[name=pregnantCondition]:checked', '#frmJotForm').val()));
                    formData.append('shoulderCondition', $.base64.encode($('input[name=shoulderCondition]:checked', '#frmJotForm').val()));
                    formData.append('armCondition', $.base64.encode($('input[name=armCondition]:checked', '#frmJotForm').val()));
                    formData.append('strainCondition', $.base64.encode($('input[name=strainCondition]:checked', '#frmJotForm').val()));
                    formData.append('epilepsyCondition', $.base64.encode($('input[name=epilepsyCondition]:checked', '#frmJotForm').val()));
                    formData.append('hearingCondition', $.base64.encode($('input[name=hearingCondition]:checked', '#frmJotForm').val()));
                    formData.append('stressCondition', $.base64.encode($('input[name=stressCondition]:checked', '#frmJotForm').val()));
                    formData.append('fatiqueCondition', $.base64.encode($('input[name=fatiqueCondition]:checked', '#frmJotForm').val()));
                    formData.append('asthmaCondition', $.base64.encode($('input[name=asthmaCondition]:checked', '#frmJotForm').val()));
                    formData.append('arthritisCondition', $.base64.encode($('input[name=arthritisCondition]:checked', '#frmJotForm').val()));
                    formData.append('dizzinessCondition', $.base64.encode($('input[name=dizzinessCondition]:checked', '#frmJotForm').val()));
                    formData.append('headCondition', $.base64.encode($('input[name=headCondition]:checked', '#frmJotForm').val()));
                    formData.append('speechCondition', $.base64.encode($('input[name=speechCondition]:checked', '#frmJotForm').val()));
                    formData.append('backCondition', $.base64.encode($('input[name=backCondition]:checked', '#frmJotForm').val()));
                    formData.append('kneeCondition', $.base64.encode($('input[name=kneeCondition]:checked', '#frmJotForm').val()));
                    formData.append('persistentCondition', $.base64.encode($('input[name=persistentCondition]:checked', '#frmJotForm').val()));
                    formData.append('skinCondition', $.base64.encode($('input[name=skinCondition]:checked', '#frmJotForm').val()));
                    formData.append('stomachStrains', $.base64.encode($('input[name=stomachStrains]:checked', '#frmJotForm').val()));
                    formData.append('visionCondition', $.base64.encode($('input[name=visionCondition]:checked', '#frmJotForm').val()));
                    formData.append('boneCondition', $.base64.encode($('input[name=boneCondition]:checked', '#frmJotForm').val()));
                    formData.append('bloodCondition', $.base64.encode($('input[name=bloodCondition]:checked', '#frmJotForm').val()));
                    formData.append('lungCondition', $.base64.encode($('input[name=lungCondition]:checked', '#frmJotForm').val()));
                    formData.append('surgeryInformation', $.base64.encode($('input[name=surgeryInformation]:checked', '#frmJotForm').val()));
                    formData.append('surgeryInformationDesc', $.base64.encode($('textarea#surgeryInformationDesc').val()));
                    formData.append('stomachCondition', $.base64.encode($('input[name=stomachCondition]:checked', '#frmJotForm').val()));
                    formData.append('heartCondition', $.base64.encode($('input[name=heartCondition]:checked', '#frmJotForm').val()));
                    formData.append('infectiousCondition', $.base64.encode($('input[name=infectiousCondition]:checked', '#frmJotForm').val()));
                    formData.append('medicalTreatment', $.base64.encode($('input[name=medicalTreatment]:checked', '#frmJotForm').val()));
                    formData.append('medicalTreatmentDesc', $.base64.encode($('textarea#medicalTreatmentDesc').val()));
                    formData.append('drowsinessCondition', $.base64.encode($('input[name=drowsinessCondition]:checked', '#frmJotForm').val()));
                    formData.append('drowsinessConditionDesc', $.base64.encode($('textarea#drowsinessConditionDesc').val()));
                    formData.append('chronicCondition', $.base64.encode($('input[name=chronicCondition]:checked', '#frmJotForm').val()));
                    formData.append('chronicConditionDesc', $.base64.encode($('textarea#chronicConditionDesc').val()));
                    formData.append('workInjury', $.base64.encode($('input[name=workInjury]:checked', '#frmJotForm').val()));
                    formData.append('workInjuryDesc', $.base64.encode($('textarea#workInjuryDesc').val()));
                    formData.append('crouchingCondition', $.base64.encode($('input[name=crouchingCondition]:checked', '#frmJotForm').val()));
                    formData.append('sittingCondition', $.base64.encode($('input[name=sittingCondition]:checked', '#frmJotForm').val()));
                    formData.append('workShoulderHeight', $.base64.encode($('input[name=workShoulderHeight]:checked', '#frmJotForm').val()));
                    formData.append('hearingConversation', $.base64.encode($('input[name=hearingConversation]:checked', '#frmJotForm').val()));
                    formData.append('workAtHeights', $.base64.encode($('input[name=workAtHeights]:checked', '#frmJotForm').val()));
                    formData.append('groundCondition', $.base64.encode($('input[name=groundCondition]:checked', '#frmJotForm').val()));
                    formData.append('handlingFood', $.base64.encode($('input[name=handlingFood]:checked', '#frmJotForm').val()));
                    formData.append('shiftWork', $.base64.encode($('input[name=shiftWork]:checked', '#frmJotForm').val()));
                    formData.append('standingMinutes', $.base64.encode($('input[name=standingMinutes]:checked', '#frmJotForm').val()));
                    formData.append('liftingCondition', $.base64.encode($('input[name=liftingCondition]:checked', '#frmJotForm').val()));
                    formData.append('grippingObjects', $.base64.encode($('input[name=grippingObjects]:checked', '#frmJotForm').val()));
                    formData.append('repetitiveMovement', $.base64.encode($('input[name=repetitiveMovement]:checked', '#frmJotForm').val()));
                    formData.append('walkingStairs', $.base64.encode($('input[name=walkingStairs]:checked', '#frmJotForm').val()));
                    formData.append('handTools', $.base64.encode($('input[name=handTools]:checked', '#frmJotForm').val()));
                    formData.append('protectiveEquipment', $.base64.encode($('input[name=protectiveEquipment]:checked', '#frmJotForm').val()));
                    /*formData.append('workHeights',$.base64.encode($('input[name=workHeights]:checked', '#frmJotForm').val()));*/
                    formData.append('workConfinedSpaces', $.base64.encode($('input[name=workConfinedSpaces]:checked', '#frmJotForm').val()));
                    formData.append('workHotColdEnvironment', $.base64.encode($('input[name=workHotColdEnvironment]:checked', '#frmJotForm').val()));
                    formData.append('supercheck', $.base64.encode($('#supercheck').val()));
                    formData.append('conEmail', $.base64.encode($('#conEmail').val()));
                    formData.append('policeCheck', $.base64.encode($('input[name=policeCheck]:checked', '#frmJotForm').val()));
                    formData.append('statOccupation', $.base64.encode($('#statOccupation').val()));
                    formData.append('crimeCheck', $.base64.encode($('input[name=crimeCheck]:checked', '#frmJotForm').val()));
                    formData.append('crimeDate1', $.base64.encode($('#crimeDate1').val()));
                    formData.append('crime1', $.base64.encode($('#crime1').val()));
                    formData.append('crimeDate2', $.base64.encode($('#crimeDate2').val()));
                    formData.append('crime2', $.base64.encode($('#crime2').val()));
                    formData.append('optionChk', $.base64.encode($('input[name=optionChk]:checked', '#frmJotForm').val()));
                    formData.append('neverConvicted', $.base64.encode($('input[name=neverConvicted]:checked', '#frmJotForm').val()));
                    formData.append('neverImprisonment', $.base64.encode($('input[name=neverImprisonment]:checked', '#frmJotForm').val()));
                    formData.append('visaExpiry', $.base64.encode($('#visaExpiry').val()));

                    formData.append('pb_suburb', $.base64.encode($('#pb_suburb').val()));
                    formData.append('pb_state', $.base64.encode($('#pb_state').val()));
                    formData.append('pb_country', $.base64.encode($('#pb_country').val()));

                    formData.append('fw_first_name', $.base64.encode($('#fw_first_name').val()));
                    formData.append('fw_middle_name', $.base64.encode($('#fw_middle_name').val()));
                    formData.append('fw_last_name', $.base64.encode($('#fw_last_name').val()));

                    formData.append('fw_unit_no1', $.base64.encode($('#fw_unit_no1').val()));
                    formData.append('fw_street_number1', $.base64.encode($('#fw_street_number1').val()));
                    formData.append('fw_street_name1', $.base64.encode($('#fw_street_name1').val()));
                    formData.append('fw_suburb1', $.base64.encode($('#fw_suburb1').val()));
                    formData.append('fw_state1', $.base64.encode($('#fw_state1').val()));
                    formData.append('fw_postcode1', $.base64.encode($('#fw_postcode1').val()));
                    formData.append('fw_country1', $.base64.encode($('#fw_country1').val()));

                    formData.append('fw_unit_no2', $.base64.encode($('#fw_unit_no2').val()));
                    formData.append('fw_street_number2', $.base64.encode($('#fw_street_number2').val()));
                    formData.append('fw_street_name2', $.base64.encode($('#fw_street_name2').val()));
                    formData.append('fw_suburb2', $.base64.encode($('#fw_suburb2').val()));
                    formData.append('fw_state2', $.base64.encode($('#fw_state2').val()));
                    formData.append('fw_postcode2', $.base64.encode($('#fw_postcode2').val()));
                    formData.append('fw_country2', $.base64.encode($('#fw_country2').val()));

                    formData.append('fw_licence', $.base64.encode($('#fw_licence').val()));
                    formData.append('fw_licence_state', $.base64.encode($('#fw_licence_state :selected').val()));
                    formData.append('fw_passport_no', $.base64.encode($('#fw_passport_no').val()));
                    formData.append('fw_passport_country', $.base64.encode($('#fw_passport_country').val()));
                    formData.append('fw_type', $.base64.encode($('input[name=fw_type]:checked', '#frmJotForm').val()));
                    formData.append('fw_passport_type', $.base64.encode($('input[name=fw_passport_type]:checked', '#frmJotForm').val()));
                    formData.append('video_check1', $.base64.encode($('input[name=video_check1]:checked', '#frmJotForm').val()));
                    formData.append('video_check2', $.base64.encode($('input[name=video_check2]:checked', '#frmJotForm').val()));
                    formData.append('video_check3', $.base64.encode($('input[name=video_check3]:checked', '#frmJotForm').val()));
                    formData.append('video_check4', $.base64.encode($('input[name=video_check4]:checked', '#frmJotForm').val()));
                    formData.append('video1_status', $.base64.encode($('#video1_status').val()));
                    formData.append('video2_status', $.base64.encode($('#video2_status').val()));
                    formData.append('video3_status', $.base64.encode($('#video3_status').val()));
                    formData.append('video4_status', $.base64.encode($('#video4_status').val()));

                    $.ajax({
                        url: "./processJotForm.php",
                        type: 'POST',
                        dataType: 'text',
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            console.log('.....' + data);
                            if (data == 'SUCCESS') {
                                alert('Registration Form is successfully submitted!');
                                $('.regBtn').hide();
                            } else if (data == 'EXISTS') {
                                alert('Registration Already Submitted');
                            } else if (data == 'EMAIL') {
                                alert('Email address cannot be changed');
                            } else if (data == 'TFN') {
                                alert('Registration already submitted. Please contact Chandler Services @ 03 9656 9777');
                                $('.regBtn').hide();
                            } else {
                                alert('Error on submission!! ');
                            }
                        },
                        error: function (jqXHR, exception) {
                            if (jqXHR.status === 0) {
                                console.log('Not connect.\n Verify Network.');
                            } else if (jqXHR.status == 404) {
                                console.log('Requested page not found. [404]');
                            } else if (jqXHR.status == 500) {
                                console.log('Internal Server Error [500].');
                            } else if (exception === 'parsererror') {
                                console.log('Requested JSON parse failed.');
                            } else if (exception === 'timeout') {
                                console.log('Time out error.');
                            } else if (exception === 'abort') {
                                console.log('Ajax request aborted.');
                            } else {
                                console.log('Uncaught Error.\n' + jqXHR.responseText);
                            }
                        }
                    });
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });


});