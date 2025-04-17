<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
//echo base64_decode($_REQUEST['id']).' '.base64_decode($_REQUEST['clientId']).' '.base64_decode($_REQUEST['stateId']).' '.base64_decode($_REQUEST['deptId']).' '.base64_decode($_REQUEST['positionId']);
//echo validateDocumentSubmission($mysqli,base64_decode($_REQUEST['id']),base64_decode($_REQUEST['clientId']),base64_decode($_REQUEST['stateId']),base64_decode($_REQUEST['deptId']),base64_decode($_REQUEST['positionId']),61);
/*if(validateDocumentSubmission($mysqli,base64_decode($_REQUEST['id']),base64_decode($_REQUEST['clientId']),base64_decode($_REQUEST['stateId']),base64_decode($_REQUEST['deptId']),base64_decode($_REQUEST['positionId']),61)){
    $msg = "Customer Satisfaction Survey submitted";
    header("Location:error.php?error=$msg");
}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <style>
        .error{
            color: red;
        }
        .invalid{
            color: red;
        }
        label{
            font-weight: normal;
        }
        .sign-panel{
            margin: 0 auto;
            padding: 10px 100px 10px 100px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 90%;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
        .table th, .table td {
            border-top: none !important;
        }
        .h3box {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        * {
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            box-sizing:border-box;
        }

        *:before, *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .clearfix {
            clear:both;
        }

        .text-center {text-align:center;}

        a {
            color: tomato;
            text-decoration: none;
        }

        a:hover {
            color: #2196f3;
        }

        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #F5F5F5;
            border: 1px solid #CCC;
            border-radius: 4px;
        }
        .header {
            padding:20px 0;
            position:relative;
            margin-bottom:10px;

        }
        .header:after {
            content:"";
            display:block;
            height:1px;
            background:#eee;
            position:absolute;
            left:30%; right:30%;
        }
        .header h2 {
            font-size:3em;
            font-weight:300;
            margin-bottom:0.2em;
        }
        .header p {
            font-size:14px;
        }
        #a-footer {
            margin: 20px 0;
        }
        .new-react-version {
            padding: 20px 20px;
            border: 1px solid #eee;
            border-radius: 20px;
            box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1);

            text-align: center;
            font-size: 14px;
            line-height: 1.7;
        }
        .new-react-version .react-svg-logo {
            text-align: center;
            max-width: 60px;
            margin: 20px auto;
            margin-top: 0;
        }
        .success-box1 {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        .success-box1 img {
            margin-right:10px;
            display:inline-block;
            vertical-align:top;
        }

        .success-box1 > div {
            vertical-align:top;
            display:inline-block;
            color:#888;
        }
        .success-box2 {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        .success-box2 img {
            margin-right:10px;
            display:inline-block;
            vertical-align:top;
        }

        .success-box2 > div {
            vertical-align:top;
            display:inline-block;
            color:#888;
        }
        .success-box3 {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        .success-box3 img {
            margin-right:10px;
            display:inline-block;
            vertical-align:top;
        }

        .success-box3 > div {
            vertical-align:top;
            display:inline-block;
            color:#888;
        }
        .success-box4 {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        .success-box4 img {
            margin-right:10px;
            display:inline-block;
            vertical-align:top;
        }

        .success-box4 > div {
            vertical-align:top;
            display:inline-block;
            color:#888;
        }
        /* Rating Star Widgets Style */
        .rating-stars ul {
            list-style-type:none;
            padding:0;

            -moz-user-select:none;
            -webkit-user-select:none;
        }
        .rating-stars ul > li.star {
            display:inline-block;

        }
        /* Idle State of the stars */
        .rating-stars ul > li.star > i.fa {
            font-size:2.5em; /* Change the size of the stars */
            color:#ccc; /* Color on idle state */
        }

        /* Hover state of the stars */
        .rating-stars ul > li.star.hover > i.fa {
            color:#FFCC36;
        }

        /* Selected state of the stars */
        .rating-stars ul > li.star.selected > i.fa {
            color:#FF912C;
        }



    </style>
