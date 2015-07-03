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
    $tracks = [];

    // Get tracks in a room
    $tracks = array_map(function($id) use ($redis)
    {
        return [
            'track' => $redis->get($id),
            'score' => $redis->zscore('r-1234567890', $id)
        ];
    }, $redis->zrevrangebyscore('r-1234567890', '+inf', '-inf'));

    return response()->json($tracks);
});

// Add a track to the queue
$app->post('/api/queue/add', function(Request $request) use ($app)
{
    $redis = app('redis');
    $track = json_decode($request->get('track'));

    // Store the track information
    $redis->set($track->id, json_encode($track));

    // Add the track to the room
    $redis->zadd('r-1234567890', 0, $track->id);

    return response()->json();
});

// Play the current song in the queue
$app->get('/api/queue/play', function() use ($app)
{
    $redis = app('redis');

    $tracks = array_map(function($id) use ($redis)
    {
        return $redis->get($id);
    }, $redis->zrevrangebyscore('r-1234567890', '+inf', '-inf'));

    return response()->json(reset($tracks));
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

$app->post('/api/queue/upvote', function(Request $request) use ($app)
{
    $redis = app('redis');
    $track = json_decode($request->get('track'));
    return response()->json($redis->zincrby('r-1234567890', 1, $track->id));
});

$app->post('/api/queue/downvote', function(Request $request) use ($app)
{
    $redis = app('redis');
    $track = json_decode($request->get('track'));
    $redis->zincrby('r-1234567890', -1, $track->id);

    if ($redis->zscore('r-1234567890', $track->id) <= -5) {
        $redis->zrem('r-1234567890', $track->id);
    }

    return response()->json();
});
