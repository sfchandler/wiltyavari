<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title> Error Page</title>

    <style>
        .error{
            color: red;
            font-size: 16;
            font-family: "Arial Black", arial-black;
            font-weight: bold;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        #superSignature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
        .vertical {
            border-left: 2px ridge  grey;
            height: 100%;
            position:absolute;
            left: 50%;
        }
    </style>
</head>
<body>
<div id="header" style="padding: 20px 20px 20px 20px">
    <img src="img/logochandler.png" width="277" height="37">
</div>
<div align="center" class="error"><?php if(!empty($_REQUEST['error'])){echo $_REQUEST['error'];} ?></div>
<div class="container">

</div>
<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>