<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Dodajanje filma</title>

<h1>Dodaj nov film</h1>

<div class="navbar">
    <a href="<?= BASE_URL ?>">Domov</a>
    <a href="<?= BASE_URL . "salesman" ?>">Upravljaj</a>
    <a href="<?= BASE_URL . "users?id=" . $_SESSION["id"] ?>">Uporabniški profil</a>
    <a href="<?= BASE_URL . "logout" ?>">Odjava</a>
</div>


<form action="<?= BASE_URL . "movies/add" ?>" method="post">
    <p><label>Naslov: <input type="text" name="title" value="<?= $title ?>" autofocus /></label></p>
    <p><label>Režiser: <input type="text" name="director" value="<?= $director ?>" /></label></p>
    <p><label>Leto: <input type="number" name="year" value="<?= $year ?>" /></label></p>
    <p><label>Trajanje: <input type="number" name="runlength" value="<?= $runlength ?>" /></label></p>
    <p><label>Opis: <textarea name="description"><?= $description ?></textarea></label></p>
    <p><label>Cena: <input type="number" step="any" name="price" value="<?= $price ?>" /></label></p>
    <input type="hidden" name="activated" value=0 />
    <label><button class="input_btn" type="submit">Dodaj film</button></p></label>
</form>
