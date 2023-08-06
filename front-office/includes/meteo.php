<?php
echo "<div class='weather-container'>
    <p style='color: black'>Ville : " . $_GET['city'] . "</p>
    <p>Température : " . $_GET['temp'] . "°C</p>
    <div><img src='https://openweathermap.org/img/wn/" . $_GET['icon'] . ".png' alt='weather-status'><p style='text-transform: capitalize'>" . $_GET['desc'] . "</p></div>
    </div>";
?>