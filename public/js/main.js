$(document).ready(function() {

    $('.search .js-search').keyup(function() {
        var q = $(this).val();
        searchYoutube(q, function(response) {
            displayYoutubeResults(response.items);
        });
    });

    $('.debug .js-clear').click(function() {
        $.get('/api/queue/clear', function() {
            refreshQueue();
        });
    });

    $('.js-play').click(togglePlay);

    refreshQueue();
});

var App = {
    playing: false,
    track: null,
    timer: null,
    currentTime: 0
};

var togglePlay = function() {

    if (App.playing == false) {
        play();
    } else {
        pause();
    }
};

var pause = function() {
    App.playing = false;
    pauseYoutube();
    clearInterval(App.timer);
}

var pauseYoutube = function() {
    $('#youtube').html('');
}

var updateBar = function() {

    var track = App.track;
    var duration = track.details.contentDetails.duration;
    var lengths = duration.replace('PT', '').split('M');
    var minutes = lengths[0];
    var seconds = (minutes * 60) + parseInt(lengths[1].replace('S', ''));

    console.log(seconds);

    var percentage = (App.currentTime / seconds) * 100;

    $('.player .player__track .bar .current').css('width', percentage + '%');
};

var play = function(ev) {

    App.playing = true;

    App.timer = setInterval(function() {
        App.currentTime += 1;
        updateBar();
    }, 1000);

    if (App.track) {
        playTrack(App.track, App.currentTime);
        return;
    } else {

        App.currentTime = 0;

        $.get('/api/queue/play', function(track) {
            var track = JSON.parse(track);

            if (track.details == undefined) {
                getYoutubeDetails(track.id, function(details) {
                    track.details = details;
                    App.track = track;
                });
            }

            playTrack(track);

            refreshQueue();
        });
    }
};

var playTrack = function(track, time) {

    switch (track.type) {

            case 'youtube':
                playYoutube(track.id, time);
                break;
        }
}

var playYoutube = function(code, time) {

    if (time == undefined) {
        time = 0;
    }

    var embed = '<iframe width="1" height="1" src="https://www.youtube.com/embed/' + code + '?autoplay=1&start=' + time + '" frameborder="0" allowfullscreen></iframe>';

    $('#youtube').html(embed);
};

var getYoutubeDetails = function(code, callback) {

    var url = 'https://www.googleapis.com/youtube/v3/videos?part=contentDetails';

    // Add key
    url += '&key=AIzaSyBK9_Su3qBVrBKdJUzDpjJmmwGu_e7Xa3I';

    // Add video ID
    url += '&id=' + code;

    $.getJSON(url, function(response)
    {
        callback(response.items[0]);
    });
}

var refreshQueue = function() {
    console.debug('Refreshing queue');

    $.get('/api/queue/list', function(queue) {
        $('.js-queue').html('');
        _.each(queue, function(track) {
            var track = JSON.parse(track);
            var html = $('<li data-video=' + track.id + '>' + track.track.title + '</li>');
            html.appendTo($('.js-queue'));
        });
    });
};

var addYoutubeToQueue = function(ev) {

    var item = $(ev.target);
    var id = item.attr('data-video');
    var track = {
        type: 'youtube',
        id: id,
        track: item.data('track')
    };

    $.post('/api/queue/add', { track: JSON.stringify(track) }, function(response) {
        console.debug('Track added to queue');
        refreshQueue();
        clearResults();
    });
};

var searchYoutube = _.throttle(function(query, callback) {

    var url = 'https://www.googleapis.com/youtube/v3/search?part=snippet';

    // Add key
    url += '&key=AIzaSyBK9_Su3qBVrBKdJUzDpjJmmwGu_e7Xa3I';

    // Add type
    url += '&type=video';

    // Add search query
    url += '&q=' + encodeURIComponent(query);

    $.getJSON(url, function(json) {
        callback(json);
    });
}, 200);

var clearResults = function() {

    $('.js-results').html('');
    $('.results').removeClass('is-visible');
}

var displayYoutubeResults = function(items) {

    clearResults();

    for (var i in items) {

        var item = items[i];

        var row = $('<li data-video="' + item.id.videoId + '"">' + item.snippet.title + '</li>');

        row.click(addYoutubeToQueue);

        row.data('track', item.snippet);

        row.appendTo($('.js-results'));

        $('.results').addClass('is-visible');
    }
};
