<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$sessionId = session_id();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
    <meta http-equiv="refresh" content="120">
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
<!-- END HEADER -->

<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <!-- end user info -->
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->

<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">
				<span class="ribbon-button-alignment">
				</span>
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
        <!-- end breadcrumb -->
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content">
        <div style="height: 100%;">
            <h2>Appointments Calendar</h2>
            <div id='calendar'></div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<!-- PAGE FOOTER -->
<?php include "template/footer.php"; ?>
<!-- END PAGE FOOTER -->
<!-- END SHORTCUT AREA -->
<?php include "template/scripts.php"; ?>
<style type="text/css">
    body.modal-open div.modal-backdrop { z-index: 0; }
</style>
<!-- Fullcalendar -->
<link href="css/fullcalendar.css" rel="stylesheet"/>
<link href="css/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="js/moment.min.js"></script>
<script src="js/fullcalendar.js"></script>
<script>
    $(document).ready(function(){
        var calendar = $('#calendar').fullCalendar({
            header:{
                left: 'prev,next today',
                center: 'title',
                right: 'agendaWeek,agendaDay'
            },
            defaultView: 'agendaWeek',
            axisFormat: 'HH:mm',
            timeFormat: 'HH:mm',
            slotLabelFormat: 'HH:mm',
            minTime: '00:00:00',
            maxTime: '23:59:59',
            editable: false,
            selectable: true,
            allDaySlot: false,
            events: "processEvent.php?view=1",
            eventClick:  function(event, jsEvent, view) {
                console.log('EV ID'+event.id);
                endtime = $.fullCalendar.moment(event.end).format('H:mm');
                starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, H:mm');
                var mywhen = starttime + ' - ' + endtime;
                $('#modalTitle').html(event.title);
                $('#modalWhen').text(mywhen);
                $('#eventID').val(event.id);
                $('#calendarModal').modal();
            },
            //header and other values
            select: function(start, end, jsEvent) {
                endtime = $.fullCalendar.moment(end).format('H:mm');
                starttime = $.fullCalendar.moment(start).format('dddd, MMMM Do YYYY, H:mm');
                var mywhen = starttime + ' - ' + endtime;
                start = moment(start).format();
                end = moment(end).format();
                $('#createEventModal #startTime').val(start);
                $('#createEventModal #endTime').val(end);
                $('#createEventModal #when').text(mywhen);
                $('#createEventModal').modal('toggle');
            }/*,
            eventDrop: function(event, delta){
                $.ajax({
                    url: 'processEvent.php',
                    data: 'action=update&title='+event.title+'&start='+moment(event.start).format()+'&end='+moment(event.end).format()+'&id='+event.id ,
                    type: "POST",
                    success: function(json) {
                        //alert(json);
                    }
                });
            },
            eventResize: function(event) {
                $.ajax({
                    url: 'processEvent.php',
                    data: 'action=update&title='+event.title+'&start='+moment(event.start).format()+'&end='+moment(event.end).format()+'&id='+event.id,
                    type: "POST",
                    success: function(json) {
                        //alert(json);
                    }
                });
            }*/
        });
        $('#submitButton').on('click', function(e){
            // We don't want this to act as a link so cancel the link action
            e.preventDefault();
            doSubmit();
            calendar.fullCalendar('refetchEvents');
        });
        $('#deleteButton').on('click', function(e){
            // We don't want this to act as a link so cancel the link action
            e.preventDefault();
            doDelete();
            calendar.fullCalendar('refetchEvents');
        });
        function doDelete(){
            $("#calendarModal").modal('hide');
            var eventID = $('#eventID').val();
            $.ajax({
                url: 'processEvent.php',
                data: 'action=delete&id='+eventID,
                type: "POST",
                success: function(json) {
                    if(json == 1)
                        $("#calendar").fullCalendar('removeEvents',eventID);
                    else
                        return false;


                }
            });
        }
        function doSubmit(){
            $("#createEventModal").modal('hide');
            var title = $('#title').val();
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            $.ajax({
                url: 'processEvent.php',
                data: 'action=add&title='+title+'&start='+startTime+'&end='+endTime,
                type: "POST",
                success: function(json) {
                    $("#calendar").fullCalendar('renderEvent',
                        {
                            id: json.id,
                            title: title,
                            start: startTime,
                            end: endTime,
                        },
                        true);
                }
            });
        }
    });
</script>
<!-- Modal -->
<div id="createEventModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Event</h4>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="inputPatient">Event Subject:</label>
                    <div class="field desc">
                        <input class="form-control" id="title" name="title" placeholder="Event" type="text" value="">
                    </div>
                </div>

                <input type="hidden" id="startTime"/>
                <input type="hidden" id="endTime"/>



                <div class="control-group">
                    <label class="control-label" for="when">When:</label>
                    <div class="controls controls-row" id="when" style="margin-top:5px;">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
            </div>
        </div>

    </div>
</div>


<div id="calendarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Event Details</h4>
            </div>
            <div id="modalBody" class="modal-body">
                <h4 id="modalTitle" class="modal-title"></h4>
                <div id="modalWhen" style="margin-top:5px;"></div>
            </div>
            <input type="hidden" id="eventID"/>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button type="submit" class="btn btn-danger" id="deleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>
<!--Modal-->
<br>
<br>
<br>
</body>
</html>