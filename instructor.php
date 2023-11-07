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
// Voit myös tarkistaa käyttäjän roolin, jos tarvitset sitä
$role = $_SESSION['role'];

if ($role === 'customer') {
    // Näytä asiakkaalle tarkoitettu sisältö
} elseif ($role === 'instructor') {
    // Näytä ohjaajalle tarkoitettu sisältö
}

require 'includes/dbconnect.php';

// Haetaan kaikki tunnit, jotka kuuluvat kirjautuneelle ohjaajalle
$instructor_id = $_SESSION['user_id']; // Oletetaan, että ohjaajan ID on tallennettu sessioon
$sql = "SELECT * FROM Jumpat WHERE instructor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$instructor_id]);
$classes = $stmt->fetchAll(); // Tallennetaan kaikki rivit $classes-muuttujaan

// Haetaan kaikki tunnit, ohjaajan nimi ja varauksien määrä
$instructor_id = $_SESSION['user_id']; // Oletetaan, että ohjaajan ID on tallennettu sessioon
$stmt = $conn->prepare("
    SELECT 
        j.*, 
        o.name as instructor_name, 
        COALESCE(v.reservation_count, 0) as reservation_count
    FROM 
        Jumpat j 
    JOIN 
        Ohjaajat o ON j.instructor_id = o.instructor_id
    LEFT JOIN (
        SELECT 
            class_id,
            COUNT(*) as reservation_count 
        FROM 
            Varaukset 
        GROUP BY 
            class_id
    ) as v ON j.class_id = v.class_id
    WHERE j.instructor_id = ?
");
$stmt->execute([$instructor_id]);
$classes = $stmt->fetchAll();

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
                    <div class="form-container">
                        <h2>Ohjaajan Tuntien Lisäys</h2>
                        <!-- Tuntien lisäyslomake -->
                        <form action="handle_class_addition.php" method="post">
                            <div class="form-group">
                                <label for="name">Tunnin nimi:</label>
                                <input type="text" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Kuvaus:</label>
                                <textarea id="description" name="description" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="start_time">Aloitusaika:</label>
                                <input type="datetime-local" id="start_time" name="start_time" required>
                            </div>

                            <div class="form-group">
                                <label for="end_time">Lopetusaika:</label>
                                <input type="datetime-local" id="end_time" name="end_time" required>
                            </div>

                            <div class="form-group">
                                <label for="capacity">Kapasiteetti:</label>
                                <input type="number" id="capacity" name="capacity" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Osoite:</label>
                                <input type="text" id="address" name="address" required>
                            </div>

                            <input type="submit" value="Lisää tunti">
                        </form>
                    </div>
                </div>
            </div>
            <div class="box">
                <h2 class="title">Omat Tunnit</h2>
                <div class="booked-box">
                    <?php foreach ($classes as $class) : ?>
                        <div class="class-card">
                            <div class="class-info">
                                <!-- Muotoillaan päivämäärä ja aika halutulla tavalla -->
                                <div class="date-time">
                                    <?= date('j M', strtotime($class['start_time'])) ?> |
                                    <?= date('H:i', strtotime($class['start_time'])) ?> - <?= date('H:i', strtotime($class['end_time'])) ?>
                                </div>
                                <div class="name">
                                    <?= htmlspecialchars($class['name']) ?>
                                    <?= htmlspecialchars($class['reservation_count']) ?>/<?= htmlspecialchars($class['capacity']) ?>
                                </div>
                                <div class="location">
                                    Kuntosali: <?= htmlspecialchars($class['address']) ?>
                                </div>
                                <!-- Oletetaan, että haet ohjaajan nimen tietokannasta ja se on saatavilla $class['instructor_name'] -muuttujassa -->
                                <div class="instructor">
                                    <?= htmlspecialchars($class['instructor_name']) ?>
                                </div>
                                <div class="class-actions">
                                    <button class="info-btn">Info</button>
                                    <div class="info-section"><?= htmlspecialchars($class['description']) ?></div>
                                    <button class="book-btn" data-class-id="<?= htmlspecialchars($class['class_id']) ?>">Peruuta</button>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="instructor.js"></script>


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