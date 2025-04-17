<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$startDate = date('Y-m-d');//date('Y-m-d',strtotime('-1 days'));//date('Y-m-d H:i:s','2019-07-02 00:00:00');//$mysqli->real_escape_string
$endDate = date('Y-m-d');//date('Y-m-d', strtotime('+3 days'));//date('Y-m-d H:i:s','2019-07-22 00:00:00');
try {
    $events = getCalendarEvents($mysqli, $startDate);
}catch (Exception $e){
    $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo DOMAIN_NAME; ?> Appointments Ticker</title>
    <link href="css/jquery.jConveyorTicker.min.css" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/jquery-ui/1.11.0/jquery-ui.min.js"></script>
    <script src="js/jquery.jConveyorTicker.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.easy-ticker.js"></script>
    <script type="text/javascript">
        $(function() {
            pollServer();
            var easyTicker = $('.ticker').easyTicker({
                direction: 'up',
                easing: 'swing',//easeOutBounce
                speed:'slow',
                interval: 8000,
                visible:0
            });
            function sort_li() {
                //return new Date($(a).attr("data-date")) > new Date($(b).attr("data-date"));
                //return ($(b).data('date')) < ($(a).data('date')) ? 1 : -1;
                var tickerData = $("#tickerData");
                var ticker = tickerData.children('.eventItem').detach().get();
                ticker.sort(function(a, b) {
                    return new Date($(a).data("date")) - new Date($(b).data("date"));
                });
                tickerData.append(ticker);
            }
            function flushEvents(id){
                $.ajax({
                    url: "pushEvents.php",
                    type: "POST",
                    data: {action: 'FLUSH',id:id},
                    dataType: "json",
                    success: function (data){
                    }
                });
            }
            function pollServer(){
                    window.setTimeout(function () {
                        $.ajax({
                            url:"pushEvents.php",
                            type:"POST",
                            data:{action:'GET'},
                            dataType:"json",
                            success: function(data){
                                if(data != ''){
                                    $.each(data, function(index, element) {
                                        if($('#tickerData #'+element.id+'').length){
                                        }else{
                                            $('#tickerData').append('<li class="eventItem" id="'+element.id+'" data-date="'+element.start+'" class="eventItem">'+element.photo+'<p><span class="evTitle">'+element.title+'</span><br>'+element.start+' to '+element.end+'</p></li>');
                                            sort_li();
                                            $('#'+element.id+'').effect('highlight',{color:'#18b9c0',easing:'easeInElastic'}, 3000);
                                        }
                                    });
                                    pollServer();
                                }else{
                                    pollServer();
                                }
                            }
                        });
                        $.ajax({
                            url: "pushEvents.php",
                            type: "POST",
                            data: {action: 'DELETE'},
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (index, element) {
                                    if($('#tickerData #'+element.id+'').length) {
                                        /*$('#'+element.id+'').effect('highlight',{color:'#FA8072',easing:'easeInElastic'}, 2000);*/
                                        $('#'+element.id+'').remove();
                                        flushEvents(element.id);
                                        sort_li();
                                    }
                                });
                            }
                        });
                    },3500);
            }
        });
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500&family=Montserrat:wght@500&family=Titillium+Web&family=Work+Sans&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Montserrat', Calibri, Candara, Arial, sans-serif;
            font-size: 25pt;
            background: #F9F9F9;
        }
        .demof{
            /*border: 1px solid #ccc;*/
            margin: 25px 0;
            max-height: 800px;
            overflow: auto;
        }
        .demof ul{
            width: 100%;
            padding: 0;
            list-style: none;
        }
        .demof li{
            height: 100px;
            padding: 20px;
            border-bottom: 1px dashed #ccc;
        }
        .demof li.odd{
            background: #fafafa;
        }
        .demof li:after {
            content: '';
            display: block;
            clear: both;
        }
        .demof img{
            height: 100px;
            float: left;
            width: 100px;
            margin: 5px 15px 0 0;
        }
        .demof a{
            font-family: Arial, sans-serif;
            font-size: 30px;
            font-weight: bold;
            color: #06f;
        }
        .demof p {
            height: 100px;
            margin: 2px 0 0;
            font-size: 40px;
            /*text-shadow: 0 1px 2px rgba(69, 78, 140,1);*/
        }
        .ticker{
            box-shadow:
                    0 2.8px 2.2px rgba(0, 0, 0, 0.034),
                    0 6.7px 5.3px rgba(0, 0, 0, 0.048),
                    0 12.5px 10px rgba(0, 0, 0, 0.06),
                    0 22.3px 17.9px rgba(0, 0, 0, 0.072),
                    0 41.8px 33.4px rgba(0, 0, 0, 0.086),
                    0 100px 80px rgba(0, 0, 0, 0.12);
            width: 98vw;
            margin: 100px auto;
            background: white;
            border-radius: 5px;

            /*border: 1px solid #04AA6D;*/
            margin-top: 10px;
            border-radius: 5px;
            /*box-shadow: inset 0 0 7px rgba(189, 195, 199, 1);*/
            /*box-shadow: 2px 2px #888888;*/
            height: 400px;
            overflow: hidden;
        }
        .et-run{
            width: 100%;
            background-color: #0cf;
            color: white;
            /*border: 1px solid black;*/
        }
        .evTitle{
            font-size: 45px;
            text-transform: capitalize;
            font-weight: bold;
            /*border: 1px solid black;*/
        }
    </style>
</head>
<body>
<div style="float: left;font-weight: bold;">CHANDLER APPOINTMENTS</div>
<div style="float: right;"><img src="img/logochandler.png" alt="" width="277" height="37"></div>
<div style="clear: both"></div>
<?php
//if(!empty($events)){
?>
    <div class="ticker demof">
        <ul id="tickerData">
            <?php
            foreach ($events as $event) {
                echo '<li class="eventItem" id="'.$event['id'].'" data-date="'.$event['start'].'" class="eventItem">'.$event['photo'].'<p><span class="evTitle">' . $event['title'] . '</span><br>' . $event['start'] . ' to ' . $event['end'] . '</p></li>';
            }
            ?>
            <!--<li class="odd"><img src="images/img6.jpg" alt="Sample image" /><a href="#">dsfsfsdfsf</a><p>fffffffsdfssfsfsfdssdfdsfdsf.</p></li>
            <li><img src="images/img5.jpg" alt="Sample image" /><a href="#">From sbsbbsdss</a><p>sdfsdfsdfss. A jjjjjjjjjjjjjjjjj.</p></li>-->
        </ul>
    </div>
<?php
//}
?>
</body>
</html>




