<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


// Sisällytä tietokantayhteyden tiedot
require 'includes/dbconnect.php';

// Sisällytä viikonpäivät generoiva tiedosto
include_once 'get_weekdays.php';


// Haetaan jumppatuntien tiedot, ohjaajan nimi ja varausmäärä yhdessä kyselyssä
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
");

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Haetaan kaikki uniikit kaupungit osoite-sarakkeesta
$cityQuery = "SELECT DISTINCT SUBSTRING_INDEX(address, ' ', -1) as city FROM Jumpat";
$cityResult = $conn->query($cityQuery);

// Haetaan kaikki uniikit osoitteet
$addressQuery = "SELECT DISTINCT address FROM Jumpat";
$addressResult = $conn->query($addressQuery);

// Haetaan kaikki uniikit tunnin nimet
$classNameQuery = "SELECT DISTINCT name FROM Jumpat";
$classNameResult = $conn->query($classNameQuery);

// Haetaan kaikki ohjaajat
$instructorQuery = "SELECT instructor_id, name FROM Ohjaajat";
$instructorResult = $conn->query($instructorQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="varaukset.css">
    <title>Varaukset</title>
</head>

<body>

    <?php include_once 'navbar.php'; ?>

    <div class="content-container">
        <!-- Vasen laatikko -->
        <?php include_once 'search_container_varaus.php'; ?>

        <div class="classes-container">
            <?php echo generateWeekdays(); ?>
            <div class="classes-list">
                <?php if (count($result) > 0) : ?>

                    <?php
                    $finnishMonths = [
                        1 => "Tammi", 2 => "Helmi", 3 => "Maalis", 4 => "Huhti",
                        5 => "Touko", 6 => "Kesä", 7 => "Heinä", 8 => "Elo",
                        9 => "Syys", 10 => "Loka", 11 => "Marras", 12 => "Joulu"
                    ];
                    ?>

                    <?php foreach ($result as $row) : ?>
                        <?php
                        $startDate = new DateTime($row["start_time"]);
                        $endDate = new DateTime($row["end_time"]);
                        $formattedDate = sprintf(
                            '%d %s | %s - %s',
                            $startDate->format('j'),
                            $finnishMonths[$startDate->format('n')],
                            $startDate->format('H:i'),
                            $endDate->format('H:i')
                        );

                        $stmt = $conn->prepare("SELECT * FROM 
                        Varaukset WHERE customer_id = :user_id AND class_id = :class_id");
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt->bindParam(':class_id', $row["class_id"], PDO::PARAM_INT);
                        $stmt->execute();
                        $reservation = $stmt->fetch();
                        $buttonText = $reservation ? "Peruuta" : "Varaa";
                        $buttonClass = $reservation ? "booked" : "";
                        ?>

                        <div class="class-card" data-class-id="<?= htmlspecialchars($row["class_id"]) ?>">
                            <div class="class-info">
                                <div class="date-time"><?= htmlspecialchars($formattedDate) ?></div>
                                <div class="name">
                                    <?= htmlspecialchars($row["name"]) ?>
                                    <?= htmlspecialchars($row["reservation_count"]) ?>/
                                    <?= htmlspecialchars($row["capacity"]) ?></div>
                                <div class="location">Kuntosali: <?= htmlspecialchars($row["address"]) ?></div>
                                <div class="instructor"><?= htmlspecialchars($row["instructor_name"]) ?></div>
                            </div>
                            <div class="class-actions">
                                <button class="info-btn">Info</button>
                                <div class="info-section"><?= htmlspecialchars($row["description"]) ?></div>
                                <button class="book-btn <?= htmlspecialchars($buttonClass) ?>">
                                    <?= htmlspecialchars($buttonText) ?></button>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else : ?>
                    <p>No classes found.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="script.js"></script>

    <?php include_once 'footer.php'; ?>

</body>

</html>