$(document).ready(function() {
    /* AJAX loading animation */
    $body = $("body");
    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }
    });
    /* -  end  -*/
    $.ajaxSetup({
        headers : {
            'CsrfToken': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var $sigdiv = $("#signature");
    $(document).on('click','#reset',function () {
        $sigdiv.jSignature("reset");
    });
    $sigdiv.jSignature({'UndoButton':true,
        'background-color': 'transparent',
        'decor-color': 'transparent',
    });
    /*,
    'background-color': 'transparent',
        'decor-color': 'transparent',
        */
    $sigdiv.jSignature("reset");
    // Getting signature as SVG and rendering the SVG within the browser.
    // (!!! inline SVG rendering from IMG element does not work in all browsers !!!)
    // this export plugin returns an array of [mimetype, base64-encoded string of SVG of the signature strokes]
    $("#signature").on('change', function(e) {
        $("#imgSig").html('');
        var datapair = $sigdiv.jSignature("getData", "image");
        var i = new Image();
        i.id = 'signatureImg';
        i.src = "data:" + datapair[0] + "," + datapair[1];
        $(i).appendTo($("#imgSig")); // append the image (SVG) to DOM.
    });
    $(document).on('click','.submitBtn', function (evt) {
        var covidform = $('#frmCovidForm').validate({
            rules: {
                q1: {
                    required: true
                },
                q2: {
                    required: true
                },
                q3: {
                    required: true
                },
                q4: {
                    required: true
                },
                q5: {
                    required: true
                },
                q6: {
                    required: true
                },
                q7: {
                    required: true
                },
                q8: {
                    required: true
                }
            },
            submitHandler: function (form) {
                if($sigdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                }else {
                    var imageSrc = $("#signatureImg").attr('src');
                    var canId = $.base64.encode($('#canId').val());
                    var conEmail = $.base64.encode($('#conEmail').val());
                    var q1 = $.base64.encode($('input[name=q1]:checked', '#frmCovidForm').val());
                    var q2 = $.base64.encode($('input[name=q2]:checked', '#frmCovidForm').val());
                    var q3 = $.base64.encode($('input[name=q3]:checked', '#frmCovidForm').val());
                    var q4 = $.base64.encode($('input[name=q4]:checked', '#frmCovidForm').val());
                    var q5 = $.base64.encode($('input[name=q5]:checked', '#frmCovidForm').val());
                    var q6 = $.base64.encode($('input[name=q6]:checked', '#frmCovidForm').val());
                    var q7 = $.base64.encode($('input[name=q7]:checked', '#frmCovidForm').val());
                    var q8 = $.base64.encode($('input[name=q8]:checked', '#frmCovidForm').val());

                    $.ajax({
                        url: "./processCovidPolicy.php",
                        type: 'POST',
                        dataType: 'text',
                        data: {imageSrc:imageSrc,canId:canId,conEmail:conEmail,q1:q1,q2:q2,q3:q3,q4:q4,q5:q5,q6:q6,q7:q7,q8:q8},
                        success: function (data) {
                            if (data == 'SUCCESS') {
                                alert('Covid Policy successfully submitted!');
                                location.reload();
                            } else {
                                alert('! Submission Unsuccessful');
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
            }
        });
    });
});
