<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Web API Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body >

  <div class="container">
    <h1>Song: {{$song['name']}}</h1>
    <h1>Artist: {{$song['artist']}}</h1>
    <h1>Album: {{$song['album']}}</h1>
    <img class="w-24" src="{{$song['cover_art']}}" alt="" class="logo"/>
  </div>

</body>

</html>