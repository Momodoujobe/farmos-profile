<?php

namespace Drupal\farm_weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class will give users weather data.
 */
class WeatherController extends ControllerBase {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * Returns Weather Data.
   */
  public function getWeather($date) {
    $weather_api_url = 'http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key=beadb390698d4b5085124007220306&q=Michigan&format=json&date=' . $date;

    try {
      $response = $this->httpClient->request('GET', $weather_api_url);

      $result = json_decode($response->getBody(), TRUE);
      return $result;
    }
    catch (RequestException $e) {
      // log exception
      $e->getResponse()->getBody()->getContents();
    }
  }

}
