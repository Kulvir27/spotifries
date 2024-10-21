<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;

class SpotifyController extends Controller
{
    private $client_id;
    private $client_secret;

    // upon object creation, set the client_id and client_secret
    public function __construct(){
        $this->client_id = env('SPOTIFY_CLIENT_ID');
        $this->client_secret = env('SPOTIFY_CLIENT_SECRET');
    }

    public function index() : RedirectResponse {
        // generate a random string
        $code_verifier = bin2hex(random_bytes(16));

        // store the state in session
        request()->session()->put('code_verifier', $code_verifier);

        // redirect the user to spotify for authentication
        return redirect('https://accounts.spotify.com/authorize?client_id='. $this->client_id. '&redirect_uri='.
                    'http://127.0.0.1:8000/spotifries'. '&response_type=code&scope=user-read-private%20user-read-email&state='.
                    $code_verifier);
    }

    public function authorize() {
        // grab code from url param
        $code = request()->code;

        // store code in session on server side
        request()->session()->put('auth_code', $code);

        // send POST request to /api/token endpoint
        $access_token = $this->getAccessToken();

        // store access token in session on server side
        request()->session()->put('access_token', $access_token);

        return view('spotifries');
    }

    private function getAccessToken() {

        // encode client_id and client_secret in base64 format
        $auth = base64_encode($this->client_id . ':' . $this->client_secret);
        $url = 'https://accounts.spotify.com/api/token';

        // send POST request to /api/token endpoint, and get access token
        $client = new Client();
        $response = $client->request('POST',$url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '. $auth
            ]
            ,
            'form_params' => [
                'grant_type' => 'client_credentials',
            ]
        ]);

        // decode response body as associative array
        $response = json_decode($response->getBody(), true);

        return $response['access_token'];
    }


    public function getSong() {

        // get search query from the request
        $search = request()->search;
        $api_url = 'https://api.spotify.com/v1/search?q=' . $search . '&type=track';

        // get access token from local session storage
        $access_token = session('access_token');

        // send a GET request to the spotify API to fetch song details
        $client = new Client();
        $response = $client->request('GET', $api_url, [
            'headers' => [
                'Authorization' => 'Bearer '. $access_token
            ]
        ]);


        // decode response body as associative array
        $response = json_decode($response->getBody(), true);

        $song = [
            'name' => $response['tracks']['items'][0]['name'],
            'artist' => $response['tracks']['items'][0]['artists'][0]['name'],
            'album' => $response['tracks']['items'][0]['album']['name'],
            'cover_art' => $response['tracks']['items'][0]['album']['images'][0]['url'],
            'link' => $response['tracks']['items'][0]['external_urls']['spotify']
            ];

        // 3. display the song details on the song view page
        return view('song', [
            'song' => $song
        ]);
    }
}
