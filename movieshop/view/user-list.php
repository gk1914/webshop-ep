<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Pregled registriranih uporabnikov</title>

<h1>Seznam uporabnikov</h1>

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


<ul>

    <?php foreach ($users as $user): ?>
        <li><a href="<?= BASE_URL . "users?id=" . $user["id"] ?>">
        	<?= $user["name"] . " " . $user["surname"] ?></a><?= " (" . $user["email"] . ")" ?></li>
    <?php endforeach; ?>

</ul>
