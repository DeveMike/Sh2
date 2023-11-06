<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
session_start();

$host = '127.0.0.1';
$db = 'varausjarjestelma';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        // Ensin tarkistetaan Asiakkaat-taulusta
        $stmt = $pdo->prepare("SELECT * FROM Asiakkaat WHERE email = :email");
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
            $stmt = $pdo->prepare("SELECT * FROM Ohjaajat WHERE email = :email");
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

include 'login.html';
