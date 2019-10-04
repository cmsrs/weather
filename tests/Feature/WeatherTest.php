<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Weather;


class WeatherTest extends TestCase
{
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
}
