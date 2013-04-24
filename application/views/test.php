<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('template/css/soundcloud.css'); ?>"/>
        <script src="<?php echo base_url('template/js/jquery-1.8.3.min.js'); ?>"></script>
        <script src="//connect.soundcloud.com/sdk.js"></script>
        <script>
            SC.initialize({
                client_id: "<?php echo $client_id; ?>",
                redirect_uri: "<?php echo $redirect_url; ?>"
            });
        </script>
    </head>
    <body>
        <span class="ctrl-btn-container">
            <a href="#" id="startRecording"><span class="ctrl-btn rec"></span></a>
            <a href="#" id="stopRecording"><span class="ctrl-btn stop"></span></a>
            <a href="#" id="playBack"><span class="ctrl-btn play"></span></a>
            <a href="#" id="pause"><span class="ctrl-btn pause"></span></a>
            <a href="#" id="upload"><span class="sc-upload"></span></a>
        </span>
        <p class="status"></p>
    </body>
    <script>
        $(document).ready(function() {
            $('#startRecording').click(function(e) {
                e.preventDefault();
                SC.record({
                    progress: function(ms, avgPeak) {
                        updateTimer(ms);
                    }
                });
            });

            $('#stopRecording').click(function(e) {
                e.preventDefault();
                SC.recordStop();
            });
            $('#playBack').click(function(e) {
                e.preventDefault();
                updateTimer(0);
                SC.recordPlay({
                    progress: function(ms) {
                        updateTimer(ms);
                    }
                });
            });
            $('#upload').click(function(e) {
                e.preventDefault();
                SC.accessToken('<?php if(isset($access_token)) echo $access_token; ?>');
                $('.status').html('Uploading...');
                SC.recordUpload({
                    track: {
                        title: 'My test Recording',
                        sharing: 'public'
                    }
                }, function(track) {
                    $('.status').html("Uploaded: <a href='" + track.permalink_url + "'>" + track.permalink_url + "</a>");
                });
            });
        });

        // Helper methods for our UI.

        function updateTimer(ms) {
            // update the timer text. Used when we're recording
            $('.status').text(SC.Helper.millisecondsToHMS(ms));
        }
    </script>
</html>