<?php

namespace Drupal\weatherapp;
class WeatherApi {
  public function weatherapi($city){
    $config = \Drupal::config('weatherapp.settings');
    $data = $config->get('AppID');
    $client = new \GuzzleHttp\Client();
    $val=$client
      ->request('GET', 'https://samples.openweathermap.org/data/2.5/weather?q='.$city.'&appid='. $data);
    return $val->getBody();


  }
}
