<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Youtube authentication
|--------------------------------------------------------------------------
|
| Google account login info
|
*/
$config['youtube_email'] = "trim.kadriu@gmail.com"; // Google account Username
$config['youtube_password'] = "lladika001!!"; // Google account Password
$config['youtube_user_id'] = "UCIRty4QD7NlxrxQdv-zcDpQ"; // Youtube account user id
$config['youtube_source'] = "Crowdlator testing environment"; // A short string that identifies your application for logging purposes.
// Google Developer Key
$config['youtube_developer_key'] = "AI39si51BcMmO0DPlDRNHxLuhSkuDYnLKfg2-U_-132Sqd3zD1mGCOze7SGn_ItvFUKjfR0jnym-5gw-4UMDUG5BNmo2g9woiw";
// IMPORTANT: Please include HTTP or HTTPS protocol in the redirect URL after a successful upload
$config['youtube_next_url'] = "http://localhost/crowdlator/admin/projects/check_project";

/*
|--------------------------------------------------------------------------
| Category & Keywords
|--------------------------------------------------------------------------
|
| You can set the following CATEGORY:
|
|    Film, Autos, Music, Animals, Sports, Travel, Shortmov, Games
|    Videblog, People, Comedy, Entertainment, News, Howto, Education, Tech, Nonprofit, Movies
|    Movies_anime_action, Movies_action_adventure, Movies_classics, Movies_comedy
|    Movies_documentary, Moves_drama, Movies_family, Movies_foreign, Movies_horror
|    Movies_sci_fi_fantasy, Movies_thriller, Movies_shorts, Shows, Trailers
|
*/
$config['youtube_keywords'] = "demo";
$config['youtube_category'] = "Tech";

/*
|--------------------------------------------------------------------------
| OPTIONAL Configuration
|--------------------------------------------------------------------------
|
| Only if application will scale and decide to use oAuth for different google users
|
*/
$config['youtube_oauth_consumer_key'] = 'www.mesofizike.com';
$config['youtube_oauth_consumer_secret'] = 'Jm9Gf5hHnjXANxvyzC0-bdRv';
$config['youtube_algorithm'] = 'HMAC-SHA1';

/* End of file youtube_config.php */
/* Location: ./application/config/youtube_config.php */