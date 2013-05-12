<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */

/*
|--------------------------------------------------------------------------
| Youtube authentication
|--------------------------------------------------------------------------
|
| Google account login info
|
*/
$config['youtube_email'] = "crowdlator@gmail.com"; // Google account Username
$config['youtube_password'] = "Crowd321"; // Google account Password
$config['youtube_user_id'] = "UC6qPSV2TABI_VJ0uknEKw5g"; // Youtube account user id
$config['youtube_source'] = "Crowdlator testing environment"; // A short string that identifies your application for logging purposes.
// Google Developer Key
$config['youtube_developer_key'] = "AI39si56K64hGlavx6j60-cSqfmsRS7Gm4PnEX3gXcEXZ0pf0hd-nwE8qWRLx8gPdt70uiGhfMB5Uur4t5d-eOJpnJGl6q2t3A";
// IMPORTANT: Please include HTTP or HTTPS protocol in the redirect URL after a successful upload
// Please do not change the other part of the URL until you know what you are doing. Change only the host and Crowdlator path.
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
$config['youtube_oauth_consumer_key'] = '';
$config['youtube_oauth_consumer_secret'] = '';
$config['youtube_algorithm'] = '';

/* End of file youtube_config.php */
/* Location: ./application/config/youtube_config.php */