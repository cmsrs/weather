<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Weather extends Model
{
  public $table = "weather";

  public static $city = 'Warsaw';

  private  $cacheTimeMin = 60;

  protected $fillable = [
      'city', 'temp', 'humidity', 'api_update'
  ];


  /**
  * get data from cache or API by the name of the city
  */
  public function getDataFromCacheOrApiByCity( $city = null)
  {
    if(empty($city)){
      $city = Weather::$city;
    }

    $row = $this->getFirstRecordByCity( $city );
    if($row ){
      if(strtotime($row->api_update) + $this->cacheTimeMin * 60 -  time() > 0 ){ //60*60 = 60 min
        return $row; //not exceed 60 min
      }

      //update record - time exceed 60 min
      $req = Weather::GetDataFromOpenWeatherApi();
      $data['temp'] = Weather::GetTempFromReq($req);
      $data['humidity'] = Weather::GetHumidityFromReq($req);

      $row->saveDataToDb($data);
      return $this->getFirstRecordByCity( $city );
    }

    //new record
    $req = Weather::GetDataFromOpenWeatherApi();
    $data['temp'] = Weather::GetTempFromReq($req);
    $data['humidity'] = Weather::GetHumidityFromReq($req);
    $weather = new Weather;
    $weather->saveDataToDb($data);
    return $this->getFirstRecordByCity( $city );
  }

  /**
  * Get data from OpenWeatherApi
  * todo: Use curl to get Json
  * or Guzzle lib
  */
  static public function GetDataFromOpenWeatherApi()
  {
    $url = "http://api.openweathermap.org/data/2.5/weather?q=".Weather::$city.",PL&APPID=".$_ENV['OPEN_WEATHER_MAP_KEY']."&units=metric";

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

  /**
  * Get temperature from reqest (json from API)
  */
  public static function GetTempFromReq($req)
  {
    if( empty($req->main) ){
      Log::error('Problem with get main from req');
      return false;
    }

    return (double)$req->main->temp;
  }

  /**
  * Get humidity from reqest (json from API)
  */
  public static function GetHumidityFromReq($req)
  {
    if( empty($req->main) ){
      Log::error('Problem with get main from req');
      return false;
    }
    return (int)$req->main->humidity;
  }

  /**
  * save data to db
  */
  public function saveDataToDb($data)
  {
    //$weather = new Weather();
    $this->city = Weather::$city;
    $this->temp = (double)$data['temp'];
    $this->humidity = (int)$data['humidity'];
    $this->api_update = Carbon::now()->toDateTimeString();

    $this->save();
  }

  /**
  * get first record by city
  */
  public function getFirstRecordByCity( $city )
  {
    $row = Weather::query()->where('city', $city )->first();
    if(empty($row)){
      return false;
    }
    return $row;
  }

}
