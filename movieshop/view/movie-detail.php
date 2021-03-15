<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Podrobnosti filma</title>

<h1>Podrobni ogled: <?= $movie["title"] ?></h1>

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


<?php if (isset($_SESSION["id"]) && $_SESSION["type"] == 1): ?>
    <input type="button" onclick="window.location='<?= BASE_URL . "movies/edit?id=" . $movie["id"] ?>'" value="Uredi"/>
<?php endif; ?>
    
<ul>
    <li>Naslov: <b><?= $movie["title"] ?></b></li>
    <li>Režiser: <b><?= $movie["director"] ?></b></li>
    <li>Leto: <b><?= $movie["year"] ?></b></li>
    <li>Trajanje: <b><?= $movie["runlength"] ?> min</b></li>
    <li>Cena: <b><?= $movie["price"] ?>€</b></li>
    <li>Opis: <i><?= $movie["description"] ?></i></li>
    <li>Ocena uporabnikov: <b><?= $score ?></b></li>
</ul>

<?php if (isset($_SESSION["id"]) && $_SESSION["type"] == 0): 
    $userScore = ScoreDB::get([
        "movie_id" => $movie["id"],
        "user_id" => $_SESSION["id"]]);
    ?>
    <form action="<?= BASE_URL . "add-score" ?>" method="post">
        <input type="number" name="score" min="1" max="5" value="<?= $userScore ?>"/>
        <input type="hidden" name="movie_id" value="<?= $movie["id"] ?>"/>
        <input type="hidden" name="user_id" value="<?= $_SESSION["id"] ?>"/>
        <button type="submit">Oceni</button>
    </form>
<?php endif; ?>
