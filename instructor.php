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
// tarkistaa käyttäjän roolin
$role = $_SESSION['role'];

require 'includes/dbconnect.php';

// Haetaan kaikki tunnit, jotka kuuluvat kirjautuneelle ohjaajalle
$instructor_id = $_SESSION['user_id'];
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
    <?php include_once 'navbar.php'; ?>


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
                                    <?= date('H:i', strtotime($class['start_time'])) ?> -
                                    <?= date('H:i', strtotime($class['end_time'])) ?>
                                </div>
                                <div class="name">
                                    <?= htmlspecialchars($class['name']) ?>
                                    <?= htmlspecialchars($class['reservation_count']) ?>/
                                    <?= htmlspecialchars($class['capacity']) ?>
                                </div>
                                <div class="location">
                                    Kuntosali: <?= htmlspecialchars($class['address']) ?>
                                </div>
                                <!-- Oletetaan, että haet ohjaajan nimen tietokannasta
                                ja se on saatavilla $class['instructor_name'] -muuttujassa -->
                                <div class="instructor">
                                    <?= htmlspecialchars($class['instructor_name']) ?>
                                </div>
                                <div class="class-actions">
                                    <button class="info-btn">Info</button>
                                    <div class="info-section"><?= htmlspecialchars($class['description']) ?></div>
                                    <button class="book-btn" data-class-id="
                                    <?= htmlspecialchars($class['class_id']) ?>">Peruuta</button>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="instructor.js"></script>
    <?php include_once 'footer.php'; ?>

</body>

</html>