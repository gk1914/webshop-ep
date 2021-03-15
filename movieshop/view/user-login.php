<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Prijava</title>

<h1>Prijava uporabnika</h1>

<?php if (isset($_SESSION["id"])): ?>

    <p>Uporabnik že prijavljen!</p>
    <input type="button" onclick="window.location='<?= BASE_URL . "logout" ?>'" value="Odjava"/>
    
<?php else: ?>
    
    <p>[<a href="<?= BASE_URL . "register" ?>">Registracija</a> |
        <a href="<?= BASE_URL . "x509-login" ?>">Prijava za osebje</a>]</p>

    <form action="<?= BASE_URL . "login" ?>" method="post">
        <label>E-mail: <input type="text" name="email" value="" /></label>
        <label>Pass: <input type="password" name="pass" value="" /></label>
        <label><button class="input_btn" type="submit" />Prijava</label>
    </form>
    
<?php endif; ?>