</head>
<body>
<div class="container">
    <br><br>
    <div class="sign-panel">
        <br><br>
        <div>
            <img src="img/chandler_personnel.jpg" width="350" height="30">
        </div>
        <br><br>
        <span style="text-align: center"><div class="h3box"><h3>CANDIDATE SATISFACTION SURVEY</h3></div></span>
        <div id="msg" class="msg error"></div>
        <form id="frmSurveyForm" name="frmSurveyForm" method="post" class="smart-form">
            <div class="row">
                <section class="col col sm-6">
                    <table class="table table-responsive" style="width: 100%">
                        <tbody>
                        <tr>
                            <td>
                                <input type="hidden" name="candidate_name" id="candidate_name" value="<?php echo getCandidateFullName($mysqli,base64_decode($_REQUEST['id'])); ?>" class="form-control" readonly/></td>
                            <td>
                                <input type="hidden" name="id" id="id" value="<?php echo base64_decode($_REQUEST['id']); ?>"/>
                                <input type="hidden" name="cons_id" id="cons_id" value="<?php echo base64_decode($_REQUEST['cons_id']); ?>"/>
                                <input type="hidden" name="clientId" id="clientId" value="<?php echo base64_decode($_REQUEST['clientId']);?>">
                                <input type="hidden" name="stateId" id="stateId" value="<?php echo base64_decode($_REQUEST['stateId']);?>">
                                <input type="hidden" name="deptId" id="deptId" value="<?php echo base64_decode($_REQUEST['deptId']);?>">
                                <input type="hidden" name="positionId" id="positionId" value="<?php echo base64_decode($_REQUEST['positionId']);?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>To rate out of 5, select out of 5 stars</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                1.	How would you rate your experience with your consultant?
                                <br>
                                <section class='rating-widget'>
                                    <!-- Rating Stars Box -->
                                    <br>
                                    <div class='rating-stars text-center'>
                                        <ul id='stars1'>
                                            <li class='star' title='Poor' data-value='1'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Fair' data-value='2'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Good' data-value='3'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Excellent' data-value='4'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='WOW!!!' data-value='5'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class='success-box1'>
                                        <div class='clearfix'></div>
                                        <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                        <div class='text-message'></div>
                                        <div class='clearfix'></div>
                                    </div>
                                    <input type="hidden" id="answer1"/>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                2.	How helpful was your consultant during the recruitment process?
                                <br>
                                <section class='rating-widget'>
                                    <!-- Rating Stars Box -->
                                    <br>
                                    <div class='rating-stars text-center'>
                                        <ul id='stars2'>
                                            <li class='star' title='Poor' data-value='1'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Fair' data-value='2'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Good' data-value='3'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Excellent' data-value='4'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='WOW!!!' data-value='5'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class='success-box2'>
                                        <div class='clearfix'></div>
                                        <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                        <div class='text-message'></div>
                                        <div class='clearfix'></div>
                                    </div>
                                    <input type="hidden" id="answer2"/>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                3.	How do you rate your overall experience with Chandler Personnel?
                                <br>
                                <section class='rating-widget'>
                                    <!-- Rating Stars Box -->
                                    <br>
                                    <div class='rating-stars text-center'>
                                        <ul id='stars3'>
                                            <li class='star' title='Poor' data-value='1'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Fair' data-value='2'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Good' data-value='3'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Excellent' data-value='4'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='WOW!!!' data-value='5'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class='success-box3'>
                                        <div class='clearfix'></div>
                                        <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                        <div class='text-message'></div>
                                        <div class='clearfix'></div>
                                    </div>
                                    <input type="hidden" id="answer3"/>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                4.	How likely are you to refer Chandler Personnel to others?
                                <br>
                                <section class='rating-widget'>
                                    <!-- Rating Stars Box -->
                                    <br>
                                    <div class='rating-stars text-center'>
                                        <ul id='stars4'>
                                            <li class='star' title='Poor' data-value='1'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Fair' data-value='2'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Good' data-value='3'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='Excellent' data-value='4'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                            <li class='star' title='WOW!!!' data-value='5'>
                                                <i class='fa fa-star fa-fw'></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class='success-box4'>
                                        <div class='clearfix'></div>
                                        <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                        <div class='text-message'></div>
                                        <div class='clearfix'></div>
                                    </div>
                                    <input type="hidden" id="answer4"/>
                                </section>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">We appreciate your participation and look forward to hearing from you
                                <br>
                                <br>
                                Candidate care Team
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-8">
                    <br>
                    <button id="surveySubmitBtn" class="surveySubmitBtn btn- btn-lg">Submit</button>
                    <br>
                    <br>
                </section>
            </div>
        </form>

    </div>
    <br><br>
