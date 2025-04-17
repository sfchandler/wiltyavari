<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo strtoupper(DOMAIN_NAME); ?> CASUAL INDUCTION</title>
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <script src="js/jSignature/jSignature.min.js"></script>
    <!-- Jquery Form Validator -->
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.base64.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
</head>
<body>
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 14px">
    <div style="padding-left: 280px; padding-top: 10px"></div>
<div align="center"><img src="img/logo.png" width="220" alt=""><h2><?php echo strtoupper(DOMAIN_NAME); ?> CANDIDATE INDUCTION</h2></div>
<div align="center">
    Welcome to <?php echo DOMAIN_NAME; ?>! Please read this induction, and it is advised that you keep this document throughout your employment with <?php echo DOMAIN_NAME; ?>.
</div><br/>
<div align="center" style="width: 980px; margin: 0 auto">
<table class="table" width="980px">
    <tbody>
    <tr>
        <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
            INDUCTION INFORMATION
        </td>
    </tr>
    <tr>
        <td>
            <p style="text-align: justify">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ullamcorper facilisis posuere. Praesent rhoncus justo at justo vestibulum, eu interdum orci bibendum. Ut tincidunt rutrum ligula, dignissim mattis quam egestas et. Phasellus fringilla convallis magna, quis consectetur tellus viverra id. Phasellus porta, quam nec rhoncus gravida, tellus ex condimentum elit, ac viverra ex metus nec lectus. Cras eu nunc sed sem laoreet interdum. Vivamus ultricies erat nibh, finibus malesuada ipsum scelerisque quis. Nunc ac ipsum a nunc tempus suscipit. In hendrerit tristique tellus eu ultricies. Praesent turpis erat, gravida congue felis condimentum, sagittis bibendum ex. Fusce pulvinar ligula ut dui sodales, eget venenatis sapien varius. Aenean purus neque, vestibulum eget commodo malesuada, accumsan ac metus. Pellentesque fringilla ante quis sapien fermentum volutpat. Curabitur elementum laoreet velit, eu efficitur turpis dictum nec. Nam suscipit nulla vitae nisi vulputate dictum et interdum lorem.
            </p>
            <p style="text-align: justify">
                Duis quis metus ac ante maximus suscipit id sit amet nisl. Phasellus eget malesuada nisi. Cras quis iaculis urna, in sodales risus. Ut blandit mauris dolor, quis condimentum massa fringilla a. In erat est, vehicula at nibh eu, maximus consectetur arcu. Sed lorem ipsum, fermentum non mi quis, dignissim consequat tortor. Etiam turpis nulla, pulvinar in maximus eu, aliquam nec dolor.
            </p>
            <p style="text-align: justify">
                Mauris tortor tortor, egestas nec arcu id, volutpat scelerisque sapien. Nullam dui ante, lacinia non gravida vel, placerat id odio. Aenean et est sapien. Donec sit amet quam scelerisque, tempor nunc non, consequat erat. In sed efficitur velit. Proin tristique mi in fringilla interdum. Cras eros massa, congue sed nulla eu, vestibulum condimentum erat.
            </p>
            <p style="text-align: justify">
                Vestibulum rutrum malesuada metus, ac lobortis orci tempus sed. Etiam fringilla faucibus felis, a gravida libero vestibulum id. Proin eleifend ultrices dolor, at sodales neque vehicula vitae. Vivamus porta interdum dignissim. Duis aliquam justo magna, ut fermentum urna aliquet nec. Curabitur malesuada condimentum nisi eget varius. Maecenas et tellus ipsum. Donec sit amet justo tempor, finibus nunc vitae, euismod metus. Vivamus posuere placerat nisl ac lobortis. Mauris congue quis metus sed fringilla. Aenean eget lectus luctus, euismod nibh at, laoreet enim. In laoreet nisl nec arcu maximus, sit amet ornare diam fringilla.
            </p>
            <p style="text-align: justify">
                Curabitur et accumsan mauris. Etiam enim felis, tincidunt a cursus sodales, iaculis vel metus. Donec euismod leo nec euismod eleifend. Aenean nec varius urna, ac varius sapien. Pellentesque interdum mi erat, id accumsan ante molestie convallis. Pellentesque non lectus venenatis, semper leo et, eleifend nunc. Donec suscipit sed nisl a laoreet. Vestibulum ac fermentum mauris. Fusce venenatis velit quis erat aliquam, non iaculis mi aliquet. Nam convallis, justo non commodo scelerisque, ex erat rhoncus felis, non pretium odio tellus at velit. Vestibulum felis justo, dictum vel quam vitae, luctus pharetra lacus. Etiam dignissim pretium convallis. Maecenas facilisis enim et nisl condimentum, eu aliquam felis faucibus. Vestibulum efficitur odio nec pulvinar consectetur.
            </p>
            <p style="text-align: justify">
                Donec fermentum mauris nisl, non maximus magna consequat eget. Vivamus vel vehicula nunc, vel faucibus elit. Nunc sollicitudin a sapien nec varius. Nulla congue sem semper eleifend efficitur. Nullam auctor magna vel quam pretium, fringilla cursus sem ullamcorper. Phasellus sollicitudin sagittis ex, sed luctus est. Vestibulum aliquam a turpis tincidunt dignissim.
            </p>
            <p style="text-align: justify">
                Integer mauris libero, ornare a justo nec, fermentum maximus nunc. Sed sodales dignissim sagittis. Cras vitae metus porta, sodales erat non, luctus odio. Pellentesque ullamcorper, augue non lacinia congue, lorem ligula pharetra ligula, at pharetra turpis libero vel neque. In a erat mollis, luctus elit nec, tempor purus. Pellentesque lorem sapien, condimentum sit amet gravida in, ultricies eget urna. Quisque aliquam molestie arcu, ac euismod felis porta quis. In a leo vestibulum, porta arcu congue, laoreet diam. Sed pulvinar leo nec volutpat bibendum.
            </p>
            <p style="text-align: justify">
                In augue lectus, volutpat nec dolor sed, porttitor sagittis diam. Fusce aliquet egestas nunc, vel rutrum ipsum lacinia vitae. Donec vestibulum felis et quam placerat vulputate. Morbi fermentum pulvinar eros id egestas. Quisque a rhoncus leo. Aliquam ac arcu ac quam egestas porta nec at arcu. Aenean consequat ultrices molestie. Nunc nec ligula purus. Donec ac lacinia felis. Etiam erat sapien, blandit vel elementum ut, imperdiet nec purus. Fusce et dui libero. Morbi scelerisque odio nec porttitor condimentum.
            </p>
            <p style="text-align: justify">
                Nullam in suscipit dui. Pellentesque sodales dignissim congue. Morbi quis tristique justo. Nunc tempus ex lorem, sed hendrerit ex viverra eu. Duis euismod urna turpis, sed volutpat nibh tempor eget. Duis non ultrices velit. Proin et lorem quis sem dapibus venenatis. Integer auctor sapien massa, eu laoreet tellus porta eget.
            </p>
            <p style="text-align: justify">
                Nam faucibus mattis nisi, ac sagittis tellus ultrices id. Cras scelerisque ultrices tellus, sed convallis ipsum semper vitae. Suspendisse auctor varius quam, dignissim luctus arcu mattis eget. Maecenas facilisis, sapien nec tempus lacinia, risus purus porta tortor, non tempus risus ex ac leo. Etiam in felis eu quam mollis dapibus. Proin lorem ipsum, fermentum non sapien at, semper luctus dolor. Mauris tristique justo sit amet congue auctor. Vestibulum mattis porttitor risus eget laoreet. Mauris vel libero orci. Donec sed pulvinar felis, at fringilla lorem. Pellentesque semper, nisl in tempor maximus, orci ligula feugiat ipsum, in scelerisque mauris lorem et turpis. Cras nulla ex, viverra pretium eleifend vitae, feugiat et dui. Praesent vel sapien rhoncus neque posuere venenatis. Morbi lobortis dignissim justo, at blandit dui placerat in. Ut consequat vel orci et pellentesque. Praesent at sapien eleifend, accumsan nisl in, aliquet nisl.
            </p>

        </td>
    </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              RELATED INFORMATION FROM FAIR WORK OMBUDSMAN
          </td>
      </tr>
      <tr>
          <td>
              <p style="text-align: justify">
                  Kindly refer following links for your knowledge and understanding
              </p>
              <p><a href="https://www.fairwork.gov.au/sites/default/files/migration/724/casual-employment-information-statement.pdf" target="_blank">https://www.fairwork.gov.au/sites/default/files/migration/724/casual-employment-information-statement.pdf</a></p>
              <p><a href="https://www.fairwork.gov.au/sites/default/files/migration/724/Fair-Work-Information-Statement.pdf" target="_blank">https://www.fairwork.gov.au/sites/default/files/migration/724/Fair-Work-Information-Statement.pdf</a></p>
          </td>
      </tr>

    </tbody>
  </table>
</div>
</div>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>
