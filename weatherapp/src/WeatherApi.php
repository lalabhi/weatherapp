<?php

namespace Drupal\weatherapp;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;


class WeatherApi {

//  /**
//   * The HTTP client.
//   *
//   * @var \GuzzleHttp\Client
//   */
//  protected $client;
//
//  public function __construct($http_client_factory) {
//    $this->client = $http_client_factory->fromOptions([
//      'base_uri' => 'https://samples.openweathermap.org',
//    ]);
//  }



  public function weatherapi($city){
    $config = \Drupal::config('weatherapp.settings');
    $data = $config->get('AppID');


    //$request = $this->httpClient->request('GET', 'https://samples.openweathermap.org/data/2.5/weather?q=' . $city . '&appid='. $data );

    //kint($request->getBody);
    $client = new \GuzzleHttp\Client();
//    $response = $this->client->get('/data/2.5/weather', [
//      'query' => [
//        'q' => $city,
//        'appid'
//      ]
//    ]);

    $val=$client->request('GET', 'https://samples.openweathermap.org/data/2.5/weather?q='.$city.'&appid='. $data);
    return $val->getBody();


  }
}
