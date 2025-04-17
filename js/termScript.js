(function() {
    var widget, initAF = function() {
        widget = new AddressFinder.Widget(
            document.getElementById('address'),
            'RWXLVYB7T8EM4JH6NQPK',
            'AU', {
                "address_params": {}
            }
        );
        widget.on('result:select', function(fullAddress, metaData) {
            $('#street_number_1').val(metaData.street_number_1);
            $('#street_name').val(metaData.street_name+' '+metaData.street_type);
            $('#suburb').val(metaData.locality_name);
            $('#state').val(metaData.state_territory);
            $('#postcode').val(metaData.postcode);
        });
    };
    function downloadAF(f) {
        var script = document.createElement('script');
        script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
        script.async = true;
        script.onload = f;
        document.body.appendChild(script);
    };
    document.addEventListener('DOMContentLoaded', function() {
        downloadAF(initAF);
    });
})();

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
    // Getting signature as "base30" data pair
    // array of [mimetype, string of jSIgnature"s custom Base30-compressed format]
    //datapair = $sigdiv.jSignature("getData","base30");
    // reimporting the data into jSignature.
    // import plugins understand data-url-formatted strings like "data:mime;encoding,data"
    //$sigdiv.jSignature("setData", "data:" + datapair.join(","));

    $('#fullName').keyup(function() {
        var fullName = $(this).val(); // remove hyphens
        console.log('fullName'+fullName);
        $('#sgName').html(fullName);
    });
    $(document).on('click','.sendTermsBtn', function (evt) {
        var frmTermsForm = $('#frmTermsForm').validate({
            rules: {
                fullName: {
                    required: true
                },
                address: {
                    required: true
                }
            },
            messages:{
                fullName: {
                    required: "Required input",
                    minlength: "Please enter at least {0} digits"
                }
            },
            submitHandler: function(form) {
                if($sigdiv.jSignature('getData', 'native').length == 0) {
                    alert('Please Enter Signature!!!');
                }else{
                    var imageSrc = $("#signatureImg").attr('src');
                    var fullName = $.base64.encode($('#fullName').val());
                    var address = $.base64.encode($('#address').val());
                    var curDate = $.base64.encode($('#curDate').val());
                    var conEmail = $('#conEmail').val();
                    $.ajax({
                        url:"./processTermsForm.php",
                        type:'POST',
                        dataType:'text',
                        data:{fullName:fullName,
                            address:address,
                            curDate:curDate,
                            conEmail:conEmail,
                            imageSrc:imageSrc
                        },
                        success: function (data) {
                            if(data == 'SUCCESS'){
                                alert('Terms Form is successfully submitted!');
                                window.open('http://labourbank.com.au','_self');
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