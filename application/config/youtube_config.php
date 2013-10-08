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
// Google account Username
$config['youtube_email'] = "crowdlator@gmail.com";
// Google account Password (Second level password - please refer to READ-ME document for details)
$config['youtube_password'] = "mysbyhdervqtgkeo";
// Youtube account user id (You can get this by logging in to youtube.com and click "My Channel" then notice the URL
// is something like this: https://www.youtube.com/channel/UC6qPSV2TABI_VJ0uknEKw5g - The last part is the USER ID)
$config['youtube_user_id'] = "UC6qPSV2TABI_VJ0uknEKw5g";
// A short string that identifies your application for logging purposes. (It's not that necessary)
$config['youtube_source'] = "Crowdlator testing environment";
// Google Developer Key (Please refer to READ-ME document for details)
$config['youtube_developer_key'] = "AI39si56K64hGlavx6j60-cSqfmsRS7Gm4PnEX3gXcEXZ0pf0hd-nwE8qWRLx8gPdt70uiGhfMB5Uur4t5d-eOJpnJGl6q2t3A";
// IMPORTANT: Please include HTTP or HTTPS protocol in the redirect URL after a successful upload
// Please do not change the other part of the URL until you know what you are doing. Change only the host and Crowdlator path.
// e.g. http://www.yoursite.com/path-to-crowdlator/crowdlator/admin/projects/check_project
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