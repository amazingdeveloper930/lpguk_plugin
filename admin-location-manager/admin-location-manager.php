<?php
/**
* Plugin Name: Admin Location Manager
* Plugin URI: https://newsite.lpguk.info/
* Description: This is one which help user manage location.
* Version: 1.0
* Author: Lucky
* Author URI: https://newsite.lpguk.info/
**/


require_once('create_location_menu.php');

if( !function_exists('custom_location_callback'))
{
    function custom_location_callback()
    {
        require_once('location_menu_view.php');
    }
}

require_once('location_api.php');


