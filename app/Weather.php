<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Weather extends Model
{

  public static $city = 'Warsaw';

  /**
  * todo: Use curl to get Json
  * or Guzzle lib
  */
  static public function GetDataFromOpenWeatherApi()
  {
    $url = "http://api.openweathermap.org/data/2.5/weather?q=".Weather::$city.",PL&APPID=".$_ENV['OPEN_WEATHER_MAP_KEY'];

    try{
        //todo
        //mozna uzyc curla - i stworzyc osobna klase oblugujaca pobranie danych i obsluge bledow/ metoda tej klasy moglaby zwracac dane w postaci np. tablicy lub clasy
        $json = file_get_contents($url);
        if( empty($json) ){
            throw new \Exception('Problem with getting data.');
        }
        $data = json_decode($json);
    } catch (\Exception $e) {
        Log::error('Caught exception: '.  $e->getMessage());
        return false;
    }
    return $data;
  }

  public static function GetTempFromReq($req)
  {
    if( empty($req->main) ){
      Log::error('Problem with get main from req');
      return false;
    }

    return (double)$req->main->temp;
  }

  public static function GetHumidityFromReq($req)
  {
    if( empty($req->main) ){
      Log::error('Problem with get main from req');
      return false;
    }
    return (int)$req->main->humidity;
  }

}
