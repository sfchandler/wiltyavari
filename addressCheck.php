<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 29/08/2018
 * Time: 1:29 PM
 */
?>
<!--<script src="js/libs/jquery-2.1.1.min.js"></script>-->
<html>
<head>
<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>
</head>
<body>
<form>
    <textarea name="fullAddress" id="fullAddress"></textarea>
    <input type="text" name="street_number_1" id="street_number_1"/>
    <input type="text" name="street_name" id="street_name"/>
    <input type="text" name="suburb" id="suburb"/>
    <input type="text" name="state" id="state"/>
    <input type="text" name="postcode" id="postcode"/>
</form>
<script>
    (function() {
        var widget, initAF = function() {
            widget = new AddressFinder.Widget(
                document.getElementById('fullAddress'),
                'RWXLVYB7T8EM4JH6NQPK',
                'AU', {
                    "address_params": {
                        "region_code": "H"
                    },
                    "show_locations": true
                }
            );
            widget.on('result:select', function(fullAddress, metaData) {
                var selected = new AddressFinder.NZSelectedAddress(fullAddress, metaData);
                var jsonString  = JSON.stringify(metaData);
                $.each(selected, function(i, object) {
                    console.log('1ST....'+i+'ooo'+object);
                    if(i == 'fullAddress') {
                        $.each(metaData, function (property, value) {
                            console.log('........' + metaData['street_number_1'] + "=" + value);
                            $('#street_number_1').val(metaData['street_number_1']);
                            $('#street_name').val(metaData['street_name']+' '+metaData['street_type']);
                            $('#suburb').val(metaData['locality_name']);
                            $('#state').val(metaData['state_territory']);
                            $('#postcode').val(metaData['postcode']);
                        });
                    }
                });
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
</script>
</body>
</html>