<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<div  class="container">
 <h1 class="m-1 mt-3 mb-3"> Weather in {{$data['city']}}</h1>
 <div class="row">
  <div class="text-center m-1 col p-3 mb-2 bg-secondary text-white ">Temperature: {{$data['temp']}} &#x2103;</div>
  <div class="text-center m-1 col p-3 mb-2 bg-info text-white ">Humidity: {{$data['humidity']}}</div>
 </div>
 <div class="row">
   <div class="m-1 col p-3 mb-2 bg-white text-dark">Last update: {{$data['api_update']}}</div>
 </div>
</div>
