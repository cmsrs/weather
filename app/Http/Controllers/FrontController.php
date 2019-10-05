<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use JWTAuth;
use App\Weather;

class FrontController extends Controller
{
  public function index()
  {
    $weather = new Weather;
    $data = $weather->getDataFromCacheOrApiByCity()->toArray();
    return view('index', [ 'data' => $data ] );
  }
}
