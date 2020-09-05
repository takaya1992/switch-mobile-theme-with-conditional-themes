<?php

require_once( SWITCH_MOBILE_THEME_WITH_CONDITIONAL_THEMES__PLUGIN_DIR . 'classes/SwitchMobileThemeAdmin.php' );

class SwitchMobileTheme {

	const MOBILE_THEME_OPTION_NAME = 'switch_mobile_theme_with_conditional_themes__mobile_theme';
	const CONDITIONAL_THEMES_CLASSNAME = 'Conditional_Themes_Manager';
	const CONDITIONAL_THEMES_PLUGIN_URL = 'https://wordpress.org/plugins/wp-conditional-themes/';

    private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;

		add_action( 'plugins_loaded', array( 'SwitchMobileTheme', 'mobile_theme_setup' ) );
		SwitchMobileThemeAdmin::init_hooks();
    }


    public static function mobile_theme_setup() {
		$mobile_theme = get_option( self::MOBILE_THEME_OPTION_NAME );
		if ( self::is_installed_ConditionalThemes() && $mobile_theme ) {
			$classname = self::CONDITIONAL_THEMES_CLASSNAME;
			$classname::register( $mobile_theme, self::is_mobile() );
        }
    }

    public static function is_mobile() {
        if ( isset( $_SERVER['HTTP_CLOUDFRONT_IS_TABLET_VIEWER'] ) && "true" === $_SERVER['HTTP_CLOUDFRONT_IS_TABLET_VIEWER'] ) {
            return false;
        }

        if ( isset( $_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER'] ) && "true" === $_SERVER['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER'] ) {
            return true;
		}

        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/iphone|android/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
            return true;
        }

        return false;
	}

	public static function is_installed_ConditionalThemes() {
		return class_exists( 'Conditional_Themes_Manager' );
	}
}
