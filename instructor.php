<?php
session_start();

$instructor_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="instructor.css">
    <title>Instructor Page</title>
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
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="customer.php" class="join-button">Oma tili</a>';
            } else {
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
        <div class="info-box2">Tehtävänimike: Ohjaaja</div>
        <div class="info-box3">Email:
            <?php echo $email; ?>
        </div>
        <div class="info-box4">Erikoistuminen: Lihaskunto</div>
        <a href="/muokkaa-profiilia" class="edit-profile-link">Muokkaa profiilia</a>
    </section>

    <div class="membership-details">
        <button class="add-button">Lisää Tunti</button>

    </div>




    <div class="white-section">
        <div class="content-container">
            <div class="box">
                <h2 class="title">Varaukset</h2>
                <div class="black-box reservations-box">
                    <a href="varaus.php" class="booking-link">Lisää Tunti</a>
                    <div class="plus-icon">+</div>
                </div>
            </div>
            <div class="box">
                <h2 class="title">Omat Tunnit</h2>
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
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php" class="login-button">Kirjaudu ulos</a>';
                echo '<a href="customer.php" class="join-button">Oma tili</a>';
            } else {
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