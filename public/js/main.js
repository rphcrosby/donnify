$(document).ready(function() {

    $('.search .js-search').keyup(function() {
        var q = $(this).val();
        searchYoutube(q, function(response) {
            displayYoutubeResults(response.items);
        });
    });

    refreshQueue();
});

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

var playYoutube = function(code) {

    var embed = '<iframe width="1" height="1" src="https://www.youtube.com/embed/' + code + '?autoplay=1" frameborder="0" allowfullscreen></iframe>';

    $('#youtube').html(embed);
};

var clearResults = function() {

    $('.js-results').html('');
}

var displayYoutubeResults = function(items) {

    clearResults();

    for (var i in items) {

        var item = items[i];

        var row = $('<li data-video="' + item.id.videoId + '"">' + item.snippet.title + '</li>');

        row.click(addYoutubeToQueue);

        row.data('track', item.snippet);

        row.appendTo($('.js-results'));
    }
};
