<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "varausjarjestelma";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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
$result = $stmt->get_result();

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

    <div class="content-container">
        <!-- Vasen laatikko -->
        <div class="search-container">
            <h2 class="centered-title">Hae Tunteja</h2>
            <div class="yellow-lines">
                <div class="yellow-line1"></div>
                <div class="yellow-line2"></div>
            </div>
            <div class="dropdown">
                <label>Kaupunki</label>
                <select id="citySelect">
                    <option value=""></option> <!-- Lisätty tyhjä rivi -->
                    <?php while ($row = $cityResult->fetch_assoc()) : ?>
                        <option value="<?= $row['city'] ?>"><?= $row['city'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="dropdown">
                <label>Kuntosalin Osoite</label>
                <select id="gymSelect">
                    <option value=""></option> <!-- Lisätty tyhjä rivi -->
                    <?php while ($row = $addressResult->fetch_assoc()) : ?>
                        <option value="<?= $row['address'] ?>"><?= $row['address'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <h2 class="centered-title_filters-title">Suodattimet</h2>
            <div class="dropdown">
                <label>Tunnin Nimi</label>
                <select id="classNameSelect">
                    <option value=""></option> <!-- Lisätty tyhjä rivi -->
                    <?php while ($row = $classNameResult->fetch_assoc()) : ?>
                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="dropdown">
                <label>Ohjaajat</label>
                <select id="instructorSelect">
                    <option value=""></option> <!-- Lisätty tyhjä rivi -->
                    <?php while ($row = $instructorResult->fetch_assoc()) : ?>
                        <option value="<?= $row['instructor_id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="time-filter">
                <label>Alkuaika:</label>
                <input type="time" id="startTime">
            </div>
            <div class="time-filter">
                <label>Loppuaika:</label>
                <input type="time" id="endTime">
            </div>
        </div>
        <div class="classes-container">
            <div class="weekdays">
                <div class="day" data-day="mon">M<br>1</div>
                <div class="day" data-day="tue">T<br>2</div>
                <div class="day" data-day="wed">K<br>3</div>
                <div class="day" data-day="thu">T<br>4</div>
                <div class="day" data-day="fri">P<br>5</div>
                <div class="day" data-day="sat">L<br>6</div>
                <div class="day" data-day="sun">S<br>7</div>
            </div>
            <div class="classes-list">
                <?php
                if ($result->num_rows > 0) {

                    $finnishMonths = array(
                        1 => "Tammi",
                        2 => "Helmi",
                        3 => "Maalis",
                        4 => "Huhti",
                        5 => "Touko",
                        6 => "Kesä",
                        7 => "Heinä",
                        8 => "Elo",
                        9 => "Syys",
                        10 => "Loka",
                        11 => "Marras",
                        12 => "Joulu"
                    );

                    // Output each class
                    while ($row = $result->fetch_assoc()) {

                        // Muotoillaan päivämäärä ja aika suomenkieliseksi
                        $startDate = new DateTime($row["start_time"]);
                        $endDate = new DateTime($row["end_time"]);

                        $formattedDate = $startDate->format('j') . ' ' . $finnishMonths[$startDate->format('n')] . ' | ' . $startDate->format('H:i') . ' - ' . $endDate->format('H:i');

                        // Tarkista, onko käyttäjällä varaus tälle tunnille
                        $stmt = $conn->prepare("SELECT * FROM Varaukset WHERE customer_id = ? AND class_id = ?");
                        $stmt->bind_param("ii", $user_id, $row["class_id"]);
                        $stmt->execute();
                        $reservation = $stmt->get_result()->fetch_assoc();
                        $buttonText = $reservation ? "Peruuta" : "Varaa";
                        $buttonClass = $reservation ? "booked" : "";

                        echo '<div class="class-card" data-class-id="' . $row["class_id"] . '">
                    <div class="class-info">
                        <div class="date-time">' . $formattedDate . '</div>
                        <div class="name">' . $row["name"] . ' ' . $row["reservation_count"] . '/ ' . $row["capacity"] . '</div>
                        <div class="location">Kuntosali: ' . $row["address"] . '</div>
                        <div class="instructor">' . $row["instructor_name"] . '</div>
                    </div>
                    <div class="class-actions">
                        <button class="info-btn">Info</button>
                        <div class="info-section">' . $row["description"] . '</div>
                        <button class="book-btn ' . $buttonClass . '">' . $buttonText . '</button>
                    </div>
                </div>';
                    }
                } else {
                    echo "No classes found.";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="script.js"></script>


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