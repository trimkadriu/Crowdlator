<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */

/*
|--------------------------------------------------------------------------
| SoundCloud authentication
|--------------------------------------------------------------------------
|
| SoundCloud account login info
|
*/
$config['soundcloud_email'] = "crowdlator@gmail.com"; // SoundCloud account email
$config['soundcloud_password'] = "Crowd321"; // SoundCloud account password
$config['soundcloud_client_id'] = "cceb3000b09a4d3a8153cd3ef6c14429"; // SoundCloud Client ID
$config['soundcloud_client_secret'] = "b71d9313562cb97c6913b3f6f3b30bc9"; // SoundCloud Client Secret
$config['soundcloud_redirect_url'] = "http://localhost/crowdlator/admin/projects/check_soundcloud"; // Redirect URL
$config['soundcloud_auth_url'] = "https://soundcloud.com/connect"; // SoundCloud end user authorisation URL
$config['soundcloud_oauth_url'] = "https://api.soundcloud.com/oauth2/token"; // SoundCloud oAuth2 token URL

/* End of file soundcloud_config.php */
/* Location: ./application/config/soundcloud_config.php */