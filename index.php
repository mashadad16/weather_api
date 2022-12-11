<?php
//интеграция с openweather
$url = 'https://api.openweathermap.org/data/2.5/weather';
$key = '92b76856022b61ead2896c1992bae214';

$params = array(
    'id' => '524305',
    'appid' => '92b76856022b61ead2896c1992bae214',
    'units' => 'metric',
    'lang' => 'ru'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));

$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

//print_r($data);

//интеграция с яндексом
//массив для заголовка
$params = array(
    'http' => array(
        'method' => "GET",
        'header' => "X-Yandex-API-Key:631e5dfa-eddf-40be-9b68-c4decab57a21"."\r\n"
    )
);

$context = stream_context_create($params);
$f=file_get_contents("https://api.weather.yandex.ru/v2/forecast/?lat=68.9791700&lon=33.0925100&lang=ru_RU",false,$context);

$f=json_decode($f);
$t=$f->fact;
$v=$f->info;

echo '
<html>
 <head>
    <!-- Bootstrap CSS (jsDelivr CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <link type="text/css" href="style.css" rel="stylesheet"/>
 </head>
 <body>
 <div class="container" style="margin: 10px;">
    <div class="weather_block row">
        <h3>'.$data['name'].'</h3>
        <div class="">
            <p>Загружено из <a href="https://openweathermap.org/current">OpenWeatherMap</a>
            <br>Время: '.date('G:i d.m,Y', $data['dt']).'</p>
        </div>
        <div style="text-align: right;" class="col-lg-6 col-xs-6">
            <h2>'.round($data['main']['temp'], 0).' °C</h2>
        </div>
        <div class="col-lg-6 col-xs-6">
            <img style="" src="http://openweathermap.org/img/wn/'.$data['weather'][0]['icon'].'.png">
        </div>
        <div class="">
            <p>Загружено из <a href="https://yandex.ru/dev/weather/doc/dg/concepts/forecast-test.html">Яндекс.Погода</a>
            <br>Время: '.date('G:i d.m,Y',$t->obs_time).'</p>
        </div>
        <div style="text-align: right;" class="col-lg-6 col-xs-6">
            <h2>'.round($t->temp, 0).' °C</h2>
        </div>
        <div class="col-lg-6 col-xs-6">
            <img style="" src="https://yastatic.net/weather/i/icons/funky/dark/'.$t->icon.'.svg">
        </div>
    </div>
 </div>
 </body>
</html>';