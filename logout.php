<?php
session_start();
session_destroy(); // Tuhoaa kaikki istunnon muuttujat
header("Location: etusivu.php"); // Ohjaa takaisin etusivulle
exit();
?>