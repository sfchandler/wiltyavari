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
    /*********** file upload ***************/
    var options = {
        beforeSend: function()
        {
            $("#taxprogress").show();
            //clear everything
            $("#taxbar").width('0%');
            $("#taxmessage").html("");
            $("#taxpercent").html("0%");
        },
        uploadProgress: function(event, position, total, percentComplete)
        {
            $("#taxbar").width(percentComplete+'%');
            $("#taxpercent").html(percentComplete+'%');

        },
        success: function()
        {
            $("#taxbar").width('100%');
            $("#taxpercent").html('100%');

        },
        complete: function(response)
        {
            if(response.responseText != 'Error Uploading'){
                $('#fileSubmitted').val(response.responseText);
            }else{
                $("#taxmessage").html("<font color='green'>"+response.responseText+"</font>");
            }
        },
        error: function()
        {
            $("#taxmessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmTaxForm").ajaxForm(options);

    var superOptions = {
        beforeSend: function()
        {
            $("#superprogress").show();
            //clear everything
            $("#superbar").width('0%');
            $("#supermessage").html("");
            $("#superpercent").html("0%");
        },
        uploadProgress: function(event, position, total, percentComplete)
        {
            $("#superbar").width(percentComplete+'%');
            $("#superpercent").html(percentComplete+'%');

        },
        success: function()
        {
            $("#superbar").width('100%');
            $("#superpercent").html('100%');

        },
        complete: function(response)
        {
            if(response.responseText != 'Error Uploading'){
                $('#superFileSubmitted').val(response.responseText);
            }else{
                $("#supermessage").html("<font color='green'>"+response.responseText+"</font>");
            }
        },
        error: function()
        {
            $("#supermessage").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    };
    $("#frmSuperForm").ajaxForm(superOptions);
    /*********** end file upload ***********/


    var $sigdiv = $("#signature");

    $(document).on('click','#reset',function () {
        $sigdiv.jSignature("reset");
    });
    $sigdiv.jSignature({'UndoButton':true,
        'background-color': 'transparent',
        'decor-color': 'transparent',
    });
    /*,'height' : 80,
        'width' : 100,
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
    $(document).on('click','.taxSubmitBtn', function (evt) {
        var frmTax = $('#frmTax').validate({
            rules: {
                fullName:{
                    required : true
                },
                mobileNo:{
                    required : true
                }
            },
            messages:{
                fullName:{
                    required: "Please enter your name"
                },
                mobileNo: {
                    required: "Required input",
                    minlength: "Please enter at least {0} digits"
                }
            },
            submitHandler: function(form) {
                if($('#fileSubmitted').val() == ''){
                    alert('Please Upload filled tax form first');
                }else if($sigdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                }else{
                    var imageSrc = $("#signatureImg").attr('src');
                    var fileSubmitted = $('#fileSubmitted').val();
                    var fullName = $.base64.encode($('#fullName').val());
                    var mobileNo = $.base64.encode($('#mobileNo').val());
                    $.ajax({
                        url:"./taxDeclaration.php",
                        type:'POST',
                        dataType:'text',
                        data:{
                            imageSrc:imageSrc,
                            fileSubmitted:fileSubmitted,
                            fullName:fullName,
                            mobileNo:mobileNo
                        },
                        success: function (data) {
                            $('.error').html('');
                            $('.error').html(data);
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

    var $sigSuperdiv = $("#superSignature");

    $(document).on('click','#reset',function () {
        $sigSuperdiv.jSignature("reset");
    });
    $sigSuperdiv.jSignature({'UndoButton':true,
        'background-color': 'transparent',
        'decor-color': 'transparent',
    });
    /*,'height' : 80,
        'width' : 100,
    'background-color': 'transparent',
        'decor-color': 'transparent',
        */
    $sigSuperdiv.jSignature("reset");
    // Getting signature as SVG and rendering the SVG within the browser.
    // (!!! inline SVG rendering from IMG element does not work in all browsers !!!)
    // this export plugin returns an array of [mimetype, base64-encoded string of SVG of the signature strokes]
    $("#superSignature").on('change', function(e) {
        $("#imgSuperSig").html('');
        var superdatapair = $sigSuperdiv.jSignature("getData", "image");
        var superImage = new Image();
        superImage.id = 'superSignatureImg';
        superImage.src = "data:" + superdatapair[0] + "," + superdatapair[1];
        $(superImage).appendTo($("#imgSuperSig")); // append the image (SVG) to DOM.
    });
    $(document).on('click','.superSubmitBtn', function (evt) {
        var frmSuper = $('#frmSuper').validate({
            rules: {
                superFullName:{
                    required : true
                },
                superMobileNo:{
                    required : true
                }
            },
            messages:{
                superFullName:{
                    required: "Please enter your name"
                },
                superMobileNo: {
                    required: "Required input",
                    minlength: "Please enter at least {0} digits"
                }
            },
            submitHandler: function(form) {
                if($('#superFileSubmitted').val() == ''){
                    alert('Please Upload filled super form first');
                }else if($sigSuperdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                }else{
                    var imageSuperSrc = $("#superSignatureImg").attr('src');
                    var superFileSubmitted = $('#superFileSubmitted').val();
                    var superFullName = $.base64.encode($('#superFullName').val());
                    var superMobileNo = $.base64.encode($('#superMobileNo').val());
                    $.ajax({
                        url:"./superDeclaration.php",
                        type:'POST',
                        dataType:'text',
                        data:{
                            imageSuperSrc:imageSuperSrc,
                            superFileSubmitted:superFileSubmitted,
                            superFullName:superFullName,
                            superMobileNo:superMobileNo
                        },
                        success: function (data) {
                            console.log('response'+data);
                            $('.error').html('');
                            $('.error').html(data);
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