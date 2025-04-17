$(document).ready(function (){
    $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });



    let targetRowId;
    let tableContainer = $('div #releaseTable');
    let rowCandidateId = null;
    let start = moment();
    let end = moment(new Date()).add(2,'days');
    let weekday=new Array(7);
    weekday[0]="Sun";
    weekday[1]="Mon";
    weekday[2]="Tue";
    weekday[3]="Wed";
    weekday[4]="Thu";
    weekday[5]="Fri";
    weekday[6]="Sat";
    let headerGlobal = [];
    let headerReturn = [];
    let rosterStartDate = '';
    let rosterEndDate = '';
    let candidateId;
    let addReleaseShiftDialog;
    let addBulkReleaseShiftDialog;
    if($('#employeeName').val() === ''){
        candidateId = '';
    }else{
        candidateId = $('#empSelected').val();
    }
    let wholesale  = 'WHOLESALE';
    let cellcast = 'CELLCAST';
    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();

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
    function generateReleaseTableHeader(header){
        var row = '';
        for(var headerItem in header){
            row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<span class="releaseShiftCount"></span><br>'+header[headerItem]['headerDate']+'</th>';
        }
        $('.releaseTableHead').html('');
        $('.releaseTableBody').html('');
        $('.releaseTableHead').html('<th class="releaseTableHeaderCellLeftAligned" style="width: 400px;">' +
            '<br>&nbsp;&nbsp;<input type="checkbox" name="selectAllrelRow" id="selectAllrelRow" class="selectAllrelRow" value=""/>' +
            '&nbsp;&nbsp;<button name="bulkRelease" id="bulkRelease" class="btn btn-info reverse btn-xs"><i class="fa fa-expand"></i>&nbsp;BULK RELEASE</button>' +
            '</th>'+row+'<th class="releaseTableAction"></th>');
        $('.releaseTable').css("width","100%");
        $('.releaseTable').css("overflow","auto");
    }
    function generateReleaseTableBody(clientId,stateId,deptId,num_th,positionid,candidateId,stWrkDate){
        $.ajax({
            url:"getAllocatedCasuals.php",
            type:"POST",
            dataType: "html",
            data:{clientId:clientId,stateId:stateId,deptId:deptId, num_th : num_th, headerGlobal : headerGlobal, positionid : positionid, candidateId : candidateId,startDate:startDate,endDate:endDate,stWrkDate:stWrkDate},
            success: function(data){
                $('.releaseTableBody').html('');
                $('.releaseTableBody').html(data);
            }
        }).done(function(){
            tableContainer.fixTableHeader();
            rowCandidateId = $("#"+targetRowId);
            if(rowCandidateId.length) {
                tableContainer.animate({scrollTop: rowCandidateId.offset().top - 250}, 'slow');

            }
        });
    }
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
    }
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
    populateClients();

    /*$('#reportrange').daterangepicker({
        autoApply: true,
        startDate: start,
        endDate: end,
        locale:{format : 'YYYY-MM-DD HH:mm:ss'}
    }, dateCalendar);
    dateCalendar(start, end);*/
    $('#rel_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false
    });
    $('input[name="rel_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#rel_date').val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('.timepicker').timepicker({'step': 15 , 'timeFormat': 'H:i'});
    $('.timepicker').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});
    $(document).on('change','#clientId',function(){
        let clientId = $('#clientId :selected').val();
        let action = 'scheduling';
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
                $('#empPosition').html('');
                $('#empPosition').html(data);
            }
        });
        getShiftLocationsDropDown(clientId);
    });
    $(document).on('click','#clientId',function(){
        let clientId = $('#clientId :selected').val();
        let action = 'scheduling';
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
                $('#empPosition').html('');
                $('#empPosition').html(data);
            }
        });
        getShiftLocationsDropDown(clientId);
    });
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
    getReleasedShifts();
    function getReleasedShifts(){
        $.ajax({
            url:"getReleasedShifts.php",
            type:"GET",
            dataType:"html",
            success: function(data){
                $('#rel_shift_display').html('');
                $('#rel_shift_display').html(data);
            }
        }).done(function (){
            releaseTableInit();
        });
    }
    function releaseTableInit(){
        var releaseTable = $('#relTbl').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "order": [[1, "desc"]],
            "pageLength": 5,
            "bDestroy" : true
        });
        $('#relTbl thead th').each(function() {
            var releaseTitle = $('#relTbl thead th').eq($(this).index()).text();
            $(this).html(releaseTitle+'<br><input type="text" size="10"/>');
            releaseTable.columns().eq(0).each(function (colpIdx) {
                $('input', releaseTable.column(colpIdx).header()).on('keyup change', function () {
                    releaseTable
                        .column(colpIdx)
                        .search(this.value)
                        .draw();
                });
                $('input', releaseTable.column(colpIdx).header()).on('click', function (e) {
                    e.stopPropagation();
                });
            });
        });
    }
    $(document).on('click','#releaseBtn', function (){
        let client_id = $('#clientId :selected').val();
        let state_id = $('#stateId :selected').val();
        let dept_id = $('#departmentId :selected').val();
        let position_id = $('#empPosition :selected').val();
        let address_id = $('#shiftLocation :selected').val();
        let rel_date = $('#rel_date').val();
        let rel_start = $('#rel_start').val();
        let rel_end = $('#rel_end').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "processReleaseShift.php",
            type:"POST",
            dataType:"text",
            data: {
                client_id:client_id,
                state_id:state_id,
                dept_id:dept_id,
                position_id:position_id,
                address_id:address_id,
                rel_date:rel_date,
                rel_start:rel_start,
                rel_end:rel_end
            },
            success: function(data){
                $('#msg').html('');
                $('#msg').html('Shift Released');
            }
        }).done(function (){
            $('#relTbl').DataTable({
                "destroy": true,
            });
            getReleasedShifts();
        });
    });
    $(document).on('click','.confirmRelShift', function (){
        let rel_shift_id = $(this).closest('td').data('relshiftid');
        let candidate_id = $(this).closest('tr').find('.relCanId').data('candidateid');
        let action = 'CONFIRMED';
        $.ajax({
            url: "confirmReleaseShifts.php",
            type:"POST",
            dataType:"json",
            data: {
                rel_shift_id:rel_shift_id,
                candidate_id:candidate_id,
                action:action
            },
            success: function(data){
                if(data[0].status === 'shiftAdded'){
                    $('.error').html('');
                    $('.error').html('Shift Added to Roster');
                }else{
                    $('.error').html('');
                    $('.error').html(data[0].status);
                }
            }
        });
    });

});