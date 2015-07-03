<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donnify</title>
    <link type="text/css" rel="stylesheet" href="/css/normalize.css">
    <link type="text/css" rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="container">
        <div class="search">
            <input type="text" placeholder="Search" class="js-search">

            <div class="results">
                <div class="modal">
                    <ul class="js-results"></ul>
                </div>
            </div>
        </div>
        <ul class="queue js-queue">

        </ul>
    </div>

    <div class="player">
        <div class="player__artwork"></div>
        <div class="player__title"></div>
        <div class="player__play js-play"></div>
        <div class="player__track">
            <div class="bar">
                <div class="bubble">0:00</div>
                <div class="full"></div>
                <div class="current"></div>
            </div>
        </div>
        <div class="player__duration js-duration">00:00</div>
    </div>

    <div class="providers">
        <div id="youtube"></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="/js/underscore.js"></script>
    <script src="/js/main.js"></script>
</body>
</html>

<!-- AIzaSyBK9_Su3qBVrBKdJUzDpjJmmwGu_e7Xa3I -->
