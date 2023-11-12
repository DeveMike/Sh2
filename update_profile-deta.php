<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require 'includes/dbconnect.php';

// Tarkista, onko käyttäjä kirjautunut sisään
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$customerId = $_SESSION['user_id']; // Oletetaan, että 'user_id' on asiakkaan ID

try {
    // Oletetaan, että käytät muuttujaa $pdo tietokantayhteyden luomiseen.
    $stmt = $conn->prepare("SELECT name, email, phone, address FROM Asiakkaat WHERE customer_id = :customerId");
    $stmt->execute(['customerId' => $customerId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Erottele nimi etu- ja sukunimeen
        list($firstName, $lastName) = array_pad(explode(' ', $user['name'], 2), 2, '');
        // Erottele osoite katuosoitteeseen, postinumeroon ja kaupunkiin
        list($streetAddress, $city, $postCode) = array_pad(explode(',', $user['address'], 3), 3, '');
    } else {
        // Käyttäjää ei löytynyt, käsittele tilanne esimerkiksi ohjaamalla virhesivulle
        throw new Exception('Käyttäjää ei löydy tietokannasta.');
    }
} catch (Exception $e) {
    // Käsittele virhe täällä, esimerkiksi näyttämällä virheilmoitus
    echo "Virhe: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lomake</title>
    <link rel="stylesheet" href="update_profile.css">
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Poistetaan viesti sessiosta
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Poistetaan viesti sessiosta
            ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form id="updateForm" action="tallennaProfileTiedot.php" method="post">
            <fieldset disabled>
                <legend>Henkilötiedot</legend>
                <input type="text" name="etunimi" placeholder="Etunimi" value="<?php echo htmlspecialchars($firstName); ?>" static>
                <input type="text" name="sukunimi" placeholder="Sukunimi" value="<?php echo htmlspecialchars($lastName); ?>" static>
                <!-- Henkilötunnus-kenttä puuttuu tietokannasta, joten sitä ei täytetä -->
                <input type="text" name="henkilotunnus" placeholder="Henkilötunnus">
            </fieldset>

            <fieldset>
                <legend>Osoitetiedot</legend>
                <input type="text" name="katuosoite" placeholder="Katuosoite" value="<?php echo htmlspecialchars($streetAddress); ?>">
                <div>
                    <input type="text" name="kaupunki" placeholder="Kaupunki" value="<?php echo htmlspecialchars($city); ?>">
                    <input type="text" name="postinumero" placeholder="Postinumero" value="<?php echo htmlspecialchars($postCode); ?>">
                </div>
            </fieldset>

            <fieldset>
                <legend>Yhteystiedot</legend>
                <input type="email" name="sahkoposti" placeholder="Sähköposti" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                <input type="tel" name="puhelin" placeholder="Puhelin" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </fieldset>

            <fieldset>
                <legend>Salasanan Vaihto</legend>
                <input type="password" name="nykyinenSalasana" placeholder="Nykyinen Salasana">
                <input type="password" name="uusiSalasana" placeholder="Uusi Salasana">
                <input type="password" name="uusiSalasanaUudelleen" placeholder="Toista Uusi Salasana">
                <button type="submit" name="muutaSalasana">Muuta Salasana</button>
            </fieldset>

            <button type="submit" name="tallennaMuutokset" class="tallenna">Tallenna</button>
        </form>
    </div>

    <script>
        window.setTimeout(function() {
            document.querySelector(".alert").style.display = 'none';
        }, 5000); // Viesti katoaa 5 sekunnin kuluttua
    </script>

    <script src="update_profile.js"></script>

    <?php include_once 'footer.php'; ?>
</body>

</html>