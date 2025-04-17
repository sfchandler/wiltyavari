<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$html = '';
$dataSet = getClientSurveyLog($mysqli);
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
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
        <div class="content-body no-content-padding">
            <div style="padding-left:10px;">
                <h1>Client Surveys</h1>
            </div>
            <br>
            <div style="width:100%">
                <div style="height: 1200px;">
                    <form id="frmClientSurvey" class="smart-form" method="post">
                        <div class="selectPanel">
                            <fieldset class="smart-form">
                                <div class="row">
                                    <section class="col col-2">
                                        <label for="client_id">Client:</label>
                                        <select name="client_id" id="client_id" class="form-control">
                                        </select>
                                    </section>
                                    <section class="col col-2">
                                        <label for="client_name">Name:</label>
                                        <input type="text" name="client_name" id="client_name" class="form-control" placeholder="client name" required/>
                                    </section>
                                    <section class="col col-2">
                                        <label for="client_position">Position:</label>
                                        <input type="text" name="client_position" id="client_position" class="form-control" placeholder="client position" required/>
                                    </section>
                                    <section class="col col-2">
                                        <label for="client_email">Client Email:</label>
                                        <input type="email" name="client_email" id="client_email" class="form-control" placeholder="client email address" required/>
                                    </section>
                                    <section class="col col-3">
                                        <label for="survey_mail_body">Survey mail body text:</label>
                                        <textarea name="survey_mail_body" id="survey_mail_body" cols="30" rows="10" class="form-control" required>Dear [Client's Name],<br><br>At Chandler Personnel, we're dedicated to providing outstanding service and continuously refining our offerings. Your satisfaction is our foremost priority, and we highly value your feedback.<br><br>We would greatly appreciate it if you could spare a few moments to share your thoughts on your recent experience with us. Your feedback and suggestions are essential for us to enhance our services and better cater to your needs in the future.<br><br>You can easily provide your feedback by filling out the short survey linked below:<br><br></textarea>
                                    </section>
                                    <section class="col col-1">
                                        <label for="" style="margin-top: 30px;">&nbsp;</label>
                                        <button type="submit" name="clientSurveyBtn" id="clientSurveyBtn" class="btn btn-sm btn-info" >Send Client Survey</button>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                    <table id="dataTbl" class="table table-striped table-bordered table-responsive" style="font-size: 9pt;">
                        <thead>
                        <tr>
                            <th class="filter">COMPANY</th>
                            <th class="filter">CLIENT NAME</th>
                            <th class="filter">CLIENT EMAIL</th>
                            <th class="filter">CLIENT POSITION</th>
                            <th class="filter">SENT TIME</th>
                            <th class="filter">RECEIVED TIME</th>
                            <th>DOCUMENT</th>
                        </tr>
                        </thead>
                        <tbody class="tblBody">
                        <?php
                        foreach ($dataSet as $data){
                            $html = $html.'<tr>
                        <td>'.$data['client'].'</td>    
                        <td>'.$data['client_name'].'</td>
                        <td>'.$data['client_email'].'</td>
                        <td>'.$data['client_position'].'</td>
                        <td>'.$data['sent_time'].'</td>
                        <td>'.$data['received_time'].'</td>
                        <td><a href="'.$data['filePath'].'" target="_blank">SURVEY DOCUMENT</a></td>
                        </tr>';
                        }
                        echo $html;
                        ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script>
    $(document).ready(function(){
       /* $('#client_name').keyup(function(){
            $('#textarea_client_name').val($('#client_name').val());
        });*/
        $('#dataTbl thead th.filter').each(function() {
            var title = $('#dataTbl thead th').eq($(this).index()).text();
            $(this).html(title+'\n<input type="text" />');
        });

        getAllClients();

        function getAllClients() {
            let action = 'accClients';
            $.ajax({
                url: "getClients.php",
                type: "POST",
                dataType: "html",
                data: {action: action},
                success: function (data) {
                    $('#client_id').html('');
                    $('#client_id').html(data);
                }
            });
        }

        $(document).on('click','#clientSurveyBtn', function (e) {
            e.preventDefault();
            let client_id = $('#client_id :selected').val();
            let client_email = $('#client_email').val();
            let client_name = $('#client_name').val();
            let client_position = $('#client_position').val();
            let survey_mail_body = $('textarea#survey_mail_body').val()
            let action = 'SENDSURVEY';
            if(client_email === ''){
                alert('Please enter client email address');
            }
            if(survey_mail_body === ''){
                alert('Please enter client email body text');
            }
            if((client_email !== '') && (survey_mail_body !== '')) {
                $.ajax({
                    url: "processClientSurvey.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        client_id: client_id,
                        client_email: client_email,
                        client_name: client_name,
                        client_position: client_position,
                        survey_mail_body: survey_mail_body,
                        action: action
                    },
                    success: function (data) {
                        if (data === 'MAILSENT') {
                            alert('Survey email generated successfully');
                        } else {
                            alert('Error generating survey email');
                        }
                        location.reload();
                    }
                });
            }
        });
        var table = $('#dataTbl').DataTable({
            "bPaginate": true,
            "bLengthChange": false, /* show entries off */
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": true,
            "order": [[4, "desc"]],
            "pageLength": 50
        });
        table.columns().eq(0).each(function(colIdx) {
            $('input', table.column(colIdx).header()).on('keyup change', function() {
                table
                    .column(colIdx)
                    .search(this.value)
                    .draw();
            });

            $('input', table.column(colIdx).header()).on('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
</body>

</html>