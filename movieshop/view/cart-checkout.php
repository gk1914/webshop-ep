<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Blagajna</title>

<h1>Zaključi nakupovanje</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "cart" ?>">Košarica</a>
    <a href="<?= BASE_URL . "orders?show=user" ?>">Moja naročila</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>


<p>[
<a href="<?= BASE_URL . "movies" ?>">Nazaj na nakupovanje</a>
]</p>

<?php $total = 0; ?>

<div id="main">
    <?php foreach ($_SESSION["cart"] as $id => $amount): 
        $movie = MovieDB::get(["id" => $id]);
        ?>
        <div class="cart_item">
            <p><b><?= $movie["title"] ?></b></p>
            <p>Količina: <?= $amount ?></p>
            <p>Znesek: <?= $_SESSION["cartPrices"][$id] ?>€</p>
        </div>
    <?php endforeach; ?>
</div>


<p>Skupni znesek: <b><?= array_sum($_SESSION["cartPrices"]) ?>€</b></p>

<form action="<?= BASE_URL . "orders/add" ?>" method="post">
    <button type="submit">Zaključi nakup</button>
</form>
