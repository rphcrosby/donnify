<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app)
{
    return view('layout');
});

// Volume
$app->get('/api/volume/{volume}', function($volume) use ($app)
{

});

// Show the next track that's playing
$app->get('/api/tracks/next', function() use ($app)
{

});

// Show the current track that's playing
$app->get('/api/tracks/playing', function() use ($app)
{

});

// List the top tracks that are played
$app->get('/api/tracks/top', function() use ($app)
{

});

// List out the current track queue
$app->get('/api/queue/list', function() use ($app)
{
    $redis = app('redis');
    return response()->json($redis->lrange('r-1234567890', 0, -1));
});

// Add a track to the queue
$app->post('/api/queue/add', function(Request $request) use ($app)
{
    $redis = app('redis');
    $track = $request->get('track');
    $redis->rpush('r-1234567890', $track);
    return response()->json();
});

// Play the current song in the queue
$app->get('/api/queue/play', function() use ($app)
{
    $redis = app('redis');
    return response()->json($redis->lrange('r-1234567890', 0, 0));
});

// Play the current song in the queue
$app->get('/api/queue/pop', function() use ($app)
{
    $redis = app('redis');
    $redis->lpop('r-1234567890');
    return response()->json();
});

// Pause the current song in the queue
$app->post('/api/queue/pause', function(Request $request) use ($app)
{
    $redis = app('redis');
    $track = $request->get('track');
    $redis->lpush('r-1234567890', $track);
    return response()->json();
});

// Remove a song from the end of the queue
$app->get('/api/queue/remove', function() use ($app)
{
    $redis = app('redis');
    $redis->pop('r-1234567890');
    return response()->json();
});

$app->get('/api/queue/clear', function() use ($app)
{
    $redis = app('redis');
    $redis->del('r-1234567890');
    return response()->json();
});
