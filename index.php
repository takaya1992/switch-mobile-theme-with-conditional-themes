<?php
/*
Plugin Name: Switch Mobile Theme With Conditional Themes
Description: switch mobile theme with conditional Themes plugin
Version: 1.0.0
Author: takaya1992
Author URI: http://takaya1992.com/
License: GPLv2 or later
 */

/*
WP Change Default Author is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Change Default Author is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Switch Mobile Theme With Conditional Themes. If not, see https://www.gnu.org/licenses/gpl-3.0.txt .
*/

define( 'SWITCH_MOBILE_THEME_WITH_CONDITIONAL_THEMES_VERSION', '1.0.0' );

define( 'SWITCH_MOBILE_THEME_WITH_CONDITIONAL_THEMES__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( SWITCH_MOBILE_THEME_WITH_CONDITIONAL_THEMES__PLUGIN_DIR . 'classes/SwitchMobileTheme.php' );

SwitchMobileTheme::init();

function SwitchMobileTheme__uninstall() {
	delete_option( SwitchMobileTheme::MOBILE_THEME_OPTION_NAME );
}
register_uninstall_hook( __FILE__, 'SwitchMobileTheme__uninstall' );
