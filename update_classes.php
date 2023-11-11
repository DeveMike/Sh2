<?php
// Tietokantayhteyden muodostaminen
require 'includes/dbconnect.php';

// Aseta aikavyöhyke
date_default_timezone_set('Europe/Helsinki');

// Tarkista, onko päivitys jo suoritettu tälle viikolle
$today = new DateTime();
$weekNumber = $today->format("W");

$query = "SELECT COUNT(*) FROM Jumpat WHERE WEEK(start_time) = :weekNumber";
$stmt = $conn->prepare($query);
$stmt->execute(['weekNumber' => $weekNumber]);

// Jos tunteja ei ole vielä päivitetty tälle viikolle, suorita päivitys
if ($stmt->fetchColumn() == 0) {
    // Laske seuraavan viikon alku- ja loppupäivämäärät
    $nextWeekMonday = new DateTime('next monday');
    $nextSunday = new DateTime('next monday +6 days');

    // Muotoile päivämäärät MySQL:n datetime-muotoon
    $nextWeekMondayFormatted = $nextWeekMonday->format('Y-m-d');
    $nextSundayFormatted = $nextSunday->format('Y-m-d');

    try {
        // Päivitä tuntien ajat tietokannassa
        $query = "UPDATE Jumpat SET start_time = CONCAT(DATE_ADD(DATE(start_time), 
        INTERVAL WEEK('$nextWeekMondayFormatted') - WEEK(start_time) WEEK), ' ',
        TIME(start_time)), end_time = CONCAT(DATE_ADD(DATE(end_time),
        INTERVAL WEEK('$nextWeekMondayFormatted') - WEEK(end_time) WEEK), ' ',
        TIME(end_time)) WHERE DATE(start_time) BETWEEN '$nextWeekMondayFormatted' AND '$nextSundayFormatted'";

        // Suorita kysely
        $stmt = $conn->prepare($query);
        $stmt->execute();

        echo "Tuntien ajat päivitetty onnistuneesti seuraavalle viikolle.\n";
    } catch (PDOException $e) {
        exit("Tietokantavirhe: " . $e->getMessage());
    }
} else {
    echo "Tunnit on jo päivitetty tälle viikolle.\n";
}
