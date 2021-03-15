<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Ogled naročila</title>

<h1>Podrobnosti naročila</h1>

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


<ul>
    <li>ID naročila: <b><?= $order["id"] ?></b></li>
    <li>Stranka: <b><?= $order["user"] ?></b></li>
    <li>Čas naročila: <b><?= $order["time_of_order"] ?></b></li>
    <li>Stanje naročila: <b><?= $order["state"] ?></b></li>
    <li>Naročilo: 
        <ul>
            <?php foreach ($orderItems as $item): ?>
                <li><b><?= $item["amount"] . " x " . $item["movie_title"] ?></b></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>Skupni znesek: <b><?= $order["price_total"] ?>€</b></li>
</ul>
