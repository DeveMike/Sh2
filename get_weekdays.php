<?php
function generateWeekdays()
{
    $weekdaysHtml = '<div class="weekdays">';
    $currentDay = (new DateTime())->format('N'); // Viikonpäivä numerona (1 maanantai, 7 sunnuntai)
    $finnishWeekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su']; // Suomenkieliset lyhenteet

    for ($i = 1; $i <= 7; $i++) {
        $date = new DateTime();
        $date->setISODate((int)$date->format('o'), (int)$date->format('W'), $i);
        $dayLetter = $finnishWeekdays[$i - 1]; // Viikonpäivän ensimmäinen kirjain suomeksi
        $dayNumber = $date->format('j'); // Päivän numero kuukaudessa
        $fullDate = $date->format('Y-m-d'); // Täysi päivämäärä muodossa YYYY-MM-DD

        // Lisätään 'active' luokka nykyiselle päivälle
        $activeClass = $i == $currentDay ? ' active' : '';

        $weekdaysHtml .= "<div class=\"day$activeClass\" data-day=\"$fullDate\">$dayLetter<br>$dayNumber</div>";
    }
    $weekdaysHtml .= '</div>';
    return $weekdaysHtml;
}
