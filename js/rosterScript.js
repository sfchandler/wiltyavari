$(document).ready(function(){
    $('#rosterTable').fixTableHeader();
    function blinker() {
        $('.rosterTableHead').css('background', '#f1f1e3').fadeOut(800);
        $('.rosterTableHead').css('background', '#f1f1e3').fadeIn(800);
    }


    var targetRowId;
    var tableContainer = $('div #rosterTable');
    var addShiftDialog;
    var editShiftDialog;
    var smsDialog;
    var smsAllDialog;
    var smsCovDialog;
    var smsVaccDialog;
    var smsOHSDialog;
    var smsAppVersionDialog;
    var surveyDialog;
    var sciclunaDialog;
    var chkArray = [];
    var smsBulkDialog;
    var firebaseBulkDialog;
    var rosterNoteDialog;
    var rowCandidateId = null;
    var form;
    var eform;
    var smsForm;
    var smsAllForm;

    var start = moment();
    var end = moment(new Date()).add(2,'days');
    var weekday=new Array(7);
    weekday[0]="Sun";
    weekday[1]="Mon";
    weekday[2]="Tue";
    weekday[3]="Wed";
    weekday[4]="Thu";
    weekday[5]="Fri";
    weekday[6]="Sat";
    var headerGlobal = [];
    var headerReturn = [];
    var rosterStartDate = '';
    var rosterEndDate = '';
    function dateCalendar(start, end) {
            /*if(start.isSame(end)){
                console.log('same')
                end = moment(start).add(2,'days');
            }*/
            var dateRange = [];
            var days = [];
            var date = [];
            var header = [];
            headerGlobal.length = 0;
            headerReturn.length = 0;
            $('#days').html('');
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var startDate = new Date(start.format('YYYY-MM-DD HH:mm:ss'));
            var endDate = new Date(end.format('YYYY-MM-DD HH:mm:ss'));
            var currentDate = startDate;
            while (startDate <= endDate) {
                var dateFormat = startDate;
                dateRange.push(dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate());
                days.push(weekday[dateFormat.getDay()]);
                date.push(dateFormat.getDate());
                header.push({
                    'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate(),
                    'headerDate': dateFormat.getDate(),
                    'headerDay': weekday[dateFormat.getDay()]
                });
                headerReturn.push({
                    'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate(),
                    'headerDate': dateFormat.getDate(),
                    'headerDay': weekday[dateFormat.getDay()]
                });
                headerGlobal.push({'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate()});
                currentDate.setDate(currentDate.getDate() + 1);
            }
            generateRosterTableHeader(header);
            $('#dateRange').val(dateRange);
            $('#startDate').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('#endDate').val(end.format('YYYY-MM-DD HH:mm:ss'));
            $('#rosterStartDate').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('#rosterEndDate').val(end.format('YYYY-MM-DD HH:mm:ss'));
    }
    $('#reportrange').daterangepicker({
        autoApply: true,
        startDate: start,
        endDate: end,
        locale:{format : 'YYYY-MM-DD HH:mm:ss'},
        isInvalidDate: function() {
            if(start === end){
                console.log('is eq');
                end = moment(start).add(2,'days');
                return true;
            }else{
                console.log('not eq');
            }
        }
    }, dateCalendar);
    dateCalendar(start, end);
    /*$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        if(picker.startDate === picker.endDate){
            console.log('..............date'+start+end)
            picker.endDate = moment(start).add(2,'days');
        }
        end = start.add(2,'days');
        dateCalendar(start, end);
    });*/
    /*function generateRosterTableHeader(header){
        var row = '';
        for(var headerItem in header){
            row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<span class="shiftCount"></span><br>'+header[headerItem]['headerDate']+'</th>';
        }
        $('.rosterTableHead').html('');
        $('.rosterTableBody').html('');
        $('.rosterTableHead').html('<th class="rosterTableHeaderCellLeftAligned" style="width: 400px;">' +
            '<br>&nbsp;&nbsp;<input type="checkbox" name="selectAllchRow" id="selectAllchRow" class="selectAllchRow" value=""/>&nbsp;&nbsp;<button name="bulkSMS" id="bulkSMS" class="btn btn-info btn-xs"><i class="fa fa-mobile-phone"></i>&nbsp;BULK SMS</button>&nbsp;&nbsp;<button name="addBulkShift" id="addBulkShift" class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i>&nbsp;ADD BULK SHIFT</button>&nbsp;<button name="deleteBulkShift" id="deleteBulkShift" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i>&nbsp;DELETE BULK SHIFT</button>&nbsp;' +
            /!*'<button name="firebaseBulkPush" id="firebaseBulkPush"><img src="../img/firebase_push.png"/> PUSH</button>' +*!/
            '<br><hr>' +
            '<div style="padding: 2px 0px 5px 50px; display: inline-block;">' +
            '<button class="genExportRosterBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP ROSTER</button>' +
            '</div>' +
            '<div style="padding: 2px 0px 5px 50px; display: inline-block;">' +
            '<button class="genExportRosterAllBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP ALL ROSTER</button>' +
            '</div>' +
            '<div style="padding: 2px 0px 5px 50px; display: inline-block;">' +
            '<button class="genEveryExcelBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP EVERYONE</button>' +
            '</div>' +
            '<div style="padding: 2px 0px 2px 50px; display: inline-block;">' +
            '<button class="genLastShiftExcelBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp; EXP LAST SHIFT</button>' +
            '</div>' +
            '<div style="padding: 2px 10px 10px 50px; display: inline-block;">' +
            '<button class="genEverythingBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP EVERYTHING</button>' +
            '</div>' +
            '<div style="padding: 2px 10px 10px 40px; display: inline-block;">' +
            '<button class="genPerClientExcelBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP PER CLIENT</button>' +
            '</div>' +
            '<div style="padding: 2px 10px 10px 50px; display: inline-block;">' +
            '<button class="genNoAnswerBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP NOANSWER</button>' +
            '</div>'+
            '</th>'+row+'<th class="rosterTableAction"></th>');
        //<button class="genExcelBtn btn btn-info btn-xs" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i>&nbsp;Export Confirmed</button>&nbsp;<button class="genAllExcelBtn btn btn-info btn-xs" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i> Export All</button>
        //&nbsp;&nbsp;&nbsp;&nbsp;<button class="tandaRosterBtn btn btn-warning btn-sm" type="button"><i class="glyphicon glyphicon-flash"></i>&nbsp;Upload to Tanda</button>
        $('.rosterTable').css("width","100%");
        $('.rosterTable').css("overflow","auto");
    }*/


    function generateRosterTableHeader(header){
        var row = '';
        for(var headerItem in header){
            row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<span class="shiftCount"></span><br>'+header[headerItem]['headerDate']+'</th>';
        }
        $('.rosterTableHead').html('');
        $('.rosterTableBody').html('');
        $('.rosterTableHead').html('<tr><th class="rosterTableHeaderCellLeftAligned">' +
            '<br>&nbsp;&nbsp;<input type="checkbox" name="selectAllchRow" id="selectAllchRow" class="selectAllchRow" value=""/>&nbsp;&nbsp;' +
            '<button name="bulkSMS" id="bulkSMS" class="btn btn-info btn-xs"><i class="fa fa-mobile-phone"></i>&nbsp;BULK SMS</button>&nbsp;' +
            '&nbsp;<button name="addBulkShift" id="addBulkShift" class="btn btn-info btn-xs"><i class="fa fa-plus"></i>&nbsp; BULK SHIFT</button>&nbsp;' +
            '&nbsp;<button name="deleteBulkShift" id="deleteBulkShift" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i>&nbsp; BULK SHIFT</button>&nbsp;' +
            '</th>' + row + '<th class="rosterTableAction"></th></tr>'
        );
    }
    function tableCounter(){
        var shCounter = new Array();
        $("table#tblRoster tr").each(
            function (i,e)
            {
                $(e).find("td").each(
                    function (i,e)
                    {
                        if (!shCounter[i]) shCounter[i] = 0;
                        if (($(e).find('div.shiftDisplay.shiftConfirmed').length)>0)shCounter[i]++;
                    }
                );
            }
        );
        var lastrow = '<tr id="sh_counter">';
        var column = 0;
        var totalShifts = 0;
        var len = shCounter.length;
        $.each(shCounter, function(i,e) {
            if(e>0){
                lastrow+='<th class="rosterTableHeaderCell">'+e+'</th>';
                totalShifts = totalShifts + e;
            }else{
                if(column == 0){
                    lastrow+='<th class="rosterTableHeaderCell">Shift Counter</th>';
                }else if((column == len - 1)){
                    lastrow+='<th class="rosterTableHeaderCell">Total of Shifts '+totalShifts+'</th>';
                }else{
                    lastrow+='<th class="rosterTableHeaderCell"></th>';
                }
            }
            column++;
        });
        lastrow+='</tr>';
        $('#sh_counter').remove();
        $("table#tblRoster thead").append(lastrow);
    }
    /*function tableCounter(){
        var shCounter = new Array();
        $("table#tblRoster tr").each(
            function (i,e)
            {
                $(e).find("td").each(
                    function (i,e)
                    {
                        if (!shCounter[i]) shCounter[i] = 0;
                        //if ($(e).hasClass("shiftDisplay")) shiftCounter[i]++;
                        if (($(e).find('div.shiftDisplay.shiftConfirmed').length)>0)shCounter[i]++;
                    }
                );
            }
        );
        var lastrow = '<tr>';
        var column = 0;
        var totalShifts = 0;
        var len = shCounter.length;
        $.each(shCounter, function(i,e) {
            if(e>0){
                lastrow+='<td class="rosterTableHeaderCell">'+e+'</td>';
                totalShifts = totalShifts + e;
            }else{
                if(column == 0){
                    lastrow+='<td class="rosterTableHeaderCell">Shift Counter</td>';
                }else if((column == len - 1)){
                    lastrow+='<td class="rosterTableHeaderCell">Total of Shifts '+totalShifts+'</td>';
                }else{
                    lastrow+='<td class="rosterTableHeaderCell"></td>';
                }
            }
            column++;
        });
        lastrow+='</tr>';
        $("table#tblRoster tbody").prepend(lastrow);
    }*/
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

    function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
            return null;
        }

        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.child, function (idx, child) {
            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                filteredChildren.push(child);
            }
        });

        // If we matched any of the timezone group's children, then set the matched children on the group
        // and return the group object
        if (filteredChildren.length) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.children = filteredChildren;

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }
    populateClients();
    function populateClients(){
        var action = 'scheduling';
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
    function getClientDepartmentNote(){
        $('#clientNote').removeAttr('title');
        var deptId = $('#departmentId :selected').val();
        $.ajax({
            url:"getClientNotes.php",
            type:"POST",
            dataType:"text",
            data:{deptId:deptId},
            success: function(data){
                $('#clientNote').removeAttr('title');
                $('#clientNote').attr('title',data);
            }
        });
    }

    $(document).on('change','#clientId',function(){
        var clientId = $('#clientId :selected').val();
        var action = 'scheduling';
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
    $(document).on('click','#clientId',function(){
        var clientId = $('#clientId :selected').val();
        var action = 'scheduling';
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
    $(document).on('click','#stateId',function(){
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var action = 'scheduling';
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
    /*populateClientDepartments();
    function populateClientDepartments(){

        $.ajax({
            url:"getClientDepartments.php",
            type:"POST",
            dataType:"html",
            success: function(data){
                $('#departmentId').html('');
                $('#departmentId').html(data);
            }
        }).done(function() {
            //drop down search
            //$("#departmentId").select2();
            $("#departmentId").chosen({create_option: true,
                persistent_create_option: true,
                create_option_text: 'add',});
            $("#departmentId").unmousewheel();
        });
    }*/
    /*populateCandidatePositions();
    function populateCandidatePositions(){
        var dropSelect = 'N';
        $.ajax({
            url:"getCandidatePositionList.php",
            type:"POST",
            data:{dropSelect:dropSelect},
            dataType:"html",
            success: function(data){
                $('#expPosition').html('');
                $('#expPosition').html(data);
            }
        });
    }*/
    function generateRosterTableBody(clientId,stateId,deptId,num_th,positionid,candidateId,stWrkDate){
        /*var clientID;
        if(param != 'None' && param != ''){
            var paramValues = param.split('-');
            clientID = paramValues[0];
        }*/
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        $.ajax({
            url:"getAllocatedEmployees.php",
            type:"POST",
            dataType: "html",
            data:{clientId:clientId,stateId:stateId,deptId:deptId, num_th : num_th, headerGlobal : headerGlobal, positionid : positionid, candidateId : candidateId,startDate:startDate,endDate:endDate,stWrkDate:stWrkDate},
            success: function(data){
                $('.rosterTableBody').html('');
                $('.rosterTableBody').html(data);
                //$('#rosterTable').fixTableHeader();
                /*rowCandidateId = $("#"+targetRowId);
                    if(rowCandidateId.length) {
                        $('html, body').animate({scrollTop: rowCandidateId.offset().top}, 'slow');
                    }
                */
                //sortRosterTableBody();
            }
        }).done(function(){
            //sortRosterTableBody(); // commented to prevent sorting to last
            tableContainer.fixTableHeader();
            rowCandidateId = $("#"+targetRowId);
            if(rowCandidateId.length) {
                //$('html,body').animate({scrollTop: rowCandidateId.offset().top - (w.height()/2)}, 1000 );
                //$('html,body').animate({scrollTop: rowCandidateId.offset().top}, 'fast');
                tableContainer.animate({scrollTop: rowCandidateId.offset().top - 250}, 'slow');
                /*tableContainer.scrollTop(
                    //rowCandidateId.offset().top - $('#rosterTable').offset().top + $('#rosterTable').scrollTop()
                    rowCandidateId.offset().top - tableContainer.offset().top + tableContainer.scrollTop()
                );*/
                /*tableContainer.animate({
                    scrollTop: rowCandidateId.offset().top - tableContainer.offset().top + tableContainer.scrollTop()
                },'slow');*/
            }
            tableCounter();
            //overtimeLimitDisplay(rowCandidateId,clientId);
        });
    }
    function overtimeLimitDisplay(rowCandidateId,clientID){
        $('.rosterTable > tbody > tr').each(function(){
            var currentRowId = $(this).attr('id');
            var lamattinaId = $(this).children().find('.lamattinaId').text();
            var rowCell = $(this).find('td');
            var total = 0.0;
            rowCell.each(function(){
                var hrsWrd = $(this).find('div.shiftConfirmed').find('span.hrsWorked').attr('data-hrsworked');
                if(hrsWrd != undefined) {
                    total = total + parseFloat(hrsWrd);
                }else{
                    total = total + 0;
                }
            });
            /* Display 38hrs limit on roster */
            //if(lamattinaId.length>0){ //(lamattinaId != '') && $.isNumeric(lamattinaId)
            /*if(clientID == 89 || clientID == 111){
                if(total > 60){
                    $('#'+currentRowId).find('td span.hrsTotal').html(total+'&nbsp;Work Hours');
                    $('#'+currentRowId).find('td span.hrsTotal').toggle('puff').effect("highlight", {}, 6000);
                    $('#'+currentRowId).effect('highlight',{color:'#FF0000',easing:'easeInElastic'}, 3000);
                    //sendNotification(currentRowId);
                }else{
                }
            }else {*/
                if(total > 38){
                    $('#'+currentRowId).find('td span.hrsTotal').html(total+'&nbsp;Work Hours');
                    $('#'+currentRowId).find('td span.hrsTotal').toggle('puff').effect("highlight", {}, 6000);
                    $('#'+currentRowId).effect('highlight',{color:'#FF6666',easing:'easeInElastic'}, 3000);
                    //sendNotification(currentRowId);
                }
            /*}*/
        });
    }
    function sendNotification(empId){

        $.ajax({
            url:"sendWorkHrsWarning.php",
            type:"POST",
            dataType: "html",
            data:{empId:empId}
        })
    }
    function sortRosterTableBody(){
        var lastRowId = $('.rosterTable').find('tr:last').attr('id');
        $('.rosterTable > tbody > tr').each(function(){
            var currentRowId = $(this).attr('id');
            var nextRowId = $(this).next().attr('id');
            if ($(this).find('td div.shiftDisplay').length >0){
            }else{
                $('#'+currentRowId).insertAfter('#'+lastRowId);
            }
        });
        var shiftInfo = [];
        $('.rosterTable > tbody > tr').each(function(){
            var currentRowId = $(this).attr('id');
            if($(this).find('td div.shiftDisplay:first').attr('data-shiftstart') != undefined){
                var thisStartTime = $(this).find('td div.shiftDisplay:first').attr('data-shiftstart');
                var startDate = $(this).find('td div.shiftDisplay:first').attr('data-shiftdate');
                var dateBreak = startDate.split("-");
                var newStartDate = dateBreak[0]+"/"+dateBreak[1]+"/"+dateBreak[2];
                var timeStamp = new Date(newStartDate+' '+thisStartTime).getTime();
                shiftInfo.push({rowId:currentRowId, stTime: timeStamp});
            }else{
                //shiftInfo.push({rowId:currentRowId, stTime: 123456789123456789});
            }
        });
        shiftInfo.sort(function(obj1,obj2){
            return obj1.stTime - obj2.stTime;
        });
        $.each(shiftInfo, function (index, value) {
            $('#'+value.rowId).insertBefore('#'+lastRowId);
        });
    }
    $('.ui-autocomplete-input').css('width','40px')
    $('#employeeName').autocomplete({
        source: "./employeeList.php",
    select: function(event, ui) {
        var empName = ui.item.value;
        var candidateId = ui.item.id;
        $('#empSelected').val('');
        $('#empSelected').val(candidateId);
    }
    });
    $(document).on('click','.scheduleBtn', function(){
        chkArray = [];
        loadRecipients(1,0);
        //var param = $('#departmentId :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var positionid = $('#expPosition :selected').val();
        var candidateId;
        var clientId = $('#clientId :selected').val();
        var deptId = $('#departmentId :selected').val();
        var stateId = $('#stateId :selected').val();
        var stWrkDate = $('#stWrkDate').val();
        if($('#employeeName').val() === ''){
            candidateId = '';
            searchTxt = '';
        }else{
            candidateId = $('#empSelected').val();
            searchTxt = '&nbsp;Schedule For '+$('#employeeName').val();
        }
        generateRosterTableBody(clientId,stateId,deptId,num_th,positionid,candidateId,stWrkDate);
        $('#searchedPerson').html('');
        $('#searchedPerson').html(searchTxt);
    });
    $('input[name="stWrkDate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="stWrkDate"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#stWrkDate').val(picker.startDate.format('YYYY-MM-DD'));
    });
    $(document).on('click','.copyScheduleBtn', function (){
        var positionId = $('#expPosition :selected').val();
        var clientId = $('#clientId :selected').val();
        var deptId = $('#departmentId :selected').val();
        var stateId = $('#stateId :selected').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var dateRange = $('#dateRange').val();
        var num_th = $('.rosterTableHead th').length;
        if($('#employeeName').val() === ''){
            candidateId = '';
            searchTxt = '';
        }else{
            candidateId = $('#empSelected').val();
            searchTxt = '&nbsp;Schedule For '+$('#employeeName').val();
        }
        var candidateId;
        var copyRoster = 'COPYROSTER';
        $.ajax({
            url:"saveShift.php",
            type:"POST",
            data:{clientId:clientId,stateId:stateId,deptId:deptId,positionId:positionId,startDate:startDate,endDate:endDate,dateRange:dateRange,copyRoster:copyRoster},
            dataType:"text",
            success: function(data){
                $.each(data, function(index, element){
                    if (element.status=='ShiftLocationNotFound'){
                        console.log('....'+element.status);
                        $('.erMsg').html('');
                        $('.erMsg').html('Shift Location not found');
                    }
                });
                generateRosterTableBody(clientId,stateId,deptId,num_th,positionId,candidateId);
            }
        });
    });
    /*$(document).on('click','#rosterOrderBtn', function(){
        var action = 'UPDATE';
        var param = $('#departmentId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var rosterStartDate = $('#rosterStartDate').val();
        var rosterEndDate = $('#rosterEndDate').val();
        var rosterOrder = $('#rosterOrder').val();
        $.ajax({
            url:"rosterOrder.php",
            type:"POST",
            data:{rosterOrder:rosterOrder,rosterStartDate:rosterStartDate, rosterEndDate:rosterEndDate,positionid:positionid,param:param,action:action},
            dataType:"text",
            success: function(data){
                $('#rosterOrder').val('');
                $('#rosterOrder').val(data);
            }
        });
    });*/
    /*function populateSupervisors(param,dropdown){
        $.ajax({
            url:"getSupervisorsList.php",
            type:"POST",
            data:{param:param, dropdown:dropdown},
            dataType:"html",
            success: function(data){
                $('#supervisorId').html('');
                $('#supervisorId').html(data);
            }
        });
    }*/
    $(document).on('change', '#departmentId', function(){
        getClientDepartmentNote();
        /*$('#supervisorDetails').html('');
        var param = $('#departmentId :selected').val();*/
        /*var dropdown = 1;*/
        /*var num_th = $('.rosterTableHead th').length;
        var positionid = $('#expPosition :selected').val();
        generateRosterTableBody(param,num_th,positionid);
        populateSupervisors(param,dropdown);*/
    });
    $(document).on('click', '#departmentId', function(){
        getClientDepartmentNote();
    });
    /*$(document).on('change', '#supervisorId', function(){
        var supervisorId = $('#supervisorId :selected').val();
        $.ajax({
            url:"getSupervisorsList.php",
            type:"POST",
            data:{supervisorId:supervisorId},
            dataType:"html",
            success: function(data){
                $('#supervisorDetails').html('');
                $('#supervisorDetails').html(data);
            }
        });
    });*/
    function getShiftLocationsDropDown(clientId){
        $.ajax({
            url:"getClientShiftLocationsDropdown.php",
            type:"POST",
            data:{clientId:clientId},
            dataType:"html",
            success: function(data){
                $('#shiftLocation').html('');
                $('#shiftLocation').html(data);
            }
        });
    }
    function getEditShiftLocationsDropDown(clientId,addressId){
        $.ajax({
            url:"getClientShiftLocationsDropdown.php",
            type:"POST",
            data:{clientId:clientId,addressId:addressId},
            dataType:"html",
            success: function(data){
                $('#eshiftLocation').html('');
                $('#eshiftLocation').html(data);
            }
        });
    }
    var addShiftClick = null;
    $(document).on('click', '.addshift', function(e){
        $('.erMsg').html('');
        var tdDate = $(this).closest('td').attr('data-tddate');
        var canid = $(this).closest('td').attr('data-canid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var empName = $(this).closest('td').attr('data-empName');
        var thDay =  $(this).closest('table').find('th').eq($(this).closest('td').index()).attr('data-day');

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
        rowCandidateId = $("#"+targetRowId);
        /* SCROLL TO POSITION */
        if(rowCandidateId.length) {
            //$('html, body').animate({scrollTop: rowCandidateId.offset().top}, 'fast');
        }
        addShiftClick = $(this);
        addShiftDialog.data('tDate',tdDate);
        addShiftDialog.data('thDay',thDay);
        addShiftDialog.data('canid',canid);
        addShiftDialog.data('clid',clid);
        addShiftDialog.data('stid',stid);
        addShiftDialog.data('did',did);
        addShiftDialog.data('empName',empName);
        addShiftDialog.dialog("open");
        jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
        addShiftDialog.dialog("option", "title", 'New Shift On '+tdDate);
        var target = $(this);
        addShiftDialog.dialog('option', 'position', {
            my: 'top', at: 'top',of: target
        });
    });
    var errorClass = 'invalid';
    var errorElement = 'em';
    $("#shiftFrm").validate({
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
            shiftStart: {
                required: true
            },
            shiftEnd: {
                required: true
            }
        },
        messages: {
            shiftStart:{
                required: "Please enter a note"
            },
            shiftEnd:{
                required: "Please enter a note"
            }
        },
        submitHandler: function (form) {
            var shDate = $('#shiftDate').val();
            var shDay = $('#shiftDay').val();
            var clid = $('#clid').val();
            var stid = $('#stid').val();
            var did = $('#did').val();
            var canid = $('#canid').val();
            var shiftStart = $('#shiftStart').val();
            var shiftEnd = $('#shiftEnd').val();
            var workBreak = $('#break').val();
            var note = $('textarea#note').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var dateRange = $('#dateRange').val();
            var shiftCopy = $('input[name=shiftCopy]:checked', '#shiftFrm').val();
            //bulkShift
            var bulkCanId = $('#bulkCanId').val();
            var positionid = $('#expPosition :selected').val();
            var shStatus = $('#shStatus').val();
            var addressId = $('#shiftLocation :selected').val();
            var shiftCallStatus = $('#shiftCallStatus :selected').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "saveShift.php",
                type:"POST",
                dataType:"json",
                data: {shDate : shDate, shDay : shDay, clid : clid, stid : stid, did : did, canid : canid, bulkCanId:bulkCanId, shiftStart : shiftStart, shiftEnd : shiftEnd, workBreak : workBreak, note : note, shiftCopy : shiftCopy, startDate : startDate, endDate : endDate, dateRange : dateRange, positionid : positionid,shStatus:shStatus,addressId:addressId,shiftCallStatus:shiftCallStatus},
                success: function(data){
                    $.each(data, function(index, element) {
                        console.log('element '+element.status);
                        if(element.status=='shiftOverlap'){
                            console.log('OVERLAP'+element.status);
                            $('.erMsg').html('');
                            $('.erMsg').html('Shift Overlaps existing');
                        }else if(element.status=='shiftAdded'){
                            $('.erMsg').html('');
                            $('.erMsg').html('Shift Added');
                        }else if(element.status=='maxShifts'){
                            $('.erMsg').html('');
                            $('.erMsg').html('Employee is having two shifts in same date');
                        }else if(element.status=='bulkShiftAdded'){
                            $('.erMsg').html('');
                            $('.erMsg').html('Bulk shift added');
                        }else if(element.status=='NoJobCode'){
                            console.log('NO JOBCODE'+element.status);
                            $('.erMsg').html('');
                            $('.erMsg').html('No JobCode Found');
                        }else if(element.status=='ShiftLocationNotFound') {
                            $('.erMsg').html('');
                            $('.erMsg').html('Shift Location not found');
                        }else if(element.status=='VisaExpired') {
                            console.log('VISA EXPIRED'+element.status);
                            $('.erMsg').html('');
                            $('.erMsg').html('Candidate Visa Expired');
                        }else{
                            console.log('ERRORS'+element.status);
                        }
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody(clid,stid,did,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        addShiftDialog.dialog("close");
                    });
                    chkArray = [];
                    addBulkShiftDialog.data('bulkCanId',chkArray);
                    $('#bulkCanId').val('');
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    addShiftDialog = $("#shiftPopup").dialog({
        autoOpen: false,
        height: 400,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#shiftDate').val(addShiftDialog.data('tDate'));
            $('#shiftDay').val(addShiftDialog.data('thDay'));
            $('#clid').val(addShiftDialog.data('clid'));
            getShiftLocationsDropDown(addShiftDialog.data('clid'));
            $('#stid').val(addShiftDialog.data('stid'));
            $('#did').val(addShiftDialog.data('did'));
            $('#canid').val(addShiftDialog.data('canid'));
            $('#empName').html(addShiftDialog.data('empName'));
            /*var clid = addShiftDialog.data('clid');
            var stid = addShiftDialog.data('stid');*/
            $('#shiftStart').timepicker({'step': 15 , 'timeFormat': 'H:i'});
            $('#shiftEnd').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});
            $("#shiftPopup").css({'overflow':'hidden'});
        },
        buttons: {
            Save: function(){
                $('#shStatus').val('');
                $('#shiftFrm').submit();
            },
            Cancel: function() {
                $('#shStatus').val('');
                addShiftDialog.dialog("close");
            },
            Sick: function() {
                $('#shStatus').val('');
                addShiftDialog.dialog("close");
            },
            NotAvailable: function(){
                $('#shStatus').val('N/A');
                $('#shiftFrm').submit();
            }
        }
    });

    addBulkShiftDialog = $("#shiftPopup").dialog({autoOpen: false,
        height: 400,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#shiftDate').val(addBulkShiftDialog.data('tDate'));
            $('#shiftDay').val(addBulkShiftDialog.data('thDay'));
            $('#clid').val(addBulkShiftDialog.data('clid'));
            getShiftLocationsDropDown(addBulkShiftDialog.data('clid'));
            $('#stid').val(addBulkShiftDialog.data('stid'));
            $('#did').val(addBulkShiftDialog.data('did'));
            $('#canid').val(addBulkShiftDialog.data('canid'));
            $('#bulkCanId').val(addBulkShiftDialog.data('bulkCanId'));
            $('#empName').html(addBulkShiftDialog.data('empName'));
            $('#shiftStart').timepicker({'step': 15 , 'timeFormat': 'H:i'});
            $('#shiftEnd').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});
            $("#shiftPopup").css({'overflow':'hidden'});
        },
        buttons: {
            Save: function(){
                $('#shStatus').val('');
                $('#shiftFrm').submit();
            },
            Cancel: function() {
                $('#shStatus').val('');
                addShiftDialog.dialog("close");
            },
            NotAvailable: function(){
                $('#shStatus').val('N/A');
                $('#shiftFrm').submit();
            }
        }
    });
    $(document).on('click','#deleteBulkShift',function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var consultant = $('#consultant').val();
        if(chkArray.length != 0) {
            console.log("employee selected"+chkArray);
            $.each(chkArray, function (index, value) {
                console.log('chkarray.....'+value);
                //loadRecipients(value, count++);
                deleteBulkShift(clientId,stateId,deptId,startDate,endDate,value,consultant);
            });
        }else{
            alert("Please tick employees to delete shifts");
            loadRecipients(1,0);
        }
    });
    function deleteBulkShift(clid,stid,did,strdate,enddate,canid,consultant){
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "deleteAllShifts.php",
            type: "POST",
            dataType: "text",
            data: {canid : canid,clid : clid,stid : stid,did : did,strdate : strdate, enddate : enddate, consultant : consultant},
            success: function(data) {
                if(data){
                    generateRosterTableHeader(headerReturn);
                    generateRosterTableBody(clid,stid,did,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                }
            }
        });
    }
    $(document).on('click','#addBulkShift', function(){
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var clid = $('#clientId :selected').val();
        var stid = $('#stateId :selected').val();
        var did = $('#departmentId :selected').val();
        addBulkShiftDialog.data('tDate','');
        addBulkShiftDialog.data('thDay','');
        addBulkShiftDialog.data('canid','');
        addBulkShiftDialog.data('bulkCanId',chkArray);
        addBulkShiftDialog.data('clid',clid);
        addBulkShiftDialog.data('stid',stid);
        addBulkShiftDialog.data('did',did);
        addBulkShiftDialog.data('empName','');
        addBulkShiftDialog.dialog("open");
        jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
        addBulkShiftDialog.dialog("option", "title", 'New Bulk Shift');
        var target = $(this);
        addBulkShiftDialog.dialog('option', 'position', {
            my: 'top', at: 'top',of: target
        });
    });
    $(document).on('click', '.editshift', function(){
        $('.erMsg').html('');
        var empName = $(this).closest('td').attr('data-empName');
        var shiftid = $(this).closest('div').attr('data-shiftid');
        var addressId = $(this).closest('div').attr('data-addressId');
        var shiftdate = $(this).closest('div').attr('data-shiftdate');
        var clid = $(this).closest('div').attr('data-clid');
        var stid = $(this).closest('div').attr('data-stid');
        var did = $(this).closest('div').attr('data-did');
        var canid = $(this).closest('div').attr('data-canid');
        var shiftstart = $(this).closest('div').attr('data-shiftstart');
        var shiftend = $(this).closest('div').attr('data-shiftend');
        var ebreak = $(this).closest('div').attr('data-break');
        var shiftnote = $(this).closest('div').attr('data-shiftnote');
        var shiftStatus = $(this).closest('div').attr('data-shiftStatus');
        var shiftSMSStatus = $(this).closest('div').attr('data-shiftSMSStatus');
        getEditShiftLocationsDropDown(clid,addressId);
        editShiftDialog.data('shiftid',shiftid);
        editShiftDialog.data('addressId',addressId);
        editShiftDialog.data('eshiftDate',shiftdate);
        editShiftDialog.data('ecanid',canid);
        editShiftDialog.data('eclid',clid);
        editShiftDialog.data('estid',stid);
        editShiftDialog.data('edid',did);
        editShiftDialog.data('eshiftstart',shiftstart);
        editShiftDialog.data('eshiftend',shiftend);
        editShiftDialog.data('ebreak',ebreak);
        editShiftDialog.data('shiftnote',shiftnote);
        editShiftDialog.data('shiftStatus',shiftStatus);
        editShiftDialog.data('shiftSMSStatus',shiftSMSStatus);
        editShiftDialog.data('eempName',empName);
        editShiftDialog.dialog("open");
        jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
        editShiftDialog.dialog("option", "title", 'Edit Shift On '+shiftdate);
        var target = $(this);
        editShiftDialog.dialog('option', 'position', {
            my: 'top', at: 'top',of: target
        });
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
        rowCandidateId = $("#"+targetRowId);
        if(rowCandidateId.length) {
            //$('html, body').animate({scrollTop: rowCandidateId.offset().top}, 'slow');
        }
    });

    var eerrorClass = 'invalid';
    var eerrorElement = 'em';
    $("#editshiftFrm").validate({
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
            eshiftStart: {
                required: true
            },
            eshiftEnd: {
                required: true
            }
        },
        messages: {
            eshiftStart:{
                required: "Please enter a note"
            },
            eshiftEnd:{
                required: "Please enter a note"
            }
        },
        submitHandler: function (form) {
            var shiftid = $('#shiftid').val();
            var eshDate = $('#eshiftDate').val();
            var eclid = $('#eclid').val();
            var estid = $('#estid').val();
            var edid = $('#edid').val();
            var ecanid = $('#ecanid').val();
            var eshiftEnd = $('#eshiftEnd').val();
            var eshiftStart = $('#eshiftStart').val();
            var eworkBreak = $('#ebreak').val();
            var enote = $('textarea#enote').val();
            var shiftStatus = $('input[name=shiftStatus]:checked', '#editshiftFrm').val();
            var candidateId = '';
            var addressId = $('#eshiftLocation :selected').val();
            var eshiftCallStatus = $('#eshiftCallStatus :selected').val();
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "updateShift.php",
                type:"POST",
                dataType:"json",
                data: {shiftid : shiftid, eshDate : eshDate, eclid : eclid, estid : estid, edid : edid, ecanid : ecanid, eshiftStart : eshiftStart, eshiftEnd : eshiftEnd, eworkBreak : eworkBreak, enote : enote, shiftStatus : shiftStatus,addressId:addressId,eshiftCallStatus:eshiftCallStatus},
                success: function(data){
                    $.each(data, function(index, element) {
                        if(element.status=='shiftOverlap'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            $('.erMsg').html('');
                            $('.erMsg').html('Shift Overlaps existing');
                        }else if(element.status=='shiftUpdated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            editShiftDialog.dialog("close");
                        }else if(element.status=='maxShifts'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            $('.erMsg').html('');
                            $('.erMsg').html('Employee is having two shifts in same date');
                        }else if(element.status == ''){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            editShiftDialog.dialog("close");
                        }else if(element.status=='VisaExpired') {
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            $('.erMsg').html('');
                            $('.erMsg').html('Candidate Visa Expired');
                        }else if(element.status=='NoJobCode'){
                            console.log('NO JOBCODE'+element.status);
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody(eclid,estid,edid,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            $('.erMsg').html('');
                            $('.erMsg').html('No JobCode Found');
                            $('.erMsg').html('No JobCode Found');
                        }else{
                            console.log('UPDATE ERROR'+element.status);
                        }
                    });
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    function removeShift(shiftid,clientId,stateId,deptId){
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "removeShift.php",
            type:"POST",
            dataType:"json",
            data: {shiftid : shiftid},
            success: function(data){
                $.each(data, function(index, element) {
                    if(element.status=='removed'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody(clientId,stateId,deptId,$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        editShiftDialog.dialog("close");
                    }
                });
            }
        });
    }
    $('#shiftStatus').hide();
    editShiftDialog = $("#editshiftPopup").dialog({
        autoOpen: false,
        height: 400,
        width: 550,
        modal: true,
        open: function(event, ui) {
            var shiftid = $('#shiftid').val(editShiftDialog.data('shiftid'));
            var eshiftDate = $('#eshiftDate').val(editShiftDialog.data('eshiftDate'));
            var eclid = $('#eclid').val(editShiftDialog.data('eclid'));
            //console.log('EXADDRESSID'+editShiftDialog.data('addressId'));
            var estid = $('#estid').val(editShiftDialog.data('estid'));
            var edid = $('#edid').val(editShiftDialog.data('edid'));
            var ecanid = $('#ecanid').val(editShiftDialog.data('ecanid'));
            var eempName = $('#eempName').html(editShiftDialog.data('eempName'));
            var eshiftStart = $('#eshiftStart').val(editShiftDialog.data('eshiftstart'));
            var eshiftEnd = $('#eshiftEnd').val(editShiftDialog.data('eshiftend'));
            var ebreak = $('#ebreak').val(editShiftDialog.data('ebreak'));
            var enote = $('textarea#enote').val(editShiftDialog.data('shiftnote'));//$('#enote').text(editShiftDialog.data('shiftnote'));
            $('#eshiftStart').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});//'disableTextInput':true
            $('#eshiftEnd').timepicker({'step': 15 , 'timeFormat': 'H:i'});
            /*
            if(editShiftDialog.data('shiftSMSStatus')==1){
                  $('.confirmBox').show();
            }else{
                  $('.confirmBox').hide();
            }
            */
            if(editShiftDialog.data('shiftStatus')=='CONFIRMED'){
                $('#shiftStatus').prop('checked', true);
            }else{
                $('#shiftStatus').prop('checked', false);
            }
            $("#editshiftPopup").css({'overflow':'hidden'});
        },
        buttons: {
            Save: function(){
                $('#shStatus').val('');
                $('#editshiftFrm').submit();
            },
            Delete: function(){
                $('#shStatus').val('');
                removeShift(editShiftDialog.data('shiftid'),editShiftDialog.data('eclid'),editShiftDialog.data('estid'),editShiftDialog.data('edid'));
            },
            CancelShift: function() {
                $('#shStatus').val('');
                cancelShift($('#shiftid').val(),'CANCELLED',$('textarea#enote').val(),$('#econsultant').val(),editShiftDialog.data('eclid'),editShiftDialog.data('estid'),editShiftDialog.data('edid'));
                editShiftDialog.dialog("close");
            },
            Sick: function() {
                $('#shStatus').val('');
                Sick($('#shiftid').val(),'SICK',$('textarea#enote').val(),$('#econsultant').val(),editShiftDialog.data('eclid'),editShiftDialog.data('estid'),editShiftDialog.data('edid'));
                editShiftDialog.dialog("close");
            }
        }
    });

    function Sick(shiftid,shiftStatus,shiftNote,consultant,clientId,stateId,deptId) {
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "saveSick.php",
            type:"POST",
            dataType:"text",
            data: {shiftid : shiftid,shiftStatus : shiftStatus,shiftNote :shiftNote,consultant : consultant},
            success: function(data){
                if(data) {
                    generateRosterTableHeader(headerReturn);
                    generateRosterTableBody(clientId,stateId,deptId, $('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                }
            }
        });
    }
    function cancelShift(shiftid,shiftStatus,shiftNote,consultant,clientId,stateId,deptId){
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "cancelShift.php",
            type:"POST",
            dataType:"text",
            data: {shiftid : shiftid,shiftStatus : shiftStatus,shiftNote :shiftNote,consultant : consultant},
            success: function(data){
                if(data) {
                    generateRosterTableHeader(headerReturn);
                    generateRosterTableBody(clientId,stateId,deptId, $('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                }
            }
        });
    }
    /* sms Popup */
    loadSMSCreditBalance();
    function loadSMSCreditBalance(){
        $.ajax({
            url: "balanceCheck.php",
            type: "POST",
            dataType: "html",
            success: function(data) {
                $('.creditBalance').html('');
                $('.creditBalance').html(data);
            }
        });
    }
    function loadRecipients(cid,attempt){
        $.ajax({
            url: "smsList.php",
            type: "POST",
            dataType: "html",
            data: { cid : cid,attempt : attempt},
            success: function(data) {
            }
        }).done(function (data) {
            $('.recipients').html('');
            $('.recipients').html(data);
            $('.nRecipients').html('');
            $('.nRecipients').html($('#recipientCount').val());
        });
    }
    function loadBulkRecipients(chkArray,attempt){
        $.ajax({
            url: "smsList.php",
            type: "POST",
            dataType: "html",
            data: { chkArray : chkArray,attempt : attempt},
            success: function(data) {
            }
        }).done(function (data) {
            $('.recipients').html('');
            $('.recipients').html(data);
            $('.nRecipients').html('');
            $('.nRecipients').html($('#recipientCount').val());
        });
    }
    function updateShiftSMS(shiftid){
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "updateShiftSMS.php",
            type: "POST",
            dataType: 'json',
            data: { shiftid : shiftid},
            success: function(data) {
                $.each(data, function(index, element) {
                    if(element.status == 'Updated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        smsDialog.dialog("close");
                    }else if(element.status == 'AlreadyUpdated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        smsDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsDialog.dialog("close");
                    }
                });
            }
        });
    }
    var defaultSMSAcc = 1;
    smsSupportInfo(defaultSMSAcc);
    function smsSupportInfo(smsAccount){
        $.ajax({
            url: "getSMSAccountInfo.php",
            type: "POST",
            dataType: "html",
            data: {smsAccount : smsAccount},
            success: function(data) {
                $(".supportInfo").html('');
                $(".supportInfo").html(data);
            }
        });
    }

    smsDialog = $("#smsPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rCanId').val(smsDialog.data('rCanId'));
            $('#shiftid').val(smsDialog.data('shiftid'));
            $(document).on('change','#smsAccount',function () {
                var smsAccount = $('#smsAccount option:selected').val();
                smsSupportInfo(smsAccount);
            });
        },
        close: function(event, ui){
            $('#smsText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
            loadRecipients(1,0);
        }
    });

    $(document).on('click','.smsShiftLink', function(){
        var rCanId = $(this).closest('td').attr('data-canid');
        var shiftid = $(this).closest('div').attr('data-shiftid');
        var shiftdate = $(this).closest('div').attr('data-shiftdate');
        var shiftstart = $(this).closest('div').attr('data-shiftstart');
        var shiftend = $(this).closest('div').attr('data-shiftend');
        var client = $(this).closest('div').attr('data-client');
        var clientId = $(this).closest('div').attr('data-clid');
        var shiftDay = $(this).closest('div').attr('data-shiftday');
        var did = $(this).closest('div').attr('data-did');
        var consultant = $('#conid').val();
        smsDialog.data('rCanId',rCanId);
        smsDialog.data('shiftid',shiftid);
        smsDialog.data('shiftdate',shiftdate);
        smsDialog.data('shiftstart',shiftstart);
        smsDialog.data('shiftend',shiftend);
        smsDialog.data('clientName',client);
        smsDialog.data('shiftday',shiftDay);
        $.ajax({
            url: "getShiftTimeInfo.php",
            type: "POST",
            dataType: "text",
            data: {shiftdate : shiftdate, client : client, clientId :clientId,did:did, shiftDay : shiftDay, shiftstart : shiftstart, shiftend : shiftend, consultant : consultant,rCanId:rCanId},
            success: function(data){
            }
        }).done(function(data){
            $('#smsText').html('');
            $('#smsText').html(data);
            loadRecipients(rCanId,0);
            smsDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsDialog.dialog("option", "title", 'Send SMS');
        });
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
    });
    var smserrorClass = 'invalid';
    var smserrorElement = 'em';
    var smsFrm = $("#frmNewSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#act').val();
            var alertMe = $('input[name=alertMe]:checked', '#frmNewSMS').val();
            var smsAccount = $('#smsAccount option:selected').val();
            var smsText = $('textarea#smsText').val();
            var rcanid = $('#rCanId').val();
            var shiftid = $('#shiftid').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        updateShiftSMS(shiftid);
                        $('#smsText').html('');
                        smsDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsDialog.dialog("close");
                    }
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    smsAllDialog = $("#smsAllPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rAllCanId').val(smsAllDialog.data('rCanId'));
            $(document).on('change','#smsAllAccount',function () {
                var smsAccount = $('#smsAllAccount option:selected').val();
                smsSupportInfo(smsAccount);
            });
        },
        close: function(event, ui){
            $('#smsAllText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
            loadRecipients(1,0);
        }
    });
    smsCovDialog = $("#smsCovPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rCovCanId').val(smsCovDialog.data('rCanId'));
        },
        close: function(event, ui){
            $('#smsCovText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendCovBtn', function(){
        $('#smsCovText').html('');
        smsCovDialog.data('smsCovText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var strdate = $(this).closest('td').attr('data-strdate');
        var enddate = $(this).closest('td').attr('data-enddate');
        var consultant = $('#consultant').val();
        smsCovDialog.data('rCovCanId',rCanId);

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        $.ajax({
            url: "getShiftId.php",
            type: "POST",
            dataType: "json",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
            success: function(data){
            }
        }).done(function(data){
            //smsAllDialog.data('allShiftId','');
            smsCovDialog.data('covShiftId',data);
        });
        var action = 'COVIDCHECK';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate,action:action},
            success: function(data){
            }
        }).done(function(data){
            $('#smsCovText').html('');
            $('#smsCovText').html(data);
            smsCovDialog.data('smsCovText','');
            smsCovDialog.data('smsCovText',data);
            loadRecipients(rCanId,0);
            smsCovDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsCovDialog.dialog("option", "title", 'Send COVID Symptoms SMS');
        });
    });
    smsVaccDialog = $("#smsVaccPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rVaccCanId').val(smsVaccDialog.data('rCanId'));
        },
        close: function(event, ui){
            $('#smsVaccText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendVaccBtn', function(){
        $('#smsVaccText').html('');
        smsVaccDialog.data('smsVaccText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var strdate = $(this).closest('td').attr('data-strdate');
        var enddate = $(this).closest('td').attr('data-enddate');
        var consultant = $('#consultant').val();
        smsVaccDialog.data('rVaccCanId',rCanId);

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        $.ajax({
            url: "getShiftId.php",
            type: "POST",
            dataType: "json",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
            success: function(data){
            }
        }).done(function(data){
            smsVaccDialog.data('vaccShiftId',data);
        });
        var action = 'VACCINE';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate,action:action},
            success: function(data){
            }
        }).done(function(data){
            $('#smsVaccText').html('');
            $('#smsVaccText').html(data);
            smsVaccDialog.data('smsVaccText','');
            smsVaccDialog.data('smsVaccText',data);
            loadRecipients(rCanId,0);
            smsVaccDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsVaccDialog.dialog("option", "title", 'Send Vaccination SMS');
        });
    });

    smsOHSDialog = $("#smsOHSPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rOHSCanId').val(smsOHSDialog.data('rOHSCanId'));
        },
        close: function(event, ui){
            $('#smsOHSText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendOHSBtn', function(){
        $('#smsOHSText').html('');
        smsOHSDialog.data('smsOHSText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');

        var consultant = $('#consultant').val();
        var clientId = $(this).closest('td').attr('data-clid');
        var stateId = $(this).closest('td').attr('data-stid');
        var deptId = $(this).closest('td').attr('data-did');
        var positionId = $('#expPosition :selected').val();
        smsOHSDialog.data('rOHSCanId',rCanId);
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        var action = 'OHS';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId:rCanId,consultant:consultant,clientId:clientId,positionId:positionId,stateId:stateId,deptId:deptId,action:action},
            success: function(data){
            }
        }).done(function(data) {
            $('#smsOHSText').html('');
            $('#smsOHSText').html(data);
            smsOHSDialog.data('smsOHSText', '');
            smsOHSDialog.data('smsOHSText',data);
            loadRecipients(rCanId, 0);
            smsOHSDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsOHSDialog.dialog("option", "title", 'Send OHS SMS');
        });
    });


    $(document).on('click','.sendFairWorkInfoBtn', function(){
        var canId = $(this).closest('td').attr('data-allcanid');
        var action = 'FairWorkInfo';
        console.log('can id '+canId+'action '+action);
        $.ajax({
            url: "sendFairWorkInfo.php",
            type: "POST",
            dataType: "text",
            data: { canId:canId,action:action},
            success: function(data){
                console.log('>>>>'+data);
                if (data === 'MAILSENT'){
                    alert('Email generated successfully');
                }else{
                    alert('Error generating email');
                }
            }
        }).done(function(data) {
        });
    });

    smsAppVersionDialog = $("#smsAppVersionPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rAppVersionCanId').val(smsAppVersionDialog.data('rAppVersionCanId'));
        },
        close: function(event, ui){
            $('#smsAppVersionText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendAppVersionBtn', function(){
        $('#smsAppVersionText').html('');
        smsAppVersionDialog.data('smsAppVersionText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');

        var consultant = $('#consultant').val();
        var clientId = $(this).closest('td').attr('data-clid');
        var stateId = $(this).closest('td').attr('data-stid');
        var deptId = $(this).closest('td').attr('data-did');
        var positionId = $('#expPosition :selected').val();
        smsAppVersionDialog.data('rAppVersionCanId',rCanId);
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        var action = 'APPVERSION';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId:rCanId,consultant:consultant,clientId:clientId,positionId:positionId,stateId:stateId,deptId:deptId,action:action},
            success: function(data){
            }
        }).done(function(data) {
            $('#smsAppVersionText').html('');
            $('#smsAppVersionText').html(data);
            smsAppVersionDialog.data('smsAppVersionText', '');
            smsAppVersionDialog.data('smsAppVersionText',data);
            loadRecipients(rCanId, 0);
            smsAppVersionDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsAppVersionDialog.dialog("option", "title", 'Send App Version SMS');
        });
    });
    var smsAppVersionFrm = $("#frmAppVersionSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsAppVersionText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsAppVersionText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsOHSText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actOHS').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmAppVersionSMS').val();
            var smsAccount = $('#smsAppVersionAccount option:selected').val();
            var smsText = $('textarea#smsAppVersionText').val();
            var rCanId = $('#rAppVersionCanId').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var consultant = $('#consultant').val();
            var stateId = $('#stateId :selected').val();
            var deptId = $('#departmentId :selected').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsOHSText').html('');
                        smsAppVersionDialog.data('smsAppVersionText','');
                        smsAppVersionDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsAppVersionDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsAppVersionDialog.dialog("close");
                    }
                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
            $.ajax({
                url: "appVersionEmail.php",
                type: "POST",
                dataType: "text",
                data: { rCanId:rCanId, clientId:clientId, positionId:positionId,stateId:stateId,deptId:deptId,consultant:consultant,smsText:smsText},
                success: function(data) {

                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    surveyDialog = $("#smsSurveyPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rSurveyCanId').val(surveyDialog.data('rSurveyCanId'));
        },
        close: function(event, ui){
            $('#smsSurveyText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendSurveyBtn', function(){
        $('#smsSurveyText').html('');
        surveyDialog.data('smsSurveyText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');

        var consultant = $('#consultant').val();
        var clientId = $(this).closest('td').attr('data-clid');
        var stateId = $(this).closest('td').attr('data-stid');
        var deptId = $(this).closest('td').attr('data-did');
        var positionId = $('#expPosition :selected').val();
        surveyDialog.data('rSurveyCanId',rCanId);
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        var action = 'SURVEY';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId:rCanId,consultant:consultant,clientId:clientId,positionId:positionId,stateId:stateId,deptId:deptId,action:action},
            success: function(data){
            }
        }).done(function(data) {
            $('#smsSurveyText').html('');
            $('#smsSurveyText').html(data);
            surveyDialog.data('smsSurveyText', '');
            surveyDialog.data('smsSurveyText',data);
            loadRecipients(rCanId, 0);
            surveyDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            surveyDialog.dialog("option", "title", 'Send Survey SMS');
        });
    });

    sciclunaDialog = $("#smsSciclunaPopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {
            $('#rSciclunaCanId').val(sciclunaDialog.data('rSciclunaCanId'));
        },
        close: function(event, ui){
            $('#smsSciclunaText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
        }
    });
    $(document).on('click','.sendSciclunaBtn', function(){
        $('#smsSciclunaText').html('');
        sciclunaDialog.data('smsSurveyText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');
        var consultant = $('#consultant').val();
        var clientId = $(this).closest('td').attr('data-clid');
        var stateId = $(this).closest('td').attr('data-stid');
        var deptId = $(this).closest('td').attr('data-did');
        var positionId = $('#expPosition :selected').val();
        sciclunaDialog.data('rSciclunaCanId',rCanId);
        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
        var action = 'SCICLUNA';
        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId:rCanId,consultant:consultant,clientId:clientId,positionId:positionId,stateId:stateId,deptId:deptId,action:action},
            success: function(data){
            }
        }).done(function(data) {
            $('#smsSciclunaText').html('');
            $('#smsSciclunaText').html(data);
            sciclunaDialog.data('smsSciclunaText', '');
            sciclunaDialog.data('smsSciclunaText',data);
            loadRecipients(rCanId, 0);
            sciclunaDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            sciclunaDialog.dialog("option", "title", 'Send Scicluna SMS');
        });
    });

    $(document).on('click','.sendAllBtn', function(){
        $('#smsAllText').html('');
        smsAllDialog.data('smsAllText','');
        var rCanId = $(this).closest('td').attr('data-allcanid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var strdate = $(this).closest('td').attr('data-strdate');
        var enddate = $(this).closest('td').attr('data-enddate');
        var consultant = $('#consultant').val();
        smsAllDialog.data('rAllCanId',rCanId);

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');

        $.ajax({
            url: "getShiftId.php",
            type: "POST",
            dataType: "json",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
            success: function(data){
            }
        }).done(function(data){
            //smsAllDialog.data('allShiftId','');
            smsAllDialog.data('allShiftId',data);
        });

        $.ajax({
            url: "getShiftData.php",
            type: "POST",
            dataType: "text",
            data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
            success: function(data){
            }
        }).done(function(data){
            $('#smsAllText').html('');
            $('#smsAllText').html(data);
            smsAllDialog.data('smsAllText','');
            smsAllDialog.data('smsAllText',data);
            loadRecipients(rCanId,0);
            smsAllDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            smsAllDialog.dialog("option", "title", 'Send All SMS');
        });
    });

    var smserrorClass = 'invalid';
    var smserrorElement = 'em';
    var smsAllFrm = $("#frmAllSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsAllText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsAllText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsAllText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actAll').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmAllSMS').val();
            var smsAccount = $('#smsAllAccount option:selected').val();
            var smsText = $('textarea#smsAllText').val();
            var rCanId = $('#rAllCanId').val();
            var allshifts = smsAllDialog.data('allShiftId');

            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $.each(allshifts, function(index, element) {
                            updateAllShiftsSMS(element.shiftid);
                        });
                        $('#smsAllText').html('');
                        smsAllDialog.data('smsAllText','');
                        smsAllDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsAllDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsAllDialog.dialog("close");
                    }
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    var smsCovFrm = $("#frmCovSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsCovText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsCovText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsCovText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actCov').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmCovSMS').val();
            var smsAccount = $('#smsCovAccount option:selected').val();
            var smsText = $('textarea#smsCovText').val();
            var rCanId = $('#rCovCanId').val();
            var allshifts = smsCovDialog.data('covShiftId');

            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsCovText').html('');
                        smsCovDialog.data('smsCovText','');
                        smsCovDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsCovDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsCovDialog.dialog("close");
                    }
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    var smsVaccFrm = $("#frmVaccSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsVaccText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsVaccText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsVaccText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actVacc').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmCovSMS').val();
            var smsAccount = $('#smsVaccAccount option:selected').val();
            var smsText = $('textarea#smsVaccText').val();
            var rCanId = $('#rVaccCanId').val();
            var allshifts = smsVaccDialog.data('vaccShiftId');

            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsVaccText').html('');
                        smsVaccDialog.data('smsVaccText','');
                        smsVaccDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsVaccDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsVaccDialog.dialog("close");
                    }
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    var smsOHSFrm = $("#frmOHSSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsOHSText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsOHSText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsOHSText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actOHS').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmOHSSMS').val();
            var smsAccount = $('#smsOHSAccount option:selected').val();
            var smsText = $('textarea#smsOHSText').val();
            var rCanId = $('#rOHSCanId').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var consultant = $('#consultant').val();
            var stateId = $('#stateId :selected').val();
            var deptId = $('#departmentId :selected').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsOHSText').html('');
                        smsOHSDialog.data('smsOHSText','');
                        smsOHSDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsOHSDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsOHSDialog.dialog("close");
                    }
                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
            //console.log('....'+rCanId+'clientId'+clientId+'positionId'+positionId+'stateId'+stateId+'deptId'+deptId);
            $.ajax({
                url: "ohsEmail.php",
                type: "POST",
                dataType: "text",
                data: { rCanId:rCanId, clientId:clientId, positionId:positionId,stateId:stateId,deptId:deptId,consultant:consultant,smsText:smsText},
                success: function(data) {

                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    var smsSurveyFrm = $("#frmSurveySMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsSurveyText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsSurveyText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsOHSText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actOHS').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmOHSSMS').val();
            var smsAccount = $('#smsOHSAccount option:selected').val();
            var smsText = $('textarea#smsSurveyText').val();
            var rCanId = $('#rSurveyCanId').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var consultant = $('#consultant').val();
            var stateId = $('#stateId :selected').val();
            var deptId = $('#departmentId :selected').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsSurveyText').html('');
                        surveyDialog.data('smsSurveyText','');
                        surveyDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        surveyDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        surveyDialog.dialog("close");
                    }
                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
            $.ajax({
                url: "surveyEmail.php",
                type: "POST",
                dataType: "text",
                data: { rCanId:rCanId, clientId:clientId, positionId:positionId,stateId:stateId,deptId:deptId,consultant:consultant,smsText:smsText},
                success: function(data) {
                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    var smsSciclunaFrm = $("#frmSciclunaSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsSciclunaText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsSciclunaText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsSciclunaText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var act = $('#actOHS').val();
            var alertMe = $('input[name=alertAllMe]:checked', '#frmSciclunaSMS').val();
            var smsAccount = $('#smsSciclunaAccount option:selected').val();
            var smsText = $('textarea#smsSciclunaText').val();
            var rCanId = $('#rSciclunaCanId').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var consultant = $('#consultant').val();
            var stateId = $('#stateId :selected').val();
            var deptId = $('#departmentId :selected').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                success: function(data) {
                    if(data == 'MSGSENT'){
                        $('#smsSciclunaText').html('');
                        sciclunaDialog.data('smsSciclunaText','');
                        sciclunaDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        sciclunaDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        sciclunaDialog.dialog("close");
                    }
                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
            $.ajax({
                url: "sciclunaEmail.php",
                type: "POST",
                dataType: "text",
                data: { rCanId:rCanId, clientId:clientId, positionId:positionId,stateId:stateId,deptId:deptId,consultant:consultant,smsText:smsText},
                success: function(data) {

                }
            }).done(function (data) {
                $('#scheduleBtn').trigger('click');
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    function updateAllShiftsSMS(shiftid){
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "updateShiftSMS.php",
            type: "POST",
            dataType: 'json',
            data: { shiftid : shiftid},
            success: function(data) {
                $.each(data, function(index, element) {
                    if(element.status == 'Updated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        smsAllDialog.dialog("close");
                    }else if(element.status == 'AlreadyUpdated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                        smsAllDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsAllDialog.dialog("close");
                    }
                });
            }
        });
    }
    $(document).on('click','.confirmAllBtn', function() {
        var canid = $(this).closest('td').attr('data-allcanid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var strdate = $(this).closest('td').attr('data-strdate');
        var enddate = $(this).closest('td').attr('data-enddate');
        var consultant = $('#consultant').val();

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "confirmAllShifts.php",
            type: "POST",
            dataType: "text",
            data: {canid : canid,clid : clid,stid : stid,did : did,strdate : strdate, enddate : enddate, consultant : consultant},
            success: function(data) {
                if(data){
                    generateRosterTableHeader(headerReturn);
                    generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                }
            }
        });
    });
    $(document).on('click','.deleteAllBtn', function(){
        var canid = $(this).closest('td').attr('data-allcanid');
        var clid = $(this).closest('td').attr('data-clid');
        var stid = $(this).closest('td').attr('data-stid');
        var did = $(this).closest('td').attr('data-did');
        var strdate = $(this).closest('td').attr('data-strdate');
        var enddate = $(this).closest('td').attr('data-enddate');
        var consultant = $('#consultant').val();

        var $row = $(this).closest("tr");
        targetRowId = $row.attr('id');
        var candidateId;
        if($('#employeeName').val() === ''){
            candidateId = '';
        }else{
            candidateId = $('#empSelected').val();
        }
        $.ajax({
            url: "deleteAllShifts.php",
            type: "POST",
            dataType: "text",
            data: {canid : canid,clid : clid,stid : stid,did : did,strdate : strdate, enddate : enddate, consultant : consultant},
            success: function(data) {
                if(data){
                    generateRosterTableHeader(headerReturn);
                    generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                }
            }
        });
    });
    rosterNoteDialog = $("#rosterNotePopup").dialog({
        autoOpen: false,
        height: 500,
        width: 550,
        modal: true,
        open: function(event, ui) {

        }
    });
    $(document).on('click', '.rosterNote', function(){
        var roscanid = $(this).closest('span').attr('data-roscanid');
        $.ajax({
            url: "getRosterNotes.php",
            type: "POST",
            dataType: "text",
            data: {roscanid : roscanid},
            success: function(data) {
                if(data.length > 0){
                    $('#rosterNoteTxt').html('');
                    $('#rosterNoteTxt').html(data);
                    rosterNoteDialog.dialog("open");
                    jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
                    rosterNoteDialog.dialog("option", "title", 'Roster Notes');
                }
            }
        });

    });
    /* Generate Roster Excel Sheet */
    $(document).on('click', '.genExcelBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'CONFIRMED';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid,num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                window.open(data);
            }
        });
    });

    $(document).on('click', '.genExportRosterBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'ROSTER';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid,num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                console.log('data'+data);
                window.open(data);
            }
        });
    });
    $(document).on('click', '.genExportRosterAllBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'ALLROSTER';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid,num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                console.log('data'+data);
                window.open(data);
            }
        });
    });

    $(document).on('click', '.genEverythingBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'EXPORTEV';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid,num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                //console.log('.......'+data);
                window.open(data);
            }
        });
    });
    $(document).on('click', '.genNoAnswerBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'EXPORTNOANSWER';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid,num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                window.open(data);
            }
        });
    });
    $(document).on('click','.tandaRosterBtn',function () {
        tandaConfim.dialog('open');
        /*var param = $('#departmentId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'CONFIRMED';
        console.log('POSITIOn'+param+positionid+'ssss'+num_th+'>>>>'+startDate+endDate+status);
        $.post("tanda/tandaTasks.php",{task:'addShifts',param : param, positionid : positionid, num_th : num_th,startDate : startDate, endDate : endDate, status : status});
        $.ajax({
            url: "tanda/tandaTasks.php",
            type: "POST",
            data: {task:'addShifts',param : param,positionid : positionid, num_th : num_th,startDate : startDate, endDate : endDate, status : status},
            success: function(data) {
                console.log('SHIFTS>>'+data);
            }
        });*/
    });
    /* Generate Roster Excel with Unconfirmed Data */
    $(document).on('click', '.genAllExcelBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'UNCONFIRMED';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId,positionid : positionid, num_th : num_th,startDate : startDate, endDate : endDate,status:status},
            success: function(data) {
                window.open(data);
            }
        });
    });
    /* Generate Roster Excel with Everyones Data */
    $(document).on('click', '.genEveryExcelBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'EVERYONE';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid, num_th : num_th,status:status},
            success: function(data) {
                window.open(data);
            }
        });
    });
    /* Generate Roster Excel with Everyones Data Per Client */
    $(document).on('click', '.genPerClientExcelBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'ALLPERCLIENT';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid, num_th : num_th,status:status},
            success: function(data) {
                window.open(data);
            }
        });
    });
    /* Generate Roster Excel with LASTSHIFTWORKED Data */
    $(document).on('click', '.genLastShiftExcelBtn', function(){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var positionid = $('#expPosition :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var status = 'LASTSHIFTWORKED';
        $.ajax({
            url: "genRosterExcel.php",
            type: "POST",
            dataType: "text",
            data: {clientId:clientId,stateId:stateId,deptId:deptId, positionid : positionid, num_th : num_th,status:status},
            success: function(data) {
                window.open(data);
            }
        });
    });
    /* Check roster by Candidate rows */
    function updateBulkShiftSMS(candidateId){
        var deptId = $('#departmentId :selected').val();
        var clientId = $('#clientId :selected').val();
        var stateId = $('#stateId :selected').val();
        var num_th = $('.rosterTableHead th').length;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        console.log('bulk data....'+clientId+stateId+deptId+startDate+endDate+candidateId);
        $.ajax({
            url: "updateBulkSMS.php",
            type: "POST",
            dataType: 'json',
            data: { clientId:clientId,stateId:stateId,deptId : deptId,num_th:num_th,startDate:startDate,endDate:endDate,candidateId:candidateId},
            success: function(data) {
                console.log('incoming'+data);
                $.each(data, function(index, element) {
                    console.log('response'+element.status);
                    if(element.status == 'Updated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val());
                        smsBulkDialog.dialog("close");
                    }else if(element.status == 'AlreadyUpdated'){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#clientId :selected').val(),$('#stateId :selected').val(),$('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val());
                        smsBulkDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsBulkDialog.dialog("close");
                    }
                });
            }
        });
    }
    smsBulkDialog = $('#smsBulkPopup').dialog({
        autoOpen: false,
        height:500,
        width:550,
        modal:true,
        closeText: false,
        open: function(event,ui){
            //$(".ui-dialog-titlebar-close").hide();
            $(document).on('change','#smsBulkAccount',function () {
                var smsAccount = $('#smsBulkAccount option:selected').val();
                smsSupportInfo(smsAccount);
            });
        },
        close: function(event, ui){
            $('#smsBulkText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
            loadRecipients(1,0);
        }
    });
    firebaseBulkDialog = $('#firebaseBulkPopup').dialog({
        autoOpen: false,
        height:500,
        width:550,
        modal:true,
        closeText: false,
        open: function(event,ui){

        },
        close: function(event, ui){
            $('#firebaseBulkText').html('');
            $(this).dialog("close");
            $(this).find('form')[0].reset();
            loadBulkRecipients(1,0);
        }
    });
    var smserrorClass = 'invalid';
    var smserrorElement = 'em';
    var smsFrm = $("#frmBulkSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsBulkText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsBulkText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsBulkText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var bulkact = $('#bulkact').val();
            var bulkalertMe = $('input[name=bulkalertMe]:checked', '#frmBulkSMS').val();
            var smsBulkAccount = $('#smsBulkAccount option:selected').val();
            var smsBulkText = $('textarea#smsBulkText').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : bulkact, alertMe : bulkalertMe, smsAccount : smsBulkAccount, smsText : smsBulkText},
                success: function(data) {
                    console.log('bulk sms'+chkArray);
                    if(data == 'MSGSENT'){
                        $.each(chkArray,function (index, value) {
                            updateBulkShiftSMS(value);
                        });
                        $('#smsBulkText').html('');
                        smsBulkDialog.dialog("close");
                    }else if(data == 'NORECIPIENTS'){
                        $('.errMsg').html(data);
                        smsBulkDialog.dialog("close");
                    }else{
                        $('.errMsg').html(data);
                        smsBulkDialog.dialog("close");
                    }
                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
    $(document).on('click','#selectAllchRow', function() {
        chkArray = [];
        if($("#selectAllchRow").is(':checked')){
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            $(".chRow:checked").each(function () {
                chkArray.push($(this).val());
            });
        }else {
            $(this).closest('table').find('td input:checkbox').prop('checked', false);
            loadRecipients(1,0);
            loadBulkRecipients(1,0);
        }
    });

    $(document).on('click','.chRow',function () {
        chkArray = [];
        $(".chRow:checked").each(function() {
            chkArray.push($(this).val());
        });
        var count = 0;
        $(".chRow:checkbox:not(:checked)").each(function () {
            //console.log('unchecked checkboxes.....'+$(this).val()+'...'+count++);
            var i = chkArray.indexOf($(this).val());
            if(i != -1) {
                chkArray.splice(i, 1);
            }
            loadRecipients(1,$(this).val());
        });
    });

    $(document).on('click','#bulkSMS',function () {

        if(chkArray.length != 0) {
            var count = 0;
            /*$.each(chkArray, function (index, value) {
                loadRecipients(value, count++);
            });*/
            loadBulkRecipients(chkArray,1);
            /*if(chkArray.length == count) {*/
                smsBulkDialog.dialog("open");
                jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
                smsBulkDialog.dialog("option", "title", 'Send Bulk SMS');
            /*}*/
        }else{
            alert("Please tick employees to send");
            loadBulkRecipients(1,0);
        }
    });
    $(document).on('click','#firebaseBulkPush',function () {
        loadBulkRecipients(1,0);
        if(chkArray.length != 0) {
            var count = 0;
            loadBulkRecipients(chkArray,1);
            firebaseBulkDialog.dialog("open");
            jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
            firebaseBulkDialog.dialog("option", "title", 'Send Bulk Push Notificaitons');
        }else{
            alert("Please tick employees to send");
            loadBulkRecipients(1,0);
        }
    });
    function removeRecipient(cid,sessId){
        $.ajax({
            url: "removeRecipients.php",
            type: "POST",
            dataType: "html",
            data: { cid : cid, sessId : sessId },
            success: function(data) {
                $('.recipients').html('');
                $('.recipients').html(data);
                $('.nRecipients').html('');
                $('.nRecipients').html($('#recipientCount').val());
            }
        });
    }
    $(document).on('click', '.recipientRemove', function(){
        var $row = $(this).closest("tr");
        var cand = $row.find('.cand').data('cand');
        var sessid = $row.find('.sessid').data('sessid');
        removeRecipient(cand,sessid);
    });
    $(document).on('click', '#smsBodyTxt', function(){
        var smsBody = $('#smsBodyTxt :selected').val();
        $('#smsBulkText').text(smsBody);
    });
    $(document).on('click', '.wrkPermit', function(){
        var start_date = $(this).closest('span').data('start-date');
        var end_date = $(this).closest('span').data('end-date');
        var emp_id = $(this).closest('span').data('emp-id');
        var position_id = $(this).closest('span').data('positionid');
        var client_id = $(this).closest('span').data('clientid');
        var dept_id = $(this).closest('span').data('deptid');
        var state_id = $(this).closest('span').data('stateid');
        $.ajax({
            url: "work_permit_pdf.php",
            type: "POST",
            dataType: "text",
            data: { start_date : start_date, end_date : end_date, emp_id : emp_id, position_id : position_id,client_id:client_id,dept_id:dept_id,state_id:state_id},
            success: function(data) {
                alert(data);
            }
        });

    });
    //setInterval(blinker, 8000);
    function calculateSMSCost(no_of_characters,maxLengthPerMessage){
        let check_value = no_of_characters / maxLengthPerMessage;
        let sms_cost = 0;
        let one_sms_cost = 1.3;
        let dollar_value = 0.0290036;
        let no_of_recipients = $('.nRecipients').html();
        if(check_value >= 0 && check_value <= 1) {
            sms_cost = one_sms_cost * dollar_value * no_of_recipients;
        }else if(check_value >= 1 && check_value <= 2) {
            sms_cost = one_sms_cost * 2 * dollar_value * no_of_recipients;
        }else if(check_value >= 2 && check_value <= 3) {
            sms_cost = one_sms_cost * 3 * dollar_value * no_of_recipients;
        }else if(check_value >= 3 && check_value <= 4) {
            sms_cost = one_sms_cost * 4 * dollar_value * no_of_recipients;
        }else if(check_value >= 4 && check_value <= 5) {
            sms_cost = one_sms_cost * 5 * dollar_value * no_of_recipients;
        }else if(check_value >= 5 && check_value <= 6) {
            sms_cost = one_sms_cost * 6 * dollar_value * no_of_recipients;
        }else if(check_value >= 6 && check_value <= 7) {
            sms_cost = one_sms_cost * 7 * dollar_value * no_of_recipients;
        }else if(check_value >= 7 && check_value <= 8) {
            sms_cost = one_sms_cost * 8 * dollar_value * no_of_recipients;
        }else if(check_value >= 8 && check_value <= 9) {
            sms_cost = one_sms_cost * 9 * dollar_value * no_of_recipients;
        }else if(check_value >= 9 && check_value <= 10) {
            sms_cost = one_sms_cost * 10 * dollar_value * no_of_recipients;
        }else if(check_value >= 10 && check_value <= 11) {
            sms_cost = one_sms_cost * 11 * dollar_value * no_of_recipients;
        }
        return sms_cost.toFixed(2);
    }
    $('#smsText').on("input", function() {
        var maxlength = 136;
        var currentLength = $(this).val().length;
        /*if (currentLength >= maxlength) {
            return console.log("You have reached the maximum number of characters.");
        }*/
        $('#smsTextCount').html(currentLength + " characters");
        $('#smsTextCost').html('');
        $('#smsTextCost').html("Cost: $"+calculateSMSCost(currentLength,maxlength));
    });
    $('#smsBulkText').on("input", function() {
        var maxlength = 136;
        var currentLength = $(this).val().length;
        /*if (currentLength >= maxlength) {
            return console.log("You have reached the maximum number of characters.");
        }*/
        $('#smsBulkTextCount').html(currentLength + " characters");
        $('#smsBulkTextCost').html('');
        $('#smsBulkTextCost').html("Cost: $"+ calculateSMSCost(currentLength,maxlength));
    });

    var smserrorClass = 'invalid';
    var smserrorElement = 'em';
    var smsFrm = $("#frmBulkSMS").validate({
        smserrorClass	: errorClass,
        smserrorElement	: errorElement,
        highlight: function(element) {
            $(element).parent().removeClass('state-success').addClass("state-error");
            $(element).removeClass('valid');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("state-error").addClass('state-success');
            $(element).addClass('valid');
        },
        rules: {
            smsBulkText: {
                required: true,
                rangelength:[1,600]
            }
        },
        messages: {
            smsBulkText: {
                rangelength: function(range, input) {
                    return [
                        'You are only allowed between ',
                        range[0],
                        'and ',
                        range[1],
                        ' characters. ',
                        ' You have typed ',
                        $('#smsBulkText').val().length,
                        ' characters'
                    ].join('');
                }
            }
        },
        submitHandler: function (form) {
            var bulkact = $('#bulkact').val();
            var bulkalertMe = $('input[name=bulkalertMe]:checked', '#frmBulkSMS').val();
            var smsBulkAccount = $('#smsBulkAccount option:selected').val();
            var smsBulkText = $('textarea#smsBulkText').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "html",
                data: { act : bulkact, alertMe : bulkalertMe, smsAccount : smsBulkAccount, smsText : smsBulkText},
                success: function(data) {
                    console.log('bulk firebase push'+chkArray);

                }
            });
        },
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });
});