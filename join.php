<?php
session_start();
require 'includes/dbconnect.php';

function isEmailTaken($email, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM Asiakkaat WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];

    $_SESSION['form_data'] = $_POST;
    unset($_SESSION['error_message']);

    // Yksinkertaistetaan virheiden tarkistusta
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = 'Salasanat eivät täsmää. ';
    } elseif (isEmailTaken($email, $conn)) {
        $_SESSION['error_message'] = 'Sähköposti on jo käytössä. ';
    }

    if (isset($_SESSION['error_message'])) {
        header('Location: join.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $address = "$street, $city, $postal_code";

    try {
        $stmt = $conn->prepare("INSERT INTO Asiakkaat (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $phone, $address]);

        $_SESSION['success_message'] = 'Rekisteröityminen onnistui. Tarkista sähköpostisi vahvistusta varten.';
        unset($_SESSION['form_data']); // Poistetaan lomaketiedot onnistuneen rekisteröinnin jälkeen
        header('Location: join.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Rekisteröityminen epäonnistui: ' . $e->getMessage();
        header('Location: join.php');
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="join.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <title>Rekisteröidy</title>
</head>


<body>
    <?php include_once 'navbar.php'; ?>

    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Poistetaan viesti sessiosta
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); // Poistetaan viesti sessiosta
            ?>
        </div>
    <?php endif; ?>

    <div class="form-section">
        <form action="join.php" method="post">
            <h2>Rekisteröidy</h2>
            <label for="name">Nimi:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? '') ?>" pattern="^[a-zA-ZäöåÄÖÅ\s]+$" title="Vain kirjaimet sallittu." required>

            <label for="email">Sähköposti:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" required>

            <label for="phone">Puhelinnumero:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? '') ?>" pattern="\d*" title="Vain numerot sallittu." required>

            <label for="street">Katuosoite:</label>
            <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($_SESSION['form_data']['street'] ?? '') ?>" required>

            <label for="city">Kaupunki:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_SESSION['form_data']['city'] ?? '') ?>" pattern="^[a-zA-ZäöåÄÖÅ\s]+$" title="Vain kirjaimet sallittu." required>

            <label for="postal_code">Postinumero:</label>
            <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($_SESSION['form_data']['postal_code'] ?? '') ?>" pattern="\d*" title="Vain numerot sallittu." required>

            <label for="password">Salasana:</label>
            <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}" title="Salasanan on oltava vähintään 6 merkkiä pitkä ja sisältää sekä kirjaimia että numeroita." required>

            <label for="confirm_password">Vahvista salasana:</label>
            <input type="password" id="confirm_password" name="confirm_password" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}" title="Salasanan on oltava vähintään 6 merkkiä pitkä ja sisältää sekä kirjaimia että numeroita." required>

            <input type="submit" value="Rekisteröidy">
        </form>
    </div>

    <?php include_once 'footer.php'; ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.setTimeout(function() {
                var alertElement = document.querySelector(".alert");
                if (alertElement) {
                    alertElement.style.display = 'none';
                }
            }, 10000);
        });
    </script>
</body>

</html>