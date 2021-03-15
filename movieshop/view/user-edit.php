<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Urejanje uporabnika</title>

<h1>Urejanje uporabniškega profila: <?= $user["name"] ?> <?= $user["surname"] ?></h1>

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


<div class="edit_div">
    <form action="<?= BASE_URL . "users/edit" ?>" method="post">
        <input type="hidden" name="id" value="<?= $user["id"] ?>"/>
        <input type="hidden" name="type" value="<?= $user["type"] ?>"/>
        <p><label>Ime: <input type="text" name="name" value="<?= $user["name"] ?>" autofocus /></label></p>
        <p><label>Priimek: <input type="text" name="surname" value="<?= $user["surname"] ?>"/></label></p>
        <p><label>E-mail: <input type="text" name="email" value="<?= $user["email"] ?>"/></label></p>
        <?php if ($user["type"] == 0): ?>
            <p><label>Telefonska številka: <input type="text" name="phone" value="<?= $user["phone"] ?>"/></label></p>
            <p><label>Poštna številka: <input type="number" name="post_adress_zipcode" value="<?= $user["post_adress_zipcode"] ?>"/></label></p>
            <p><label>Naslov: <input type="text" name="adress" value="<?= $user["adress"] ?>"/></label></p>
        <?php endif; ?>
        <?php if (isset($_SESSION["id"]) && $_SESSION["type"] > $user["type"]): ?>
            <input type="hidden" name="activated" value="off" />
            <p><label>Je aktiviran? <input type="checkbox" name="activated" <?= (($user["activated"]) ? "checked" : "") ?>/></label></p>
        <?php else: ?>
            <input type="hidden" name="activated" value="<?= $user["activated"] ?>" />
        <?php endif; ?>

        <label><button class="input_btn" type="submit">Posodobi podatke</button></label>
    </form>
</div>

<div class="edit_div">
    <form action="<?= BASE_URL . "users/edit-password" ?>" method="post">
        <input type="hidden" name="id" value="<?= $user["id"] ?>"/>
        <p><label>Novo geslo: <input type="password" name="password" minlength="6"/></label></p>
        <label><button class="input_btn" type="submit">Posodobi geslo</button></label>
    </form>
</div>