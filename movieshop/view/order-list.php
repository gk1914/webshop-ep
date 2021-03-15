<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Pregled naročil</title>

<h1>Naročila</h1>

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
    <?php endif; ?>
</div>


<?php $url = (isset($_SESSION["id"]) && $_SESSION["type"] == 1) ? BASE_URL . "orders/edit" : BASE_URL . "orders"; ?>

<ul>

    <?php foreach ($orders as $order): ?>
        <li><a href="<?= $url . "?id=" . $order["id"] ?>"><?= $order["id"] . ": " . $order["time_of_order"] ?></a></li>
    <?php endforeach; ?>

</ul>
