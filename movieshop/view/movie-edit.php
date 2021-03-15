<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Urejanje filma</title>

<h1>Urejanje podatkov filma</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>


<div class="edit_div">
    <form action="<?= BASE_URL . "movies/edit" ?>" method="post">
        <input type="hidden" name="id" value="<?= $movie["id"] ?>" />
        <p><label>Naslov: <input type="text" name="title" value="<?= $movie["title"] ?>" autofocus /></label></p>
        <p><label>Režiser: <input type="text" name="director" value="<?= $movie["director"] ?>" /></label></p>
        <p><label>Leto: <input type="number" name="year" value="<?= $movie["year"] ?>" /></label></p>
        <p><label>Trajanje: <input type="number" name="runlength" value="<?= $movie["runlength"] ?>" /></label></p>
        <p><label>Opis: <textarea name="description"><?= $movie["description"] ?></textarea></label></p>
        <p><label>Cena: <input type="number" step="any" name="price" value="<?= $movie["price"] ?>" /></label></p>
        <input type="hidden" name="activated" value="off" />
        <label>Aktiviran? <input type="checkbox" name="activated" <?= (($movie["activated"]) ? "checked" : "") ?>/></label>
        <label><button class="input_btn" type="submit">Posodobi podatke</button></p></label>
    </form>
</div>

<div class="edit_div">
    <form action="<?= BASE_URL . "movies/delete" ?>" method="post">
        <input type="hidden" name="id" value="<?= $movie["id"] ?>"  />
        <label>Odstrani? <input type="checkbox" name="delete_confirmation" /></label>
        <label><button class="input_btn" type="submit">Odstrani film</button></label>
    </form>
</div>
