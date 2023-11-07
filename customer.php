<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    // Jos käyttäjä ei ole kirjautunut sisään, ohjaa kirjautumissivulle
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];
$role = $_SESSION['role']; // Oletan, että rooli on tallennettu 'role'-avaimen alle

require 'includes/dbconnect.php';

// Tarkistetaan, onko käyttäjällä lukemattomia ilmoituksia
$ilmoituksetQuery = "SELECT * FROM ilmoitukset WHERE user_id = ? AND luettu = 0";
$ilmoituksetStmt = $conn->prepare($ilmoituksetQuery);
$ilmoituksetStmt->execute([$user_id]);

$ilmoitukset = $ilmoituksetStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ilmoitukset as $ilmoitus) {
    // Näytä ilmoitus
    echo '<div class="alert alert-info">' . htmlspecialchars($ilmoitus['viesti']) . '</div>';

    // Merkitse ilmoitus luetuksi
    $merkintaQuery = "UPDATE ilmoitukset SET luettu = 1 WHERE ilmoitus_id = ?";
    $merkintaStmt = $conn->prepare($merkintaQuery);
    $merkintaStmt->execute([$ilmoitus['ilmoitus_id']]);
}

if ($role === 'customer') {
    // Näytä asiakkaalle tarkoitettu sisältö
} elseif ($role === 'instructor') {
    // Näytä ohjaajalle tarkoitettu sisältö
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="customer.css">
    <title>Main Page</title>
</head>

<body>
    <nav class="navbar">
        <a href="etusivu.php">
            <img src="assets/Asset 5.svg" alt="Logo" class="logo"></a>
        <ul class="nav-links">
            <li><a href="#">Toimipisteet</a></li>
            <li class="has-dropdown">
                <a href="#">
                    Palvelut
                    <img src="assets/Infolaunch.svg" alt="Icon" class="icon">
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="#">Ryhmäliikunta</a></li>
                        <li><a href="#">Personal Trainer</a></li>
                        <li><a href="#">Vinkit ja Treenit</a></li>
                    </ul>
                </div>
            </li>
            <li class="has-dropdown">
                <a href="#">
                    Jäsennyys
                    <img src="assets/Infolaunch.svg" alt="Icon" class="icon">
                </a>
                <div class="submenu">
                    <ul>
                        <li><a href="#">Hinnasto</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="#">Ota yhteyttä</a></li>
        </ul>
        <div class="buttons">
            <?php
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor') {
                // Ohjaaja on kirjautunut sisään
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="instructor.php" class="join-button">Oma tili</a>';
            } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
                // Asiakas on kirjautunut sisään
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="customer.php" class="join-button">Oma tili</a>';
            } else {
                // Kukaan ei ole kirjautunut sisään
                echo '<a href="login.html" class="login-button">Kirjaudu sisään</a>';
                echo '<a href="register.html" class="join-button">Liity Jäseneksi</a>';
            }

            ?>
        </div>
    </nav>

    <section class="user-profile">
        <div class="info-box1">Name:
            <?php echo $name; ?>
        </div>
        <div class="info-box2">Membership: Student</div>
        <div class="info-box3">Email:
            <?php echo $email; ?>
        </div>
        <div class="info-box4">Status: Yellow</div>
        <a href="/muokkaa-profiilia" class="edit-profile-link">Muokkaa profiilia</a>
    </section>

    <div class="membership-details">
        <div class="day-element" id="day-number">Days: </div>
        <button class="cancel-button">Irtisanoudu</button>
        <div class="membership-price">24.90€/kk</div>
    </div>

    <script>
        // Generoidaan satunnainen numero 1-10000
        document.getElementById("day-number").textContent += Math.floor(Math.random() * 10000) + 1;
    </script>


    <div class="white-section">
        <div class="content-container">
            <div class="box">
                <h2 class="title">Varaukset</h2>
                <div class="black-box reservations-box">
                    <div class="reservations-container">
                    </div>
                </div>
            </div>
            <div class="box">
                <h2 class="title">Maksutiedot</h2>
                <div class="black-box payment-details-box scrollable">
                    <div class="info-box">
                        Tuote: Basicmonth 29,95 €
                        <div>
                            <span>Summa: 29,95 €</span>
                            <span>Tila: <span class="payment-status">Maksettu</span></span>
                        </div>
                    </div>
                    <div class="info-box">
                        Tuote: Basicmonth 29,95 €
                        <div>
                            <span>Summa: 29,95 €</span>
                            <span>Tila: <span class="payment-status">Maksettu</span></span>
                        </div>
                    </div>
                    <div class="info-box">
                        Tuote: Basicmonth 29,95 €
                        <div>
                            <span>Summa: 29,95 €</span>
                            <span>Tila: <span class="payment-status">Maksettu</span></span>
                        </div>
                    </div>
                    <div class="info-box">
                        Tuote: Basicmonth 29,95 €
                        <div>
                            <span>Summa: 29,95 €</span>
                            <span>Tila: <span class="payment-status">Maksettu</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="customer.js"></script>


    <footer class="footer">
        <div class="footer-logo">
            <img src="assets/Asset 7.png" alt="Logo" width="369" height="76">
        </div>

        <div class="footer-section">
            <h4>Meistä</h4>
            <ul>
                <li><a href="#">Töihin meille</a></li>
                <li><a href="#">Historia</a></li>
                <li><a href="#">Asiakaspalvelu</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Tuki</h4>
            <ul>
                <li><a href="#">Jäsenhinnasto</a></li>
                <li><a href="#">Tietosuojaseloste</a></li>
                <li><a href="#">Säännöt ja Ehdot</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Yhteystiedot</h4>
            <p>Fitnessskuja 12<br>00100 Helsinki</p>
        </div>

        <div class="footer-buttons">
            <?php
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor') {
                // Ohjaaja on kirjautunut sisään
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="instructor.php" class="join-button">Oma tili</a>';
            } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
                // Asiakas on kirjautunut sisään
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="customer.php" class="join-button">Oma tili</a>';
            } else {
                // Kukaan ei ole kirjautunut sisään
                echo '<a href="login.html" class="login-button">Kirjaudu sisään</a>';
                echo '<a href="register.html" class="join-button">Liity Jäseneksi</a>';
            }

            ?>

        </div>
        <div class="footer-line"></div>

        <div class="footer-text">
            © Strength & Health. 2024. Healthy AF!
        </div>
        <div class="footer-icons">
            <p>Seuraa meitä:</p>
            <img src="assets/footer_icon/instagram.svg" alt="Icon 1">
            <img src="assets/footer_icon/twitter.svg" alt="Icon 2">
            <img src="assets/footer_icon/github.svg" alt="Icon 3">
            <img src="assets/footer_icon/linkedin.svg" alt="Icon 4">
        </div>
    </footer>
</body>

</html>