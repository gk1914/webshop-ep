<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Upravljanje naročila</title>

<h1>Upravljanje naročila</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
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

<?php if ($order["state"] == "neobdelano"): ?>
    <div class="row">
        <div class="horizontal_btn">
            <form action="<?= BASE_URL . "orders/edit" ?>" method="post">
                <input type="hidden" name="id" value="<?= $order["id"] ?>" />
                <input type="hidden" name="state" value="potrjeno" />
                <button class="horizontal_btn" type="submit">Potrdi naročilo</button>
            </form>
        </div>
        <div class="horizontal_btn">
            <form action="<?= BASE_URL . "orders/edit" ?>" method="post">
                <input type="hidden" name="id" value="<?= $order["id"] ?>" />
                <input type="hidden" name="state" value="preklicano" />
                <button class="horizontal_btn" type="submit">Prekliči naročilo</button>
            </form>
        </div>
    </div>
<?php elseif ($order["state"] == "potrjeno"): ?>
    <form action="<?= BASE_URL . "orders/edit" ?>" method="post">
        <input type="hidden" name="id" value="<?= $order["id"] ?>" />
        <input type="hidden" name="state" value="stornirano" />
        <button type="submit">Storniraj naročilo</button>
    </form>
<?php endif; ?>