</div>
<br><br><br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script src="js/jquery/2.1.1/jquery.min.js"></script>
<!-- this, preferably, goes inside head element: -->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
<![endif]-->
<!-- Basic Styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
<!-- BOOTSTRAP JS -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- you load jquery somewhere before jSignature...-->
<script src="js/jSignature/jSignature.min.js"></script>

<script type="text/javascript" src="js/jquery.base64.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script>
    $(document).ready(function(){

        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        $.ajaxSetup({
            headers : {
                'CsrfToken': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#stars1 li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });
        $('#stars1 li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
            var ratingValue = parseInt($('#stars1 li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            }
            else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            $('#answer1').val(ratingValue);
            responseMessage(msg,1);
        });


        $('#stars2 li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });
        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });
        $('#stars2 li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
            // JUST RESPONSE (Not needed)
            var ratingValue = parseInt($('#stars2 li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            }
            else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            $('#answer2').val(ratingValue);
            responseMessage(msg,2);
        });

        $('#stars3 li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });
        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });
        $('#stars3 li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
            var ratingValue = parseInt($('#stars3 li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            }
            else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            $('#answer3').val(ratingValue);
            responseMessage(msg,3);
        });

        $('#stars4 li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });
        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });
        $('#stars4 li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
            var ratingValue = parseInt($('#stars4 li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            }
            else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            $('#answer4').val(ratingValue);
            responseMessage(msg,4);
        });

    function responseMessage(msg,question_no) {
        $('.success-box'+question_no).fadeIn(200);
        $('.success-box'+question_no+' div.text-message').html("<span>" + msg + "</span>");
    }

        $(document).on('click','#surveySubmitBtn',function (e) {
            var errorClass = 'invalid';
            var errorElement = 'div';
            var frmSurveyForm = $('#frmSurveyForm').validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules:{
                    answer1:{
                        required:true
                    },
                    answer2:{
                        required:true
                    },
                    answer3:{
                        required:true
                    },
                    answer4:{
                        required:true
                    },

                },
                messages:{
                    answer1:{
                        required: "Please answer this question"
                    },
                    answer2:{
                        required: "Please answer this question"
                    },
                    answer3:{
                        required: "Please answer this question"
                    },
                    answer4:{
                        required: "Please answer this question"
                    },
                },
                submitHandler: function (form) {
                        let answer1 = $.base64.encode($('#answer1').val());
                        let answer2 = $.base64.encode($('#answer2').val());
                        let answer3 = $.base64.encode($('#answer3').val());
                        let answer4 = $.base64.encode($('#answer4').val());
                        let id = $.base64.encode($('#id').val());
                        let cons_id = $.base64.encode($('#cons_id').val());
                    /*let candidate_name = $.base64.encode($('#candidate_name').val());
                    let clientId = $.base64.encode($('#clientId').val());
                    let positionId = $.base64.encode($('#positionId').val());
                    let deptId = $.base64.encode($('#deptId').val());
                    let stateId = $.base64.encode($('#stateId').val());*/
                        $.ajax({
                            url:"./processSurvey.php",
                            type:'POST',
                            dataType:'text',
                            data:{
                                answer1:answer1,
                                answer2:answer2,
                                answer3:answer3,
                                answer4:answer4,
                                id:id,
                                cons_id:cons_id
                                /*candidate_name:candidate_name,
                                clientId:clientId,
                                positionId:positionId,
                                deptId:deptId,
                                stateId:stateId*/
                            },
                            success: function (data) {
                                if(data == 'SUCCESS'){
                                    $('#surveySubmitBtn').hide();
                                    $('.msg').html('');
                                    $('.msg').html('Submission Successful');
                                    $('html, body').animate({scrollTop: '0px'}, 300);
                                }else{
                                    $('.msg').html('');
                                    $('.msg').html('Error Submission Unsuccessful');
                                }
                            },
                            error: function(jqXHR, exception) {
                                if (jqXHR.status === 0) {
                                    console.log('Not connect.\n Verify Network.');
                                } else if (jqXHR.status == 404) {
                                    console.log('Requested page not found. [404]');
                                } else if (jqXHR.status == 500) {
                                    console.log('Internal Server Error [500].');
                                } else if (exception === 'parsererror') {
                                    console.log('Requested JSON parse failed.');
                                } else if (exception === 'timeout') {
                                    console.log('Time out error.');
                                } else if (exception === 'abort') {
                                    console.log('Ajax request aborted.');
                                } else {
                                    console.log('Uncaught Error.\n' + jqXHR.responseText);
                                }
                            }
                        });

                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<div class="modal"></div>
</body>
</html>