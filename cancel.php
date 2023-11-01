<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Et ole kirjautunut sisään.']);
    exit;
}

// Sisällytetään tietokantayhteyden luomisen tiedosto
include 'dbconnect.php';
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$customerId = $_SESSION['user_id'];
$classId = $_POST['class_id'];

try {
    $conn->beginTransaction();

    // Yritä peruuttaa käyttäjän varaus kyseiselle tunnille
    $stmt = $conn->prepare("DELETE FROM Varaukset WHERE customer_id = ? AND class_id = ?");
    if ($stmt->execute([$customerId, $classId])) {
        // Varaus peruttiin onnistuneesti, joten annetaan onnistuneen peruutuksen viesti
        echo json_encode(['success' => true]);
    } else {
        // Jokin meni pieleen, joten annetaan tietokantavirheen viesti
        echo json_encode(['success' => false, 'message' => 'Tietokantavirhe.']);
    }

    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Tietokantavirhe: ' . $e->getMessage()]);
    exit;
}
