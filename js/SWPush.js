$(function() {
    setInterval(function () {
        var firstRow = $('#inb-table > tbody > tr:first').attr('id');
        //$("#smsDataTable > tr:last-child").data("data-smsid");
        $.ajax({
            type: "POST",
            url: "SWDataPush.php",
            data: {firstRow: firstRow},
            dataType: "json"
        }).done(function (response) {
            if (!$.trim(response)) {
                getMailCount();
            }else{
                /*response.sort(function(obj1, obj2){

                    var dateA = new Date(a.maildate), dateB = new Date(b.maildate);
                    return dateA-dateB;

                });*/
                console.log('RESPONSE>'+JSON.stringify(response));
                $.each(response.reverse(), function(index, element) {
                    var row = '<tr id="'+element.autoid+'" class="rowId" data-acc="'+element.accname+'"><td class="messageid" data-messageid="'+element.messageid+'"><div class="mFrom">'+element.mailfrom+'</div><div class="subject"><strong>Subj:</strong>&nbsp;'+element.subject+'</div><div class="mTo"><strong>To:</strong>&nbsp;'+element.mailto+'</div></td><td class="categoryStatus"><div id="'+element.autoid+'" class="category">'+element.mailcolor+'</div></td><td align="right" class="mdate" data-mdate="'+element.maildate+'">'+element.maildate+'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'+element.messageid+'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
                    if(($('#searchTxt').val()=='') && ($('#subjectSearchTxt').val() == '') && ($('#fromSearchTxt').val() == '') && ($('#srchCount').val() != 'clicked')) {
                        if(element.autoid != $("tr[id="+element.autoid+"]").attr('id')){
                            console.log('ITERATOR'+element.autoid);
                            //$('#'+firstRow).before(row);
                            $('#inb-table > tbody > tr:first').before(row);
                            //console.log('ID>>>>>>>>'+$("tr[id=" + ((element.autoid)-1) + "]").attr('id'));
                            //$("tr[id=" + ((element.autoid)-1) + "]").before(row);
                            $("tr[id=" + element.autoid + "]").effect('highlight', {
                                color: '#ADFF2F',
                                easing: 'easeInElastic'
                            }, 3000);
                        }
                    }
                });
                getMailCount();
            }
            $("#inbLoading").hide();
        });
    }, 3000);

    function getMailCount(){
        $.ajax({
            type: "POST",
            url: "mailCount.php",
            dataType: "text"
        }).done(function (response) {
            $('.numRows').html(response);
        });
    }
    /* Circling ???? */
    function sortMails(){
        var tableRows = $("#inb-table tbody tr:lt(5)");
        console.log('TABLE >>>>'+tableRows);
        tableRows.each(function(){
            var id = this.id;
            console.log('Dates >>>>>>'+id);
        }).sort(function (a, b) {
            return $(a).closest('td').data('data-mdate') < $(b).closest('td').data('data-mdate');
        }).appendTo('tbody');
    }
});