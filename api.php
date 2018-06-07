<?php

require 'vendor/autoload.php';
require 'weather.php';

$text = mb_strtolower($_REQUEST['text']);
$get_audio = $_REQUEST['get_audio'] ?? false;

$questions = [
    "как дела" => "вроде нормально",
    "чем занят" => "отвечаю на дурацкие вопросы",
    "ты дурак" => "не, а ты умный чтоле?",
];

$responses = [];

foreach($questions as $question => $answer) {
    if (mb_strpos($text, $question) !== false) {
        $responses []= $answer;
    }
}

if (mb_strpos($text, "сколько время") !== false) {
    $responses [] = "точное время " . date("H:i");
}

if (preg_match("/какая погода в городе (\w+)/iu", $text,$matches)) {
    $city = urlencode($matches[1]);
    [$temp_c, $condition] = getWeather($city);
    $responses [] = "там сейчас $condition, где-то $temp_c градусов";
}

if ($responses) {
    $response = join(',', $responses);
} else {
    $response = "окей";
}

if ($get_audio) {
    $provider = new \duncan3dc\Speaker\Providers\ResponsiveVoiceProvider("ru-RU");
    $tts = new \duncan3dc\Speaker\TextToSpeech($response, $provider);
    header("Content-Type: audio/mpeg");
    echo $tts->getAudioData();
} else {
    echo $response;
}