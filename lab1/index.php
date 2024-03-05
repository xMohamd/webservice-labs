<!DOCTYPE html>
<html>

<head>
  <title>Egyptian Cities Weather</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 50px;
    }

    .weather-info {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="card">
          <div class="card-header">
            <h3>Select a City in Egypt</h3>
          </div>
          <div class="card-body">
            <form method="post">
              <div class="form-group">
                <select name="city" class="form-control">
                  <option value="">Select a City</option>
                  <?php
                  ini_set('memory_limit', '-1');
                  $cities_txt_file = 'cities.txt';
                  $cities_txt = file_get_contents($cities_txt_file);
                  $cities = explode("\n", $cities_txt);

                  sort($cities);

                  foreach ($cities as $city) {
                    echo "<option value='$city'>$city</option>";
                  }
                  ?>
                </select>
              </div>
              <button type="submit" name="submit" class="btn btn-primary">Get Weather</button>
            </form>
          </div>
        </div>

        <?php
        if (isset($_POST['submit'])) {
          $selectedCity = $_POST['city'];
          if (!empty($selectedCity)) {
            $apiKey = "b6de1af4989ae03601fbfd07e804f454";
            $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($selectedCity) . ",EG&appid=" . $apiKey;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $weatherData = json_decode($response, true);
            curl_close($curl);
            if (isset($weatherData['cod']) && $weatherData['cod'] == 200) {
              $cityName = $weatherData['name'];
              $weatherDescription = $weatherData['weather'][0]['description'];
              $temperature = $weatherData['main']['temp'] - 273.15;
              $feelsLike = $weatherData['main']['feels_like'] - 273.15;
              $humidity = $weatherData['main']['humidity'];
              $windSpeed = $weatherData['wind']['speed'];
        ?>
              <div class="weather-info mt-4">
                <h4>Weather Information for <?php echo $cityName; ?></h4>
                <p><strong>Weather:</strong> <?php echo $weatherDescription; ?></p>
                <p><strong>Temperature:</strong> <?php echo round($temperature, 2); ?> &deg;C</p>
                <p><strong>Feels Like:</strong> <?php echo round($feelsLike, 2); ?> &deg;C</p>
                <p><strong>Humidity:</strong> <?php echo $humidity; ?>%</p>
                <p><strong>Wind Speed:</strong> <?php echo $windSpeed; ?> m/s</p>
              </div>
        <?php
            } else {
              echo "<div class='alert alert-danger mt-4'>Error: Unable to fetch weather information for the selected city.</div>";
            }
          } else {
            echo "<div class='alert alert-warning mt-4'>Please select a city.</div>";
          }
        }
        ?>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>