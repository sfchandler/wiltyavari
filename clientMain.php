<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
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
                <h1>Client Information</h1>
            </div>
            <br>
            <div style="width:100%">
                <div style="padding-left:10px;padding-right:5px;padding-bottom:45px; width:35%; float: left; overflow-y: scroll; height: 1200px; ">
                    <form id="clientFrm" class="smart-form" method="post">
                        <h3>Add Clients/Locations</h3>
                        <hr>
                        <br>
                        <div class="row">
                            <section class="col col-4">Industry Sector
                                <label class="input">
                                    <i class="icon-append fa fa-building-o"></i>
                                    <input type="text" name="industry_sector" id="industry_sector" placeholder="Industry Nature/Industry Sector" class="clientGroup">
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4"><span style="color: red"> *</span>Client Name
                                <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                                    <input type="hidden" id="clientid" name="clientid"/>
                                    <input type="text" name="client" id="client" placeholder="Client Name" class="clientGroup">
                                </label>
                            </section>
                            <section class="col col-4"><span style="color: red"> *</span>Client Code
                                <label class="input"><i class="icon-append fa fa-ticket"></i>
                                    <input type="text" name="clientCode" id="clientCode" placeholder="Client Code" class="clientGroup">
                                </label>
                            </section>
                            <section class="col col-4"><span style="color: red"> *</span>Industry
                                <label class="select">
                                    <select name="industryId" id="industryId" class="select">
                                        <?php echo getIndustryTypesForDropdown($mysqli); ?>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="input"><i class="icon-append fa fa-phone"></i>
                                    <input name="phone" id="phone" value="" placeholder="Phone" class="clientGroup"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="input"><i class="icon-append fa fa-info-circle"></i>
                                    <input name="abn" id="abn" value="" placeholder="ABN" class="clientGroup"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="select">Terms
                                    <select name="terms" id="terms" class="select">
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-8"><span style="color: red"> *</span>WIC
                                <label class="input">
                                    <select name="wic" id="wic" class="clientGroup form-control">
                                        <?php echo getWorkcoverClassifications($mysqli); ?>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4"><span style="color: red"> *</span>Payroll Tax
                                <label class="input"><i class="icon-append fa fa-percent"></i>
                                    <input name="payrolltax" id="payrolltax" value="0.00" class="clientGroup" required/>
                                </label>
                            </section>
                            <section class="col col-4"><span style="color: red"> *</span>Workcover
                                <label class="input"><i class="icon-append fa fa-percent"></i>
                                    <input name="workcover" id="workcover" value="0.00" class="clientGroup" required/>
                                </label>
                            </section>
                            <section class="col col-4"><span style="color: red"> *</span>Super Percentage
                                <label class="input">
                                    <i class="icon-append fa fa-percent"></i>
                                    <input name="super_percentage" id="super_percentage" value="10" placeholder="Super Percentage" class="clientGroup"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4"><span style="color: red"> *</span>MHW Surcharge
                                <label class="input"><i class="icon-append fa fa-percent"></i>
                                    <input name="mhws" id="mhws" value="0.00" class="clientGroup" required/>
                                </label>
                            </section>
                        </div>
                        <h3>Client Defaults</h3>
                        <hr>
                        <br>
                        <div class="row">
                            <section class="col col-4">
                                <label class="select">Invoice Type<span style="color: red"> *</span>
                                    <select name="invoiceType" id="invoiceType" class="select">
                                        <option value="All Jobs">All jobs</option>
                                        <option value="Each Job">Each Job</option>
                                        <option value="Each Employee">Each Employee</option>
                                    </select>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="select">Payment Method
                                    <select name="paymentMethod" id="paymentMethod" class="select">
                                        <option value="Cheque">Cheque</option>
                                        <option value="EFT">EFT</option>
                                        <option value="Cash">Cash</option>
                                        <option value="VISA">VISA</option>
                                        <option value="BankCard">Bank Card</option>
                                        <option value="AMEX">AMEX</option>
                                        <option value="Diners">Diners</option>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="select">GST Payable
                                    <select name="gstPayable" id="gstPayable" class="select">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <header>Verify Authority</header>
                            <section class="col col-4">
                                <label class="label">Terms of Business signed:</label>
                                <input type="checkbox" name="termsOfBusinessSigned" id="termsOfBusinessSigned" checked/>
                                <label>Verfied By:</label>
                                <label class="label">Payroll Tax Authority Signed:</label>
                                <input type="checkbox" name="payrollTaxSigned" id="payrollTaxSigned" checked/>
                                <label>Verfied By:</label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-12"><span style="color: red"> *</span>
                                <label class="input"> <i class="icon-append fa fa-home"></i>
                                    <textarea type="textarea" name="clientAddress" id="clientAddress" placeholder="Client Address" class="clientGroup" rows="5" style="width: 350px;"></textarea>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="street_number_1" class="label"><span style="color: red"> *</span>Street No :</label>
                                <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input class="input" name="street_number_1" id="street_number_1" placeholder="Street No"/>
                                    <b class="tooltip tooltip-bottom-right">Please enter Street No</b></label>
                            </section>
                            <section class="col col-3">
                                <label for="street_name" class="label"><span style="color: red"> *</span>Street Name :</label>
                                <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input class="input" name="street_name" id="street_name" placeholder="Street Name"/>
                                    <b class="tooltip tooltip-bottom-right">Please enter Street Name</b></label>
                            </section>
                            <section class="col col-3">
                                <label for="suburb" class="label"><span style="color: red"> *</span>Suburb:</label>
                                <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input class="input" name="suburb" id="suburb" placeholder="Street Name"/>
                                    <b class="tooltip tooltip-bottom-right">Please enter Suburb</b></label>
                            </section>
                            <section class="col col-3">
                                <label for="state" class="label"><span style="color: red"> *</span>State:</label>
                                <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input class="input" name="state" id="state" placeholder="State"/>
                                    <b class="tooltip tooltip-bottom-right">Please enter State</b></label>
                            </section>
                            <section class="col col-3">
                                <label for="postcode" class="label"><span style="color: red"> *</span>Postcode:</label>
                                <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input class="input" name="postcode" id="postcode" placeholder="Postcode"/>
                                    <b class="tooltip tooltip-bottom-right">Please enter Postcode</b></label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                                <label class="input"> <i class="icon-append fa fa-info"></i>
                                    <input type="text" name="clientReference" id="clientReference" placeholder="Client Reference" class="clientGroup">
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-12" style="width: 100%">
                                <div class="pull-left" style="width: 80%">
                                    <label class="textarea">
                                        <textarea name="clientNote" id="clientNote" placeholder="Client Note" class="textarea" rows="8" style="width: 100%"></textarea>
                                        <i class="icon-append fa fa-info"></i>
                                    </label>
                                </div>
                                <div class="input-group-btn pull-left" style="width: 20%">
                                    <button class="addClientBtn btn btn-primary btn-sm" type="submit" value="AddClient"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;Add Client</button>
                                    <button class="editClientBtn btn btn-primary btn-sm" type="submit" value="UpdateClient"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;Update Client</button>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
                <div style="padding-left:10px; padding-bottom:50px; width:65%; float: left; overflow-y: scroll; height: 1200px;">
                    <div class="clientList" style="font-size: 9pt">
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>Client Code</th>
                                <th>Client</th>
                                <th>Client Address</th>
                                <th>Client Reference</th>
                                <th>Client Note</th>
                                <th>Documents</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="clientBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div><br><br><br>
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
    /************* addressFinder **********/
    (function() {
        var widget, initAddressFinder = function() {
            widget = new AddressFinder.Widget(
                document.getElementById('clientAddress'),
                'DYM7UE36AWQV8F94PKJH',
                'AU', {
                    "address_params": {
                        "gnaf,paf" : "1",
                    }
                }
            );

            widget.on('result:select', function(fullAddress, metaData) {
                document.getElementById("street_number_1").value = metaData.street_number_1;
                document.getElementById("street_name").value = metaData.street;
                document.getElementById("suburb").value = metaData.locality_name;
                document.getElementById("state").value = metaData.state_territory;
                document.getElementById("postcode").value = metaData.postcode;
            });

        }

        function downloadAddressFinder() {
            var script = document.createElement('script');
            script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
            script.async = true;
            script.onload = initAddressFinder;
            document.body.appendChild(script);
        };

        document.addEventListener('DOMContentLoaded', downloadAddressFinder);
        let addr_1 = document.getElementById('address')
    })();
    $(function(){
        $('#client').bind('input', function() {
            var c = this.selectionStart,
                r = /[^a-z0-9 .]/gi,
                v = $(this).val();
            if(r.test(v)) {
                $(this).val(v.replace(r, ''));
                c--;
            }
            this.setSelectionRange(c, c);
        });
        $('.editClientBtn').hide();
        function addClients(clientCode,industryId,industrySector,client,clientAddress,street_number_1,street_name,suburb,state,postcode,clientReference,clientNote,phone,abn,classification,terms,invoiceType,paymentMethod,gstPayable,termsOfBusinessSigned,payrollTaxSigned,payrolltax,workcover,super_percentage,wic,mhws) {
            $.ajax({
                url: "addClient.php",
                type: "POST",
                dataType: "html",
                data: {clientCode:clientCode,industryId:industryId,industrySector:industrySector,client : client, clientAddress : clientAddress,street_number_1:street_number_1,street_name:street_name,suburb:suburb,state:state,postcode:postcode,clientReference :clientReference,clientNote:clientNote,phone:phone,abn:abn,classification:classification,terms:terms,invoiceType:invoiceType,paymentMethod:paymentMethod,gstPayable:gstPayable,termsOfBusinessSigned:termsOfBusinessSigned,payrollTaxSigned:payrollTaxSigned,payrolltax:payrolltax,workcover:workcover, super_percentage:super_percentage,wic:wic,mhws:mhws},
                success: function(data) {
                    //console.log('.....'+data);
                    $('.clientBody').html('');
                    $('.clientBody').html(data);
                    getClients();
                    //location.reload();
                }
            }).done(function(){

            });
        }
        listDepartments();
        retrieveClients();
        getClients();
        listTerms();
        function listTerms(){
            $.ajax({
                url:"getClientTerms.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#terms').html('');
                    $('#terms').html(data);
                }
            });
        }
        function getClients(){
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.clientsMenu').html('');
                    $('.clientsMenu').html(data);
                }
            });
        }
        function retrieveClients(){
            $.ajax({
                url:"retrieveClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.clientBody').html('');
                    $('.clientBody').html(data);
                }
            });
        }
        $(document).on('click','#wic','click',function(){
            $('#workcover').val($('#wic :selected').attr('data-rate'));
        });
        $(document).on('click','#addEmailBtn', function (){
            let $row = $(this).closest("tr");
            let clientId = $row.find('.clid').data('clid');
            let email = $('#clientEmail'+clientId).val();
            let action = 'ADD';
            $.ajax({
                url:"addClientEmail.php",
                type:"POST",
                data:{clientId:clientId,email:email,action:action},
                dataType:"html",
                success: function(data){
                    $('#em'+clientId).html('');
                    $('#em'+clientId).html(data);
                }
            });
        });
        $(document).on('click','.removeEmail', function (){
            let $row = $(this).closest("tr");
            let clientId = $row.find('.clid').data('clid');
            let clEmId = $(this).data('clemid');
            let action = 'DELETE';
            $.ajax({
                url:"addClientEmail.php",
                type:"POST",
                data:{clientId:clientId,clEmId:clEmId,action:action},
                dataType:"html",
                success: function(data){
                    $('#em'+clientId).html('');
                    $('#em'+clientId).html(data);
                }
            });
        });
        $(document).on('click', '.addClientBtn', function(evt) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var clientFrm = $("#clientFrm").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    client: {
                        required:true
                    },
                    clientCode:{
                        required:true
                    },
                    payrolltax:{
                        required:true
                    },
                    workcover:{
                        required:true
                    },
                    super_percentage:{
                        required:true
                    },
                    wic:{
                        required:true
                    },
                    mhws:{
                        required:true
                    },
                    clientAddress: {
                        required:true
                    },
                    postcode:{
                        required:true
                    },
                    street_number_1:{
                        required:true
                    },
                    street_name:{
                        required:true
                    },
                    suburb:{
                        required:true
                    },
                    state:{
                        required:true
                    },
                    invoiceType:{
                        required:true
                    }
                },
                messages: {
                    client: {
                        required: "Please enter Client Name"
                    },
                    clientCode:{
                        required: "Please enter Client Code"
                    },
                    payrolltax:{
                        required:"Please enter payroll tax"
                    },
                    workcover:{
                        required:"Please enter work cover"
                    },
                    super_percentage:{
                        required:"Please enter super percentage"
                    },
                    wic:{
                        required:"Please select work cover industry classification"
                    },
                    mhws:{
                        required:"Please enter mental health well being surcharge percentage"
                    },
                    clientAddress: {
                        required: "Please enter Client Address"
                    },
                    street_number_1:{
                        required:"Please enter street number"
                    },
                    street_name:{
                        required:"Please enter street name"
                    },
                    suburb:{
                        required:"Please enter suburb"
                    },
                    state:{
                        required:"Please enter state"
                    },
                    postcode:{
                        required:"Please enter postcode"
                    },
                    invoiceType:{
                        required: "Please select invoice type"
                    }
                },
                submitHandler: function (form) {
                    let clientCode = $('#clientCode').val();
                    let industryId = $('#industryId :selected').val();
                    let industrySector = $('#industry_sector').val();
                    let client = $('#client').val();
                    let clientAddress = $('textarea#clientAddress').val();
                    let street_number_1 = $('#street_number_1').val();
                    let street_name =  $('#street_name').val();
                    let suburb = $('#suburb').val();
                    let state = $('#state').val();
                    let postcode = $('#postcode').val();
                    let clientReference = $('#clientReference').val();
                    let clientNote = $('textarea#clientNote').val();
                    let phone = $('#phone').val();
                    let abn = $('#abn').val();
                    let classification = $('#classification').val();
                    let terms = $('#terms :selected').val();
                    let invoiceType = $('#invoiceType :selected').val();
                    let paymentMethod = $('#paymentMethod :selected').val();
                    let gstPayable = $('#gstPayable :selected').val();
                    let termsOfBusinessSigned = $('#termsOfBusinessSigned').is(':checked');
                    let payrollTaxSigned = $('#payrollTaxSigned').is(':checked');
                    let payrolltax = $('#payrolltax').val();
                    let workcover = $('#workcover').val();
                    let super_percentage = $('#super_percentage').val();
                    let wic = $('#wic :selected').val();
                    let mhws = $('#mhws').val();
                    addClients(clientCode,industryId,industrySector,client,clientAddress,street_number_1,street_name,suburb,state,postcode,clientReference,clientNote,phone,abn,classification,terms,invoiceType,paymentMethod,gstPayable,termsOfBusinessSigned,payrollTaxSigned,payrolltax,workcover, super_percentage,wic,mhws);
                    getClients();
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

        function listDepartments(){
            $.ajax({
                url:"listDepartments.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.areaBody').html('');
                    $('.areaBody').html(data);
                }
            });
        }

        $(document).on('click','.removeClientBtn', function(){
            var $row = $(this).closest("tr");
            var clientId = $row.find('.clid').data('clid');
            $.ajax({
                url:"removeClient.php",
                type:"POST",
                dataType:"html",
                data: {clientId : clientId},
                success: function(data){
                    retrieveClients();
                    getClients();
                }
            });
        });


        $(document).on('click', '.updateClientBtn', function(){
            $('.editClientBtn').show();
            $('.addClientBtn').hide();
            var $row = $(this).closest("tr");
            var clientCode = $row.find('.clid').data('clcode');
            var clientId = $row.find('.clid').data('clid');
            var industryId = $row.find('.clid').data('industryid');
            var industrySector = $row.find('.clid').data('industrysector');
            var client = $row.find('.clid').data('client');
            var clientAddress = $row.find('.clid').data('claddress');
            var street_number = $row.find('.clid').data('street_number');
            var street_name = $row.find('.clid').data('street_name');
            var suburb = $row.find('.clid').data('suburb');
            var state = $row.find('.clid').data('state');
            var postcode = $row.find('.clid').data('postcode');
            var clientReference = $row.find('.clid').data('clreference');
            var clientNote = $row.find('.clid').data('note');
            var phone = $row.find('.clid').data('phone');
            /*var altPhone = $row.find('.clid').data('altphone');
            var fax = $row.find('.clid').data('fax');*/
            var abn = $row.find('.clid').data('abn');
            var classification = $row.find('.clid').data('classification');
            /* var rating = $row.find('.clid').data('rating');
             var accountManager = $row.find('.clid').data('accountmanager');
             var noOfCasuals = $row.find('.clid').data('noofcasuals');*/
            var terms = $row.find('.clid').data('terms');
            var salesman = $row.find('.clid').data('salesman');
            var invoiceType = $row.find('.clid').data('invoicetype');
            var paymentMethod = $row.find('.clid').data('paymentmethod');
            /*var paymentThreshold = $row.find('.clid').data('paymentthreshold');*/
            var gstPayable = $row.find('.clid').data('gstpayable');
            var termsOfBusinessSigned = $row.find('.clid').data('termsofbusinesssigned');
            var payrollTaxSigned = $row.find('.clid').data('payrolltaxsigned');
            var payrolltax = $row.find('.clid').data('payrolltax');
            var workcover = $row.find('.clid').data('workcover');
            var super_percentage = $row.find('.clid').data('super_percentage');
            var wic = $row.find('.clid').data('wic');
            var mhws = $row.find('.clid').data('mhws');
            $('#clientCode').val(clientCode);
            $('#clientid').val(clientId);
            $('#industryId').val(industryId);
            $('#industry_sector').val(industrySector);
            $('#client').val(client);
            $('#clientAddress').val(clientAddress);
            $('#street_number_1').val(street_number);
            $('#street_name').val(street_name);
            $('#suburb').val(suburb);
            $('#state').val(state);
            $('#postcode').val(postcode);
            $('#clientReference').val(clientReference);
            $('#clientNote').val(clientNote);
            $('#phone').val(phone);
            $('#abn').val(abn);
            $('#classification').val(classification);
            $('#terms').val(terms);
            $('#invoiceType').val(invoiceType);
            $('#paymentMethod').val(paymentMethod);
            $('#gstPayable').val(gstPayable);
            $('#termsOfBusinessSigned').val(termsOfBusinessSigned);
            $('#payrollTaxSigned').val(payrollTaxSigned);
            $('#payrolltax').val(payrolltax);
            $('#workcover').val(workcover);
            $('#super_percentage').val(super_percentage);
            $('#wic').val(wic);
            $('#mhws').val(mhws);
        });
        $(document).on('click','.editClientBtn', function(){
            var errorClass = 'invalid';
            var errorElement = 'em';
            var clientFrm = $("#clientFrm").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    client: {
                        required: "Please enter Client Name"
                    },
                    clientCode:{
                        required: "Please enter Client Code"
                    },
                    clientAddress: {
                        required: "Please enter Client Address"
                    }
                },
                messages: {
                    client: {
                        required: "Please enter Client Name"
                    },
                    clientCode: {
                        required: "Please enter Client Code"
                    },
                    clientAddress: {
                        required: "Please enter Client Address"
                    }
                },
                submitHandler: function (form) {
                    var clCode = $('#clientCode').val();
                    var industryId = $('#industryId :selected').val();
                    var industrySector = $('#industry_sector').val();
                    var clid = $('#clientid').val();
                    var cl = $('#client').val();
                    var claddress = $('#clientAddress').val();
                    var street_number_1 = $('#street_number_1').val();
                    var street_name =  $('#street_name').val();
                    var suburb = $('#suburb').val();
                    var state = $('#state').val();
                    var postcode = $('#postcode').val();
                    var clientReference = $('#clientReference').val();
                    var clientNote = $('textarea#clientNote').val();
                    var phone = $('#phone').val();
                    var abn = $('#abn').val();
                    var classification = $('#classification').val();
                    var terms = $('#terms :selected').val();
                    var invoiceType = $('#invoiceType :selected').val();
                    var paymentThreshold = $('#paymentThreshold').val();
                    var gstPayable = $('#gstPayable :selected').val();
                    var termsOfBusinessSigned = $('#termsOfBusinessSigned').is(':checked');
                    var payrollTaxSigned = $('#payrollTaxSigned').is(':checked');
                    var payrolltax = $('#payrolltax').val();
                    var workcover = $('#workcover').val();
                    var super_percentage = $('#super_percentage').val();
                    var wic = $('#wic :selected').val();
                    var mhws = $('#mhws').val();
                    console.log('wic'+wic);
                    updateClient(clCode,industryId,industrySector,clid,cl,claddress,street_number_1,street_name,suburb,state,postcode,clientReference,clientNote,phone,abn,classification,terms,invoiceType,paymentThreshold,gstPayable,termsOfBusinessSigned,payrollTaxSigned,payrolltax,workcover,super_percentage,wic,mhws);
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });

        });

        function updateClient(clCode,industryId,industrySector,clid,cl,claddress,street_number_1,street_name,suburb,state,postcode,clientReference,clientNote,phone,abn,classification,terms,invoiceType,paymentMethod,gstPayable,termsOfBusinessSigned,payrollTaxSigned,payrolltax,workcover,super_percentage,wic,mhws){
            $.ajax({
                url:"updateClient.php",
                type:"POST",
                dataType:"text",
                data: {clCode:clCode,industryId:industryId,industrySector:industrySector,clid : clid,cl : cl, claddress : claddress,street_number_1:street_number_1,street_name:street_name,suburb:suburb,state:state,postcode:postcode, clientReference : clientReference, clientNote : clientNote,phone:phone,abn:abn,classification:classification,terms:terms,invoiceType:invoiceType,paymentMethod:paymentMethod,gstPayable:gstPayable,termsOfBusinessSigned:termsOfBusinessSigned,payrollTaxSigned:payrollTaxSigned,payrolltax:payrolltax,workcover:workcover,super_percentage:super_percentage,wic:wic,mhws:mhws},
                success: function(data){
                    console.log('errors'+data);
                    if(data){
                        retrieveClients();
                        //location.reload();
                    }
                }
            });
        }


    });
</script>
</body>

</html>