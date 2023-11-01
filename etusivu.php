<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Main Page</title>
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
            } ?>
        </div>
    </nav>

    <section class="welcome-section">
        <div class="welcome-image"></div>
        <div class="welcome-content">
            <h1>Vahvista itseäsi ja terveyttäsi meidän kanssa!</h1>
            <p class="price-text">VAIN 29,90 € / KK</p>
            <button class="welcome-join-button">Liity jäseneksi</button>
        </div>
    </section>

    <section class="why-us-section">
        <h1>Neljä Syytä Valita Meidät!</h1>
        <div class="why-us-boxes">
            <div class="why-us-box">
                <img src="assets/time.svg" alt="Icon 1">
                <h2>Avoinna 24/7</h2>
                <p>Avoinna aina kuin sinulle sopii!</p>
            </div>
            <div class="why-us-box">
                <img src="assets/hahmot.svg" alt="Icon 2">
                <h2>Ryhmäliikunta</h2>
                <p>Meidän salilla voit unohtaa erilliset ryhmäliikunta maksut ja nauttia näistä palveluista
                    rahjattomasti jäsenenä ILMAISEKSI.</p>
            </div>
            <div class="why-us-box">
                <img src="assets/euro.svg" alt="Icon 3">
                <h2>Kilpailukykyinen Hinta</h2>
                <p>Hintamme on kilpailukykyinen ja tarjoaa ensiluokkalaista palvelua halvalla.</p>
            </div>
            <div class="why-us-box">
                <img src="assets/shield.svg" alt="Icon 4">
                <h2>30 vuotta kokemusta</h2>
                <p>Voit turvallisen mielin urheilla kanssamme, sillä perustajillamme on vankka kokemus alasta!</p>
            </div>
        </div>

    </section>

    <section class="mbr-container">
        <h1>Liity Jäseneksi Nyt!</h1>
        <h2>Valitse itsellesi sopiva jäsenyys</h2>

        <div class="membership-options">
            <div class="membership-option">
                <h3>Perus Jäsenyys</h3>
                <p class="price">29.90€</p>
                <button class="mber-join-button">Liity</button>
                <p class="note">+ Aloitusmaksu 19.90€</p>
            </div>

            <div class="membership-option">
                <h3>Eläkeläinen</h3>
                <p class="price">24.90€</p>
                <button class="mber-join-button">Liity</button>
                <p class="note">+ Aloitusmaksu 19.90€</p>
            </div>

            <div class="membership-option">
                <h3>Opiskelija</h3>
                <p class="price">24.90€</p>
                <button class="mber-join-button">Liity</button>
                <p class="note">+ Aloitusmaksu 19.90€</p>
            </div>
        </div>
    </section>

    <section class="info-section">
        <div class="info-image">
            <img src="assets/pexels-leon-ardho-1552104-scaled 1.png" alt="Kuva" width="458" height="612">
        </div>
        <div class="info-text">
            <h2>Tervetuloa Strenght & Health 24/7 Kuntokeskusketjuun!</h2>
            <h4 class="yellow-text">We empower lives with strength and holistic health!</h4>
            <p>
                Olemme iloisia saadessamme esitellä sinulle Strenght & Health 24/7 -kuntosalejamme, jotka tarjoavat
                monipuolisia treenipalveluja sekä laadukasta ryhmäliikuntaa. Haluamme olla osa matkaasi kohti parempaa
                terveyttä ja kokonaisvaltaista hyvinvointia.
            </p>
        </div>
    </section>

    <section class="info-section2">
        <h2>Liity mukaan!</h2>
        <div class="yellow-lines">
            <div class="yellow-line1"></div>
            <div class="yellow-line2"></div>
        </div>

        <div class="info-block">
            <div class="icon-title-wrapper">

                <img src="assets/Barbell.svg" alt="Icon 1">
                <h3>Monipuolista Treeniä:</h3>
            </div>
            <p>Kuntosaleillamme voit nauttia modernista ympäristöstä sekä laadukkaista välineistä, jotka tukevat tavoitteitasi. Tarjoamme myös monipuolista ryhmäliikuntaa eri tyyleissä ja tasoilla. Olitpa sitten innostunut kardiotreenistä, voimaharjoittelusta tai kehoa ja mieltä tasapainottavista lajeista, meillä on jotain jokaiselle.</p>
        </div>

        <div class="info-block">
            <div class="icon-title-wrapper">

                <img src="assets/Growth Graph.svg" alt="Icon 2">
                <h3>Kasvava Yhteisö:</h3>
            </div>
            <p>Emme tyydy nykyiseen, vaan pyrimme kasvamaan ja kehittymään. Suunnitelmissamme on avata lisää saleja,
                jotta voimme palvella sinua entistä laajemmin ja monipuolisemmin. Tavoitteenamme on olla Suomen parhain urheilukeskusketju, joka tukee terveyttäsi ja hyvinvointiasi.</p>
        </div>

        <div class="info-block">
            <div class="icon-title-wrapper">
                <img src="assets/Shop.svg" alt="Icon 3">
                <h3>Tutustu Meihin:</h3>
            </div>
            <p>Meidän Strenght & Health 24/7 -sivumme tarjoavat sinulle mahdollisuuden sukeltaa treeniympäristöömme.Yhdessä pyrimme tarjoamaan sinulle parhaat mahdollisuudet saavuttaa omat tavoitteesi terveyden ja kunnon suhteen. Strenght & Health 24/7 on myös osa verkkokauppatiimiämme “Strenght & Health”, tarjoten laadukkaita treenivarusteita ja -vaatteita, jotka tukevat aktiivista elämäntapaa. Meiltä löydät kaiken tarvittavan hyvinvointiisi liittyen.</p>
        </div>

        <div class="yellow-text2">
            Olemme täällä sinua varten. Tule tutustumaan Strenght & Health 24/7 -perheeseen ja anna meidän auttaa sinua
            kohti vahvempaa ja terveempää elämää. Yhdessä teemme ihmisistä terveempiä ja onnellisempia!
        </div>
    </section>

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

        <div class="footer-section first-section">
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
            } ?>
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