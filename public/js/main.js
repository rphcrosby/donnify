$(document).ready(function() {

    $('#search').keyup(function() {
        var q = $(this).val();
        searchYoutube(q, function(response) {
            displayYoutubeResults(response.items);
        });
    });
});

var playOnClick = function(ev) {
    var code = $(ev.target).attr('data-video');
    playYoutube(code);
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

var displayYoutubeResults = function(items) {

    $('#youtube-results').html('');

    for (var i in items) {

        var item = items[i];

        var row = $('<li data-video="' + item.id.videoId + '"">' + item.snippet.title + '</li>');

        row.click(playOnClick);

        row.appendTo($('#youtube-results'));
    }
};
