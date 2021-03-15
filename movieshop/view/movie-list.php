<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Pregled filmov</title>

<h1>Seznam filmov</h1>

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


<div id="add_movie_btn">
    <?php if (isset($_SESSION["id"]) && $_SESSION["type"] == 1): ?>
        <input type="button" onclick="window.location='<?= BASE_URL . "movies/add" ?>'" value="Dodaj nov film"/>
    <?php endif; ?>
</div>

<div class="list_div">
    <?php foreach ($movies as $movie): ?>
        <div class="div_item">
            <?php if (isset($_SESSION["id"]) && $_SESSION["type"] == 0): ?>
                <form action="<?= BASE_URL . "cart" ?>" method="post">
                    <input type="hidden" name="do" value="add_to_cart"/>
                    <input type="hidden" name="id" value="<?= $movie["id"] ?>"/>
                    <a href="<?= BASE_URL . "movies?id=" . $movie["id"] ?>">
                        <?= $movie["title"] ?> (<?= $movie["year"] ?>)</a>
                    <button class="list_btn" type="submit">V košarico</button>
                </form>
            <span class="thin"><?= $movie["price"] . " €" ?></span>
            <?php else: ?>
                <a href="<?= BASE_URL . "movies?id=" . $movie["id"] ?>">
                    <?= $movie["title"] ?> (<?= $movie["year"] ?>)</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
