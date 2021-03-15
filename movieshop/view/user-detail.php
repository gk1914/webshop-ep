<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Uporabniški profil</title>

<h1>Profil uporabnika: <?= $user["name"] ?> <?= $user["surname"] ?></h1>

<div class="navbar">
    <?php if (isset($_SESSION["id"]) && $_SESSION["type"] == 0): ?>
        <a href="<?= BASE_URL ?>">Domov</a>
        <a href="<?= BASE_URL . "cart" ?>">Košarica</a>
        <a href="<?= BASE_URL . "orders?show=user" ?>">Moja naročila</a>
        <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
        <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
    <?php elseif (isset($_SESSION["id"]) && $_SESSION["type"] == 1): ?>
        <a href="<?= BASE_URL ?>">Domov</a>
        <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
        <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
        <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
    <?php elseif (isset($_SESSION["id"]) && $_SESSION["type"] == 2): ?>
        <a href="<?= BASE_URL ?>">Domov</a>
        <a href="<?= BASE_URL . "admin" ?>">Upravljaj</a>
        <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
        <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>">Domov</a>
        <a href="<?= BASE_URL . "register" ?>">Registracija</a>
        <a href="<?= BASE_URL . "login" ?>">Prijava</a>
    <?php endif; ?>
</div>


<?php if (isset($_SESSION["id"]) && ($_SESSION["id"] == $user["id"] || $_SESSION["type"]-1 == $user["type"])): ?>
    <input type="button" onclick="window.location='<?= BASE_URL . "users/edit?id=" . $user["id"] ?>'" value="Uredi uporabniške podatke"/>
<?php endif; ?>

<ul>
    <li>E-mail: <b><?= $user["email"] ?></b></li>
    <?php if ($user["type"] == 0): ?>
        <li>Telefonska številka: <b><?= $user["phone"] ?></b></li>
        <li>Poštni naslov: <b><?= $user["post_adress_zipcode"] . " " . UserDB::getCity(["zipcode" => $user["post_adress_zipcode"]])?></b></li>
        <li>Naslov: <b><?= $user["adress"] ?></b></li>
    <?php endif; ?>
    <li>Tip uporabnika: <b><?= USER_TYPE[$user["type"]] ?></b></li>
    <li>Status računa: <b><?= $user["activated"] ? "aktiviran" : "NI aktiviran" ?></li>
</ul>
