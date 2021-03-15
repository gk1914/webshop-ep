<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Center - prodajalec</title>

<h1>Prodajalec</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>

<p><a href="<?= BASE_URL . "orders?show=unprocessed" ?>">Neobdelana naročila</a></p>
<p><a href="<?= BASE_URL . "orders?show=accepted" ?>">Potrjena naročila</a></p>
<p><a href="<?= BASE_URL . "users-customer" ?>">Seznam strank</a></p>
<p><a href="<?= BASE_URL . "add-customer" ?>">Ustvari novo stranko</a></p>
<p><a href="<?= BASE_URL . "movies" ?>">Seznam artiklov</a></p>
