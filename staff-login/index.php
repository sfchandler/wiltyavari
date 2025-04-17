<?php
session_start();
$_SESSION = array();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Login</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
        <div class="container-sm">
            <div class="row">
                <section class="col-md-12">
                    <fieldset>
                        <legend class="login_title">Employee Login</legend>
                        <span class="error"><?php if(!empty($_REQUEST['msg'])){ echo base64_decode($_REQUEST['msg']);} ?></span>
                        <form name="frmLogin" action="login-check.php" class="" method="post">
                            <div>
                                <input type="hidden" name="username" class="form-control" placeholder="Username" value="<?php echo base64_decode($_REQUEST['username']);?>"/>
                                <input type="hidden" name="action" class="form-control" placeholder="Username" value="<?php echo base64_decode($_REQUEST['action']);?>"/>
                                <input type="hidden" name="shift_id" class="form-control" placeholder="Username" value="<?php echo base64_decode($_REQUEST['shift_id']);?>"/>
                                <input type="hidden" name="shift_date" class="form-control" placeholder="Username" value="<?php echo base64_decode($_REQUEST['shift_date']);?>"/>
                                <input type="hidden" name="client_id" class="form-control" placeholder="Username" value="<?php echo base64_decode($_REQUEST['client_id']);?>"/>

                            </div>
                            <br>
                            <div>
                                <input type="password" name="password" class="form-control" placeholder="Password"/>
                            </div>
                            <br>
                            <div>
                                <button type="submit" name="submit" id="submit" class="btn btn-dark form-control" value="submit">Login</button>
                            </div>
                        </form>
                    </fieldset>
                </section>
            </div>
        </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>