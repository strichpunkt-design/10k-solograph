<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/openweathermap.php';

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

try {

  $cities = explode( "\n", file_get_contents(__DIR__ . "/data/cities.txt"));
  $city = explode(";", $cities[array_rand($cities)]);

  $owm = new OpenWeatherMap($_ENV['API_KEY'], null, new FileSystemCache(), 60);
  $weather = $owm->getWeather($city[0], 'metric', 'de');
  $c = $weather->temperature->now->getValue();

  $color = color($c, 1);
  $gradient = gradient($c, 1);

  $city[]= join(',', $color);
  $city[]= join(',', $gradient[0]);
  $city[]= join(',', $gradient[1]);

  $time = new DateTime();
  $time->setTimezone(new DateTimeZone('UTC'));

  $h = gmdate('H', $time->getTimestamp() + 3600 * $city[1]);
  $m = gmdate('i', $time->getTimestamp() + 3600 * $city[1]);
  $s = gmdate('s', $time->getTimestamp() + 3600 * $city[1]);

  if (!empty($_GET['update'])) {
      die(implode(';', $city));
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>SOLOGRAPH</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <link rel="stylesheet" href="style.min.css" />
  <style>
    body{
      background: rgb(<?php echo $color[0]; ?>, <?php echo $color[1]; ?>, <?php echo $color[2]; ?> );
      background: -moz-linear-gradient(-45deg,  rgb(<?php echo $gradient[0][0]; ?>, <?php echo $gradient[0][1]; ?>, <?php echo $gradient[0][2]; ?>) 0%, rgb(<?php echo $gradient[1][0]; ?>, <?php echo $gradient[1][1]; ?>, <?php echo $gradient[1][2]; ?>) 100%);
      background: -webkit-linear-gradient(-45deg,  rgb(<?php echo $gradient[0][0]; ?>, <?php echo $gradient[0][1]; ?>, <?php echo $gradient[0][2]; ?>) 0%,rgb(<?php echo $gradient[1][0]; ?>, <?php echo $gradient[1][1]; ?>, <?php echo $gradient[1][2]; ?>) 100%);
      background: linear-gradient(45deg, rgb(<?php echo $gradient[0][0]; ?>, <?php echo $gradient[0][1]; ?>, <?php echo $gradient[0][2]; ?>), rgb(<?php echo $gradient[1][0]; ?>, <?php echo $gradient[1][1]; ?>, <?php echo $gradient[1][2]; ?>));
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="solograph">
    <div class="solograph__content">
      <div class="location">
        <h1 class="location__name"><?php echo $weather->city->name; ?></h1>
        <div class="location__time"><?php echo gmdate("H:i:s", $time->getTimestamp() + 3600 * $city[1]); ?></div>
        <div class="visualization">
          <div class="visualization__data">
            <div class="temperature"><span class="temperature__value"><?php echo round($c); ?></span><span class="temperature__unit">&deg;C</span></div>
          </div>
        </div>
      </div>
      <div class="graph">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
          <defs>
            <linearGradient id="gradient" gradientUnits="objectBoundingBox">
              <stop offset="0" style="stop-color: rgb(<?php echo $color[0]; ?>, <?php echo $color[1]; ?>, <?php echo $color[2]; ?>);" />
              <stop offset="1" style="stop-color: rgba(255, 255, 255, 0.4);" />
            </linearGradient>
            <filter id="filter_blur" x="0" y="0">
              <feGaussianBlur in="SourceGraphic" stdDeviation="2"></feGaussianBlur>
            </filter>
            <clipPath id="clip_circle">
              <circle cx="60" cy="60" r="40"></circle>
            </clipPath>
            <clipPath id="clip_rectangular" style="transform: translateX(103.3%); -ms-transform: translateX(103.3%); -webkit-transform: translateX(103.3%);">
              <rect x="150" y="150" width="50" height="500"></rect>
            </clipPath>
          </defs>
          <g id="seconds">
            <g><circle class="st2" cx="400" cy="400" r="239.4" /></g>
          </g>
          <g class="flare"><circle class="st3" cx="400" cy="400" r="10" /></g>
          <g class="flare"><circle class="st3" cx="400" cy="400" r="10" /></g>
          <g class="flare"><circle class="st3" cx="400" cy="400" r="10" /></g>
          <g class="flare"><circle class="st3" cx="400" cy="400" r="10" /></g>
          <g class="flare"><circle class="st3" cx="400" cy="400" r="10" /></g>
          <g id="outer">
            <g>
              <g><circle class="st0" cx="400" cy="160.1" r="150.3" /></g>
              <g><circle class="st1" cx="400" cy="400" r="394.1" /></g>
            </g>
          </g>
          <g id="basis">
            <g><circle class="st10" cx="400" cy="400" r="239.4" /></g>
          </g>
          <g id="inner">
            <g>
              <g><circle class="st1" cx="400" cy="400" r="239.9" /></g>
            </g>
            <g>
              <g><g><path class="st0" d="M400,310.9c66.6,0,123-43.3,142.8-103.2c-39.9-29.6-89.3-47.1-142.8-47.1s-102.9,17.5-142.8,47.1 C277,267.6,333.4,310.9,400,310.9z" /></g></g>
              <g><circle class="st1" cx="400" cy="400" r="394.1" /></g>
            </g>
          </g>
        </svg>
      </div>
    </div>
    <div class="solograph__footer">
      2016 by <a href="http://www.strichpunkt-design.de" target="_blank">Strichpunkt</a> &ndash; powered by <a href="http://www.openweathermap.org" target="_blank">OpenWeatherMap</a>
    </div>
  </div>
  <style>
    .flare,
    .st0,
    .st3,
    .st10 {
      fill: url(#gradient);
    }
    .st10 {
      stroke:url(#gradient);
    }
    #seconds circle {
      fill: none;
      stroke:url(#gradient);
      stroke-dashoffset: <?php echo $s * (-26.183); ?>;
    }
    #inner {
      transform: rotate(<?php echo  $m *  6 ; ?>deg);
    }
    #outer {
      transform: rotate(<?php echo  $h * 30 ; ?>deg);
    }
  </style>
  <script type="text/javascript" src="script.min.js"></script>
  <script type="text/javascript">window.solograph.initialize("<?php echo trim(preg_replace('/\s+/', ' ', implode(";", $city))); ?>");</script>
</body>
</html>
<?php
} catch(Exception $exception) {
if (!empty($_GET['update'])) {
  header('HTTP/1.0 404 Not Found');
  die("");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>SOLOGRAPH</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <link rel="stylesheet" href="style.min.css" />
</head>
<body>
  <h1>OpenWeatherMap API Error</h1>
  <a href="index.php">Reload</a>
</body>
</html>
<?php
}
?>
