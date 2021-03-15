<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Registracija</title>

<h1>Registracija stranke</h1>

<form action="<?= BASE_URL . "register" ?>" method="post">
    <input type="hidden" name="type" value="0"/>
    <p><label>Ime: <input type="text" name="name" value="<?= $name ?>" autofocus /></label></p>
    <p><label>Priimek: <input type="text" name="surname" value="<?= $surname ?>"/></label></p>
    <p><label>E-mail: <input type="text" name="email" value="<?= $email ?>"/></label></p>
    <p><label>Telefonska številka: <input type="text" name="phone" value="<?= $phone ?>"/></label></p>
    <p><label>Poštna številka: <input type="number" name="post_adress_zipcode" value="<?= $post_adress_zipcode ?>"/></label></p>
    <p><label>Naslov: <input type="text" name="adress" value="<?= $adress ?>"/></label></p>
    <input type="hidden" name="activated" value="0" />
    <p><label>Geslo: <input type="text" name="password" minlength="6"/></label></p>
    <label><button class="input_btn" type="submit">Ustvari nov račun</button></label>
</form>
