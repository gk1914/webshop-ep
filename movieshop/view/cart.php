<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Košarica</title>

<h1>Nakupovalna košarica</h1>

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

<?php 
$cart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];

if ($cart):
    ?>
    <div id="main">
        <?php foreach ($_SESSION["cart"] as $id => $amount): 
            $movie = MovieDB::get(["id" => $id]); 
            ?>
            <div class="cart_item">
                <form action="<?= BASE_URL . "cart" ?>" method="post">
                    <input type="hidden" name="do" value="update_cart" />
                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <p><a href="<?= BASE_URL . "movies?id=" . $movie["id"] ?>">
                        <?= $movie["title"] ?>
                    </a></p>
                    <input type="number" name="amount" min="0" value="<?= $amount ?>" />
                    <button type="submit">Posodobi količino</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="row">
        <div class="horizontal_btn">
            <form action="<?= BASE_URL . "cart" ?>" method="post">
                <input type="hidden" name="do" value="empty_cart" />
                <button type="submit">Izprazni košarico</button>
            </form>
        </div>

        <div class="horizontal_btn">
            <form action="<?= BASE_URL . "cart" ?>" method="post">
                <input type="hidden" name="do" value="checkout" />
                <button type="submit">Na blagajno</button>
            </form>
        </div>
    </div>
    
<?php else: ?>
    
    <p>Košarica je prazna.</p>
    
<?php endif; ?>
    