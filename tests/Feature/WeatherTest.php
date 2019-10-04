<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Weather;



class WeatherTest extends TestCase
{
    use RefreshDatabase;

    public function testGetDataFromCacheOrApi()
    {
      $weather = new Weather;

      //no data in db
      $data = $weather->getDataFromCacheOrApiByCity();
      $this->assertNotEmpty($data['api_update']);
      sleep(3);

      //data in db
      $data2 = $weather->getDataFromCacheOrApiByCity();
      $this->assertNotEmpty($data2['api_update']);
      $this->assertEquals($data['api_update'], $data2['api_update']);



      //data in db, and api_update - 55 min from now
      $weatherRow = Weather::query()->where('city', Weather::$city )->first();
      $nowMinus55 = date( 'Y-m-d G:i:s', time() - 55*60);
      $weatherRow->api_update  = $nowMinus55;
      $weatherRow->save();
      $data3 = $weather->getDataFromCacheOrApiByCity();
      $this->assertNotEmpty($data3['api_update']);
      $this->assertEquals($nowMinus55, $data3['api_update']);

      //data in db, and api_update - 65 min from now
      $weatherRow2 = Weather::query()->where('city', Weather::$city )->first();
      $nowMinus65 = date( 'Y-m-d G:i:s', time() - 65*60);
      $weatherRow2->api_update  = $nowMinus65;
      $weatherRow2->save();
      //print_r($weatherRow2->toArray());
      $data4 = $weather->getDataFromCacheOrApiByCity(Weather::$city, true);
      //echo "\n"."--".$data['api_update'].'--'.$data2['api_update'].'--'.$data3['api_update'].'--'.$data4['api_update'];
      $this->assertNotEmpty($data4['api_update']);
      $this->assertNotEquals($nowMinus65, $data4['api_update']);
      //print_r($data4);
    }


    /**
    *  GetDataFromOpenWeatherApi
    */
    public function testGetDataFromOpenWeatherApi()
    {
      $req = Weather::GetDataFromOpenWeatherApi();
      $this->assertNotEmpty($req);

      $temp = Weather::GetTempFromReq($req);
      $this->assertNotEmpty($temp);
      $this->assertIsFloat( $temp);

      $humidity = Weather::GetHumidityFromReq($req);
      $this->assertNotEmpty($humidity);
      $this->assertIsInt( $humidity);
    }

    public function testSaveDataToDb()
    {
      $data = [
        'temp' => (double)11.23,
        'humidity' => (int)87
      ];

      $weather = new Weather;
      $weather->saveDataToDb($data);

      $row = $weather->getFirstRecordByCity( Weather::$city )->toArray();
      $this->assertEquals($row['temp'], $data['temp'] );
      $this->assertEquals($row['humidity'], $data['humidity'] );
    }
}
