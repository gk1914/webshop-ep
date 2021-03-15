<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Ustvarjanje stranke</title>

<h1>Ustvarjanje uporabniškega računa - STRANKA</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>


<form action="<?= BASE_URL . "add-customer" ?>" method="post">
    <input type="hidden" name="type" value="0"/>
    <p><label>Ime: <input type="text" name="name" value="<?= $name ?>" autofocus /></label></p>
    <p><label>Priimek: <input type="text" name="surname" value="<?= $surname ?>"/></label></p>
    <p><label>E-mail: <input type="text" name="email" value="<?= $email ?>"/></label></p>
    <p><label>Telefonska številka: <input type="text" name="phone" value="<?= $phone ?>"/></label></p>
    <p><label>Poštna številka: <input type="number" name="post_adress_zipcode" value="<?= $post_adress_zipcode ?>"/></label></p>
    <p><label>Naslov: <input type="text" name="adress" value="<?= $adress ?>"/></label></p>
    <input type="hidden" name="activated" value="0" />
    <p><label>Geslo: <input type="password" name="password" minlength="6"/></label></p>
    <label><button class="input_btn" type="submit">Ustvari nov račun</button></label>
</form>
