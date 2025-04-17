$(document).ready(function(){
    $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });

    let jobOrderDialog;
    let jobOrderEditDialog;
    /*let pendingOrdersDialog;*/

    let start = moment();
    let end = moment();
    let weekday = new Array(7);
    weekday[0]="Sun";
    weekday[1]="Mon";
    weekday[2]="Tue";
    weekday[3]="Wed";
    weekday[4]="Thu";
    weekday[5]="Fri";
    weekday[6]="Sat";
    let headerGlobal = [];
    let headerReturn = [];

    function dateCalendar(start, end) {
        let dateRange = [];
        let days = [];
        let date = [];
        let header = [];
        headerGlobal.length = 0;
        headerReturn.length = 0;
        $('#days').html('');
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        let startDate = new Date(start.format('YYYY-MM-DD HH:mm:ss'));
        let endDate = new Date(end.format('YYYY-MM-DD HH:mm:ss'));
        let currentDate = startDate;
        while (startDate <= endDate) {
            let dateFormat = startDate;
            dateRange.push(dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate());
            days.push(weekday[dateFormat.getDay()]);
            date.push(dateFormat.getDate());
            header.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
            headerReturn.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
            headerGlobal.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate()});
            currentDate.setDate(currentDate.getDate() + 1);
        }
        generateJobOrderTableHeader(header);
        $('#dateRange').val(dateRange);
        $('#startDate').val(start.format('YYYY-MM-DD HH:mm:ss'));
        $('#endDate').val(end.format('YYYY-MM-DD HH:mm:ss'));
    }
    $(document).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo(this);
            }
        }
    });
    $('#reportrange').daterangepicker({
        "autoApply": true,
        startDate: start,
        endDate: end,
        locale:{format : 'YYYY-MM-DD HH:mm:ss'},
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Day': [moment().subtract(6, 'days'), moment()]
        }
    }, dateCalendar);
    dateCalendar(start, end);
    function generateJobOrderTableHeader(header){
        let row = '<tr>';
        for(let headerItem in header){
            row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="jbOrderTableHeaderCell">'+header[headerItem]['headerDay']+'<br>'+header[headerItem]['headerDate']+'</th>';
        }
        row += '</tr>';
        $('.jobOrderHead').html('');
        $('.jobOrderBody').html('');
        $('.jobOrderHead').html(row);
        $('.jobOrderTable').css("width","95%");
        $('.jobOrderTable').css("overflow","auto");
    }
    populateClients();
    function populateClients(){
        let action = 'scheduling';
        $.ajax({
            url:"getClients.php",
            type:"POST",
            dataType:"html",
            data:{action:action},
            success: function(data){
                $('#clientId').html('');
                $('#clientId').html(data);
            }
        });
    }
    $(document).on('click','#clientId',function(){
        let clientId = $('#clientId :selected').val();
        let action = 'scheduling';
        displayPending(clientId);
        $.ajax({
            url:"getStateByClient.php",
            type:"POST",
            dataType:"html",
            data:{clientId:clientId},
            success: function(data){
                $('#stateId').html('');
                $('#stateId').html(data);
            }
        });
        $.ajax({
            url:"getClientPositionsList.php",
            type:"POST",
            dataType:"html",
            data:{clientId:clientId,action:action},
            success: function(data){
                $('#expPosition').html('');
                $('#expPosition').html(data);
            }
        });
    });
    function displayPending(clientId){
        let action = 'PENDING';
        $.ajax({
            url:"jobOrderProcessing.php",
            type:"POST",
            dataType:"html",
            data:{clientId:clientId,headerGlobal : headerGlobal,action:action},
            success: function(data){
                $('#displayPending').html('');
                $('#displayPending').html(data);
                /*pendingOrdersDialog.dialog("open");
                jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 3px; height: 3px;">X</span>');
                pendingOrdersDialog.dialog("option", "title", 'Pending Job Order Information');
                var target = $(this);
                pendingOrdersDialog.dialog('option', 'position', {
                    my: 'top', at: 'top',of: target
                });*/
            }
        });
    }
    $(document).on('click','#stateId',function(){
        let clientId = $('#clientId :selected').val();
        let stateId = $('#stateId :selected').val();
        let action = 'scheduling';
        $.ajax({
            url:"getDepartment.php",
            type:"POST",
            dataType:"html",
            data:{clientId:clientId,stateId:stateId,action:action},
            success: function(data){
                $('#departmentId').html('');
                $('#departmentId').html(data);
            }
        });
    });
    $(document).on('click','.jbOrderAdd',function (){
        let ordDate = $(this).attr('data-date');
        let clid = $(this).attr('data-clid');
        let posid = $(this).attr('data-posid');
        let deptid = $(this).attr('data-deptid');
        let stateid = $(this).attr('data-stateid');
        jobOrderDialog.data('ordDate',ordDate);
        jobOrderDialog.data('clid',clid);
        jobOrderDialog.data('posid',posid);
        jobOrderDialog.data('deptid',deptid);
        jobOrderDialog.data('stateid',stateid);
        jobOrderDialog.dialog("open");
        jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 3px; height: 3px;">X</span>');
        jobOrderDialog.dialog("option", "title", 'Job Order Information');
        var target = $(this);
        jobOrderDialog.dialog('option', 'position', {
            my: 'top', at: 'top',of: target
        });
    });
    jobOrderDialog = $("#jobOrderPopup").dialog({
        autoOpen: false,
        height: 370,
        width: 350,
        modal: true,
        open: function(event, ui) {
            $('#ordDate').val(jobOrderDialog.data('ordDate'));//jobOrderDialog.data('ordDate')
            $('#clid').val(jobOrderDialog.data('clid'));
            $('#posid').val(jobOrderDialog.data('posid'));
            $('#deptid').val(jobOrderDialog.data('deptid'));
            $('#stateid').val(jobOrderDialog.data('stateid'));
            $('#shiftStart').timepicker({'step': 15 , 'timeFormat': 'H:i'});
            $('#shiftStart').timepicker('hide');
            $("#jobOrderPopup").css({'overflow':'hidden'});
        },
        buttons: {
            Save: function(){
                $('#ordStatus').val('');
                $("#jbOrdFrm").submit();
            },
            Cancel: function() {
                $('#ordStatus').val('');
                jobOrderDialog.dialog("close");
            }
        }
    });
    jobOrderEditDialog = $("#jobOrderEditPopup").dialog({
        autoOpen: false,
        height: 370,
        width: 350,
        modal: true,
        open: function(event, ui) {
            $('#eordDate').val(jobOrderEditDialog.data('ordDate'));//jobOrderDialog.data('ordDate')
            $('#eclid').val(jobOrderEditDialog.data('clid'));
            $('#eposid').val(jobOrderEditDialog.data('posid'));
            $('#edeptid').val(jobOrderEditDialog.data('deptid'));
            $('#estateid').val(jobOrderEditDialog.data('stateid'));
            $('#eshiftStart').timepicker({'step': 15 , 'timeFormat': 'H:i',});
            $('#eshiftStart').timepicker('hide');
            $("#jobOrderEditPopup").css({'overflow':'hidden'});
        },
        buttons: {
            Update: function(){
                $('#eordStatus').val('');
                $("#jbOrdEditFrm").submit();
            },
            Cancel: function() {
                $('#eordStatus').val('');
                jobOrderEditDialog.dialog("close");
            }/*,
            Delete: function(){
                removeJobOrder(jobOrderEditDialog.data('job_id'));
                jobOrderEditDialog.dialog("close");
            }*/
        }
    });
    /*pendingOrdersDialog = $("#displayPending").dialog({
        autoOpen: false,
        height: 370,
        width: 350,
        modal: true,
        open: function(event, ui) {
            $("#displayPending").css({'overflow':'hidden'});
        }
    });*/
    let errorClass = 'invalid';
    let errorElement = 'em';
    $("#jbOrdFrm").validate({
        errorClass: errorClass,
        errorElement: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            ordQty:{
                required:true
            },
            ordMaleQty:{
                required:true
            },
            ordFemaleQty:{
                required:true
            }
        },
        messages: {
            ordQty:{
                required: "Please enter order quantity"
            },
            ordMaleQty:{
                required: "Please enter order male quantity"
            },
            ordFemaleQty:{
                required: "Please enter order female quantity"
            }
        },
        submitHandler: function (form) {
            let action = 'ADD';
            let ordDate = $('#ordDate').val();
            let ordQty = $('#ordQty').val();
            let clid = $('#clid').val();
            let posid =  $('#posid').val();
            let deptid = $('#deptid').val();
            let stateid = $('#stateid').val();
            let starttime = $('#shiftStart').val();
            let maleqty = $('#ordMaleQty').val();
            let femaleqty = $('#ordFemaleQty').val();

            $.ajax({
                url:"jobOrderProcessing.php",
                type:"POST",
                dataType:"html",
                data:{ ordDate:ordDate,ordQty:ordQty,clid:clid,posid:posid,deptid:deptid,stateid:stateid,starttime:starttime,maleqty:maleqty,femaleqty:femaleqty,action:action},
                success: function(data){
                    console.log('data '+data);
                    $('.filterBtn').click();
                    jobOrderDialog.dialog("close");
                    $('.error').html('');
                    $('.error').html(data);
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    let eerrorClass = 'invalid';
    let eerrorElement = 'em';
    $("#jbOrdEditFrm").validate({
        eerrorClass: errorClass,
        eerrorElement: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            eordQty:{
                required:true
            },
            eordMaleQty:{
                required:true
            },
            eordFemaleQty:{
                required:true
            }
        },
        messages: {
            eordQty:{
                required: "Please enter order quantity"
            },
            eordMaleQty:{
                required: "Please enter order male quantity"
            },
            eordFemaleQty:{
                required: "Please enter order female quantity"
            }
        },
        submitHandler: function (form) {
            let action = 'EDIT';
            let job_id = $('#job_id').val();
            let ordDate = $('#eordDate').val();
            let ordQty = $('#eordQty').val();
            let clid = $('#eclid').val();
            let posid =  $('#eposid').val();
            let deptid = $('#edeptid').val();
            let stateid = $('#estateid').val();
            let starttime = $('#eshiftStart').val();
            let maleqty = $('#eordMaleQty').val();
            let femaleqty = $('#eordFemaleQty').val();
            $.ajax({
                url:"jobOrderProcessing.php",
                type:"POST",
                dataType:"html",
                data:{ job_id:job_id,ordDate:ordDate,ordQty:ordQty,clid:clid,posid:posid,deptid:deptid,stateid:stateid,starttime:starttime,maleqty:maleqty,femaleqty:femaleqty,action:action},
                success: function(data){
                    $('.filterBtn').click();
                    jobOrderEditDialog.dialog("close");
                    $('.error').html('');
                    $('.error').html(data);
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    $(document).on('click','.filterBtn', function(){
        let positionId = $('#expPosition :selected').val();
        let clientId = $('#clientId :selected').val();
        let deptId = $('#departmentId :selected').val();
        let stateId = $('#stateId :selected').val();
        let startDate = $('#startDate').val();
        let endDate = $('#endDate').val();
        let action = 'DISPLAY';

        $.ajax({
            url:"jobOrderProcessing.php",
            type:"POST",
            dataType:"html",
            data:{headerGlobal : headerGlobal,positionId:positionId,clientId:clientId,deptId:deptId,stateId:stateId,startDate:startDate,endDate:endDate,action:action},
            success: function(data){
                $('.jobOrderBody').html('');
                $('.jobOrderBody').html(data);
            }
        });

    });
    $(document).on('click','.jbEdit',function (){
        let job_id = $(this).attr('data-jbid');
        let ordDate = $(this).attr('data-date');
        let clid = $(this).attr('data-clid');
        let posid = $(this).attr('data-posid');
        let deptid = $(this).attr('data-deptid');
        let stateid = $(this).attr('data-stateid');
        let sttime = $(this).attr('data-sttime');
        let jbqty = $(this).attr('data-jbqty');
        let maleqty = $(this).attr('data-maleqty');
        let femaleqty = $(this).attr('data-femaleqty');

        $('#job_id').val(job_id);
        $('#eordDate').val(ordDate);
        $('#eclid').val(clid);
        $('#eposid').val(posid);
        $('#edeptid').val(deptid);
        $('#estateid').val(stateid);
        $('#eshiftStart').val(sttime);
        $('#eordQty').val(jbqty);
        $('#eordMaleQty').val(maleqty);
        $('#eordFemaleQty').val(femaleqty);

        jobOrderEditDialog.data('job_id',job_id);
        jobOrderEditDialog.data('ordDate',ordDate);
        jobOrderEditDialog.data('clid',clid);
        jobOrderEditDialog.data('posid',posid);
        jobOrderEditDialog.data('deptid',deptid);
        jobOrderEditDialog.data('stateid',stateid);
        jobOrderEditDialog.dialog("open");
        jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 3px; height: 3px;">X</span>');
        jobOrderEditDialog.dialog("option", "title", 'Job Order Edit');
        var targetE = $(this);
        jobOrderEditDialog.dialog('option', 'position', {
            my: 'top', at: 'top',of: targetE
        });
    });
    function removeJobOrder(job_id){
        let action = 'REMOVE';
        $.ajax({
            url:"jobOrderProcessing.php",
            type:"POST",
            dataType:"html",
            data:{job_id:job_id,action:action},
            success: function(data){
                $('.filterBtn').click();
                $('.error').html('');
                $('.error').html(data);
            }
        });
    }
});