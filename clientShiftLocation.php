<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/10/2017
 * Time: 10:28 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
/*if($_SESSION['userSession'] != 'Viran' || $_SESSION['userSession'] != 'Ana' || $_SESSION['userSession'] != 'Quin'){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}*/
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
            <div style="padding-left:30px;">
                <h2  class="semi-bold">Clients Shift Based/Locations</h2>
            </div>
            <div style="width:100%">
                <div style="padding-left:20px;padding-bottom:45px; width:45%; float: left;">
                    <form id="clientFrm" class="smart-form" method="post">
                        <header>
                            Add Clients Shift Based/Locations
                        </header>
                        <fieldset class="locationDisplay">
                            <div class="row">
                                <section class="col col-4">
                                    <label class="select"> Client<i class="icon-append fa fa-briefcase"></i>
                                        <input type="hidden" name="id" id="id" value=""/>
                                        <select name="clientId" id="clientId" class="select"></select>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="select"> Location Check<i class="icon-append fa fa-location-arrow"></i>
                                        <select name="location_check" id="location_check" class="select">
                                            <option value="YES" selected>YES</option>
                                            <option value="NO">NO</option>
                                        </select>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-location-arrow"></i>
                                        <input type="text" name="latitude" id="latitude" value="" placeholder="Latitude"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-location-arrow"></i>
                                        <input type="text" name="longitude" id="longitude" value="" placeholder="Longitude"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="textarea"> <i class="icon-append fa fa-home"></i>
                                        <textarea type="textarea" name="shiftAddress" id="shiftAddress" placeholder="Address" class="clientGroup textarea" rows="5" ></textarea>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-info"></i>
                                        <input type="text" name="street" id="street" placeholder="Street" class="clientGroup">
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-info"></i>
                                        <input type="text" name="city" id="city" placeholder="City Address" class="clientGroup">
                                </section>
                                <section class="col col-4">
                                    <label class="select"> <i class="icon-append fa fa-info"></i>
                                        <select name="state" id="state"></select>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-info"></i>
                                        <input type="text" name="suburb" id="suburb" placeholder="Suburb" class="clientGroup">
                                </section>
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-info"></i>
                                        <input type="text" name="country" id="country" placeholder="Country" class="clientGroup" value="Australia">
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-info"></i>
                                        <input type="text" name="postalCode" id="postalCode" placeholder="Postal Code" class="clientGroup">
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-12" style="width: 100%">
                                    <div class="input-group-btn pull-left" style="width: 20%">
                                        <input class="addClientBtn btn btn-primary btn-sm" type="submit" value="AddAddress"/><i class="glyphicon glyphicon-home"></i>
                                        <input class="updateClientBtn btn btn-primary btn-sm" type="submit" value="UpdateAddress"/><i class="glyphicon glyphicon-home"></i>
                                        <input class="resetBtn btn btn-primary btn-sm" type="reset" value="Reset"/><i class="glyphicon glyphicon-home"></i>
                                    </div>
                                </section>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div style="padding-left:20px; padding-bottom:50px; width:55%; float: left; overflow-y: scroll; height: 500px;">
                    <div class="clientList">
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Client</th>
                                <th>Address</th>
                                <th>Street</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Suburb</th>
                                <th>Country</th>
                                <th>Postal Code</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Location Check</th>
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
    $(function(){
        $('.updateClientBtn').hide();
        $(document).on('click','.resetBtn',function () {
            $('#id').val('');
            location.reload(true);
        });
        function addClientLocation(clientId,stateName,shiftAddress,street,city,suburb,country,postalCode,latitude,longitude,location_check) {
            $.ajax({
                url: "addClientShiftLocation.php",
                type: "POST",
                dataType: "html",
                data: {clientId:clientId,stateName:stateName,shiftAddress:shiftAddress,street:street,city:city,suburb:suburb,country:country,postalCode:postalCode,latitude:latitude,longitude:longitude,location_check:location_check},
                success: function(data) {
                    if(data == 'Added') {
                        $('.clientBody').html('');
                        $('.clientBody').html(data);
                        getClientShiftLocations();
                    }
                }
            });
        }
        function updateClientLocation(id,clientId,stateName,shiftAddress,street,city,suburb,country,postalCode,latitude,longitude,location_check){
            $.ajax({
                url: "updateClientShiftLocation.php",
                type: "POST",
                dataType: "html",
                data: {id:id,clientId:clientId,stateName:stateName,shiftAddress:shiftAddress,street:street,city:city,suburb:suburb,country:country,postalCode:postalCode,latitude:latitude,longitude:longitude,location_check:location_check},
                success: function(data) {
                    if(data == 'Updated') {
                        $('.clientBody').html('');
                        $('.clientBody').html(data);
                        getClientShiftLocations();
                    }
                }
            });
        }
        getClients();
        function getClients(){
            var action = 'locations';
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
        getClientShiftLocations();
        function getClientShiftLocations(){
            $.ajax({
                url:"getClientShiftLocations.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.clientBody').html('');
                    $('.clientBody').html(data);
                }
            });
        }
        function getStatesDropDown(clientId){
            $.ajax({
                url:"getStates.php",
                type:"POST",
                data:{clientId:clientId},
                dataType:"html",
                success: function(data){
                    $('#state').html('');
                    $('#state').html(data);
                }
            }).done(function (data) {

            });
        }
        $(document).on('change','#clientId',function(){
            var clientId = $('#clientId :selected').val();
            getStatesDropDown(clientId);
        });
        $(document).on('click','#clientId',function(){
            var clientId = $('#clientId :selected').val();
            getStatesDropDown(clientId);
        });
        $(document).on('click','#editClient', function () {
            $('.addClientBtn').hide();
            var clid = $(this).closest('td').attr('data-clid');
            var id = $(this).closest('td').attr('data-id');
            $('#state').html('');
            getStatesDropDown(clid);
            $.ajax({
                url:"getShiftLocation.php",
                type:"POST",
                data:{id:id},
                dataType:"html",
                success: function(data){
                    data = $.parseJSON(data);
                    $.each(data, function(index, element) {
                        $('#id').val(element.id);
                        $('#clientId').val(element.clientId);
                        $('#latitude').val(element.latitude);
                        $('#longitude').val(element.longitude);
                        $('#shiftAddress').val(element.address);
                        $('#street').val(element.street);
                        $('#city').val(element.city);
                        $('#state').val(element.state);
                        $('#suburb').val(element.sub);
                        $('#country').val(element.country);
                        $('#postalCode').val(element.postalCode);
                        $('#location_check').val(element.location_check);
                        $('.updateClientBtn').show();
                    });
                }
            });
        });
        $(document).on('click', '.updateClientBtn', function(evt) {
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
                    clientId:{
                        required:true
                    },
                    state:{
                        required:true
                    },
                    shiftAddress:{
                        required:true
                    },
                    street:{
                        required:true
                    },
                    city:{
                        required:true
                    },
                    suburb:{
                        required:true
                    },
                    country:{
                        required:true
                    }
                },
                messages: {
                    clientId: {
                        required: "Please select Client"
                    },
                    state:{
                        required: "Please select state"
                    },
                    shiftAddress: {
                        required: "Please enter Client Shift Address"
                    },
                    street:{
                        required: "Please enter Street Name"
                    },
                    city:{
                        required: "Please enter City Name"
                    },
                    suburb:{
                        required: "Please enter Suburb Name"
                    },
                    country:{
                        required: "Please enter Country Name"
                    }
                },
                submitHandler: function (form) {
                    var id = $('#id').val();
                    var clientId = $('#clientId').val();
                    var shiftAddress = $('#shiftAddress').val();
                    var street = $('#street').val();
                    var city = $('#city').val();
                    var suburb = $('#suburb').val();
                    var country = $('#country').val();
                    var stateName = $('#state').val();
                    var postalCode = $('#postalCode').val();
                    var vallatitude = $('#latitude').val().replace(/\,/g,'');
                    $('#latitude').val(vallatitude);
                    var latitude = $('#latitude').val();
                    var vallongitude = $('#longitude').val().replace(/\,/g,'');
                    $('#longitude').val(vallongitude);
                    var longitude = $('#longitude').val();
                    var location_check = $('#location_check :selected').val();
                    updateClientLocation(id,clientId,stateName,shiftAddress,street,city,suburb,country,postalCode,latitude,longitude,location_check);
                    getClientShiftLocations();
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        /*$.validator.addMethod("valueNotEquals", function(value, element, arg){
            return arg !== value;
        }, "Please select Client");*/
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
                    clientId:{
                        required:true
                    },
                    state:{
                      required:true
                    },
                    shiftAddress:{
                        required:true
                    },
                    street:{
                        required:true
                    },
                    city:{
                        required:true
                    },
                    suburb:{
                        required:true
                    },
                    country:{
                        required:true
                    }
                },
                messages: {
                    clientId: {
                        required: "Please select Client"
                    },
                    state:{
                        required: "Please select state"
                    },
                    shiftAddress: {
                        required: "Please enter Client Shift Address"
                    },
                    street:{
                        required: "Please enter Street Name"
                    },
                    city:{
                        required: "Please enter City Name"
                    },
                    suburb:{
                        required: "Please enter Suburb Name"
                    },
                    country:{
                        required: "Please enter Country Name"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId').val();
                    var shiftAddress = $('#shiftAddress').val();
                    var street = $('#street').val();
                    var city = $('#city').val();
                    var suburb = $('#suburb').val();
                    var country = $('#country').val();
                    var stateName = $('#state').val();
                    var postalCode = $('#postalCode').val();
                    var vallatitude = $('#latitude').val().replace(/\,/g,'');
                    $('#latitude').val(vallatitude);
                    var latitude = $('#latitude').val();
                    var vallongitude = $('#longitude').val().replace(/\,/g,'');
                    $('#longitude').val(vallongitude);
                    var longitude = $('#longitude').val();
                    var location_check = $('#location_check :selected').val();
                    addClientLocation(clientId,stateName,shiftAddress,street,city,suburb,country,postalCode,latitude,longitude,location_check);
                    getClientShiftLocations();
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });


    });
</script>
</body>

</html>