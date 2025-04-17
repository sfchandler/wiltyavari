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
    $sigdiv.jSignature({'UndoButton':true
    });

    $sigdiv.jSignature("reset");
    $("#signature").on('change', function(e) {
        $("#imgSig").html('');
        var datapair = $sigdiv.jSignature("getData", "image");
        var i = new Image();
        i.id = 'signatureImg';
        i.src = "data:" + datapair[0] + "," + datapair[1];
        $(i).appendTo($("#imgSig"));
    });

    $(document).on('click','.empTermsBtn',function () {
        var empForm = $('#frmTermsForm').validate({
            rules:{},
            messages:{},
            submitHandler: function(form) {
                if ($sigdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                } else {
                    var firstName = $('#firstName').val();
                    var lastName  = $('#lastName').val();
                    var candidateId  = $('#candidateId').val();
                    var conEmail = $('#conEmail').val();
                    var imageSrc = $("#signatureImg").attr('src');
                    $.ajax({
                        url:"./processEmpTerms.php",
                        type:'POST',
                        dataType:'text',
                        data:{firstName:firstName,
                            lastName:lastName,
                            candidateId:candidateId,
                            imageSrc:imageSrc,
                            conEmail:conEmail
                        },
                        success: function (data) {
                            console.log('>>>>>>'+data);
                            if(data == 'SUCCESS'){
                                alert('Employment Contract successfully submitted!');
                                $('#empTermsBtn').hide();
                                window.close();
                            }else{
                                alert('! Submission Unsuccessful');
                            }
                        },
                        error: function(jqXHR, exception) {
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
