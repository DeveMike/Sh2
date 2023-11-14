<?php
// Määrittele CSP-otsikot
$cspRules = "default-src 'self'; "
    . "script-src 'self' https://trusted-source.com; "
    . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " // Lisätty Google Fonts tyylitiedostoille
    . "img-src 'self' https://trusted-images.com; "
    . "font-src 'self' https://fonts.gstatic.com; " // Muutettu fonts.gstatic.com, koska se on fonttienlatauslähde
    . "frame-src 'none'; "
    . "object-src 'none';";
header("Content-Security-Policy: " . $cspRules);
