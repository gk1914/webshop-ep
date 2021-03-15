<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Center - administrator</title>

<h1>Administrator</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "admin" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>

<p><a href="<?= BASE_URL . "users-salesman" ?>">Seznam prodajalcev</a></p>
<p><a href="<?= BASE_URL . "add-salesman" ?>">Ustvari novega prodajalca</a></p>
