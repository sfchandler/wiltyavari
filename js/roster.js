$(document).ready(function (){

    var start = moment();
    var end = moment();
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
    }, dateCalendar);
    dateCalendar(start, end);
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        if(start.isSame(end)){
            end = moment(start).add(2,'days');
            $('#reportrange').daterangepicker({}, dateCalendar);
        }
    });
    function generateRosterTableHeader(header){
        var row = '';
        for(var headerItem in header){
            row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<span class="shiftCount"></span><br>'+header[headerItem]['headerDate']+'</th>';
        }
        $('.rosterTableHead').html('');
        $('.rosterTableBody').html('');
        $('.rosterTableHead').html('<th class="rosterTableHeaderCellLeftAligned" style="width: 400px;">&nbsp;Roster Scheduling<br><input type="checkbox" name="selectAllchRow" id="selectAllchRow" class="selectAllchRow" value=""/>&nbsp;&nbsp;<button name="bulkSMS" id="bulkSMS" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-phone"></i>Bulk SMS</button>&nbsp;&nbsp;<button name="addBulkShift" id="addBulkShift" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-time"></i>Add Bulk Shift</button>&nbsp;<button name="deleteBulkShift" id="deleteBulkShift" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i>Delete Bulk Shift</button>&nbsp;</th>'+row+'<th class="rosterTableAction">' +
            '<br>&nbsp;<button class="genExportRosterBtn btn btn-info btn-sm roster-button" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i>&nbsp;Exp Roster</button>' +
            '&nbsp;<button class="genExportRosterAllBtn btn btn-warning btn-sm roster-button" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i>&nbsp;Exp AllRoster</button>' +
            '&nbsp;<button class="genEveryExcelBtn btn btn-info btn-sm roster-button" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i> Exp Everyone</button>' +
            '&nbsp;<button class="genLastShiftExcelBtn btn btn-warning btn-sm roster-button" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i> Exp Last Shift</button>' +
            '&nbsp;<button class="genEverythingBtn btn btn-danger btn-sm roster-button" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i> Exp Everything</button><br><br></th>');
        $('.rosterTable').css("width","100%");
        $('.rosterTable').css("overflow","auto");
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
                    lastrow+='<td></td>';
                }
            }
            column++;
        });
        lastrow+='</tr>';
        $("table#tblRoster tbody").prepend(lastrow);
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
    $(document).on('click','.scheduleBtn', function(){
        $('#clientId').val('');
    });
    /*$(document).on('click','.scheduleBtn', function(){
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
    });*/

    $('input[name="stWrkDate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="stWrkDate"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#stWrkDate').val(picker.startDate.format('YYYY-MM-DD'));
    });
});