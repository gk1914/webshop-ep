<?php 
define("BASE_URL", rtrim(rtrim($_SERVER["SCRIPT_NAME"], "/index.php"), "x509-login"));
define("CSS_URL", rtrim(rtrim($_SERVER["SCRIPT_NAME"], "/index.php"), "x509-login") . "static/css/");
?>


<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Prijava</title>

<h1>X509 prijava</h1>

<?php 
$client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");
if ($client_cert == null) {
    die('err: Spremenljivka SSL_CLIENT_CERT ni nastavljena.');
}
?>

<?php if (isset($_SESSION["id"])): ?>

    <p>Uporabnik že prijavljen!</p>
    <input type="button" onclick="window.location='<?= BASE_URL . "logout" ?>'" value="Odjava"/>
    
<?php else: ?>

    <form action="<?= BASE_URL . "authorized-login" ?>" method="post">
        <input type="hidden" name="cert" value="<?= $client_cert ?>" />
        <p><label>E-mail: <input type="text" name="email" value="" /><br /></label></p>
        <p><label>Pass: <input type="password" name="pass" value="" /><br /></label></p>
        <label><button class="input_btn" type="submit">Prijava</button></label>
    </form>
    
<?php endif; ?>