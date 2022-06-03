<?php

namespace Drupal\farm_weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * This class will give users weather data.
 */
class WeatherController extends ControllerBase {

  /**
   * Returns Weather Data.
   */
  public function getWeather($date) {
    $weather_api_url = 'http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key=beadb390698d4b5085124007220306&q=Michigan&format=json&date=' . $date;

    $client = new Client();

    try {
      $response = $client->get($weather_api_url);
      $result = json_decode($response->getBody(), TRUE);
      return $result;
    }
    catch (RequestException $e) {
      // log exception
      $e->getResponse()->getBody()->getContents();
    }
  }

}
