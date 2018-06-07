<?php
const API_KEY = "5ffa420ac32f4ff29ce10055182504";
const API_URL = "http://api.apixu.com/v1/current.json?key=" . API_KEY;
function getWeather($city) {
    $forecast = file_get_contents(API_URL . "&q=$city&lang=ru");
    $data = json_decode($forecast, true);
    $temp_c = intval($data['current']['temp_c']);
    $condition = $data['current']['condition']['text'];
    return [$temp_c, $condition];
}
