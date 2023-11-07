<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
session_start();

// Luo CSRF-token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Sisällytä tietokantayhteyden tiedot
require 'includes/dbconnect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Käytä $conn-muuttujaa, joka on määritelty dbconnect.php-tiedostossa
        // Ensin tarkistetaan Asiakkaat-taulusta
        $stmt = $conn->prepare("SELECT * FROM Asiakkaat WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $fetchedUser = $stmt->fetch();

        if ($fetchedUser && password_verify($password, $fetchedUser['password'])) {
            $_SESSION['user_id'] = $fetchedUser['customer_id'];
            $_SESSION['email'] = $fetchedUser['email'];
            $_SESSION['name'] = $fetchedUser['name'];
            $_SESSION['role'] = 'customer'; // Lisätty rooli
            header('Location: customer.php');
            exit;
        } else {
            // Jos ei löydy Asiakkaat-taulusta, tarkistetaan Ohjaajat-taulusta
            $stmt = $conn->prepare("SELECT * FROM Ohjaajat WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $fetchedInstructor = $stmt->fetch();

            if ($fetchedInstructor && password_verify($password, $fetchedInstructor['password'])) {
                $_SESSION['user_id'] = $fetchedInstructor['instructor_id'];
                $_SESSION['email'] = $fetchedInstructor['email'];
                $_SESSION['name'] = $fetchedInstructor['name'];
                $_SESSION['role'] = 'instructor'; // Lisätty rooli
                header('Location: instructor.php'); // Olettaen, että ohjaajilla on oma sivunsa
                exit;
            } else {
                $message = 'Väärä salasana tai käyttäjänimi.';
            }
        }
    } catch (PDOException $e) {
        $message = "Tietokantavirhe: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Log in page</title>
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
            <a href="login.html" class="login-button">Kirjaudu sisään</a>
            <a href="login.html" class="join-button">Liity Jäseneksi</a>
        </div>
    </nav>

    <main class="login-container">
        <div class="login-box">
            <h2>Kirjaudu Sisään</h2>
            <div class="yellow-lines">
                <div class="yellow-line1"></div>
                <div class="yellow-line2"></div>
            </div>
            <h3>Hallinnoi aktiviteettejasi ja jäsennyttäsi</h3>
            <h4 class="small-title">Have a great day</h4>
            <form method="post" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div class="input-container">
                    <input type="email" id="email" name="email" required placeholder="Sähköposti" autocomplete="username">
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" required placeholder="Salasana" autocomplete="current-password">
                </div>
                <button type="submit" class="form-login-button">Kirjaudu Sisään</button>
                <p class="forgot-password"><img src="assets/Icon/lock-alt-solid 1.svg" alt="Kuvake"> Unohditko
                    salasanasi?</p>

            </form>
        </div>
    </main>




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
            <button class="login-button">Kirjaudu sisään</button>
            <button class="join-button">Liity sisään</button>
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