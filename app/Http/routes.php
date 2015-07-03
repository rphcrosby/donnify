<?php

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

// List out the current track queue
$app->get('/api/tracks/queue', function() use ($app)
{

});

// List the top tracks that are played
$app->get('/api/tracks/top', function() use ($app)
{

});

// Add a track to the queue
$app->get('/api/queue/add', function() use ($app)
{

});

// Play the current song in the queue
$app->get('/api/queue/play', function() use ($app)
{

});

// Pause the current song in the queue
$app->get('/api/queue/pause', function() use ($app)
{

});

// Remove a song from the end of the queue
$app->get('/api/queue/remove', function() use ($app)
{

});
