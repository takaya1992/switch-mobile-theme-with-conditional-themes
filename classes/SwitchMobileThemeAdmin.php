<?php

class SwitchMobileThemeAdmin {

	public static function init_hooks() {
		add_action( 'admin_menu', array( 'SwitchMobileThemeAdmin', 'admin_menu' ) );
	}

	public static function admin_menu() {
		add_options_page(
			__( 'Mobile Theme', 'switch-mobile-theme-with-conditional-themes' ),
			__( 'Mobile Theme', 'switch-mobile-theme-with-conditional-themes' ),
			'manage_options',
			'mobile-theme',
			array( 'SwitchMobileThemeAdmin', 'option_page' )
		);

		add_settings_field(
			'siwtch-mobile-theme-mobile-theme',
			__( 'Mobile Theme', 'switch-mobile-theme-with-conditional-themes' ),
			array( 'SwitchMobileThemeAdmin', 'theme_field' ),
			'mobile-theme',
			'default'
		);

		register_setting(
			'mobile-theme',
			SwitchMobileTheme::MOBILE_THEME_OPTION_NAME,
			array(
				'type' => 'string',
				'description' => __( 'mobile theme.', 'switch-mobile-theme-with-conditional-themes' ),
				'sanitize_callback' => array( 'SwitchMobileThemeAdmin', 'mobile_theme_sanitize_callback' )
			)
		);
	}

	public static function option_page() {
		$title = __( 'Mobile Theme', 'switch-mobile-theme-with-conditional-themes' );
		$is_installed_CondditionalThemes = SwitchMobileTheme::is_installed_ConditionalThemes();
		$plugin_name_with_anchor = '<a href="'. SwitchMobileTheme::CONDITIONAL_THEMES_PLUGIN_URL . '" target="_blank" rel="noopener noreferrer">Conditional Themes</a>';
?>
<div class="wrap">
	<h1><?php echo esc_html( $title ); ?></h1>

	<?php if ( ! $is_installed_CondditionalThemes ): ?>
		<div class="notice notice-error">
			<p><strong><?php printf( __( '%s plugin is not installed or activated.', 'switch-mobile-theme-with-conditional-themes' ), $plugin_name_with_anchor ); ?></strong></p>
		</div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'mobile-theme' ); ?>

		<table class="form-table" role="presentation">
			<?php do_settings_fields( 'mobile-theme', 'default' ); ?>
		</table>

		<?php do_settings_sections( 'mobile-theme' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
<?php
	}

	public static function theme_field() {
		$selected_option = get_option( SwitchMobileTheme::MOBILE_THEME_OPTION_NAME );
        $values = array();
		foreach ( wp_get_themes( array( 'errors' => null ) ) as $a_stylesheet => $a_theme ) {
			if ( $a_theme->errors() && 'theme_no_stylesheet' == $a_theme->errors()->get_error_code() ) {
				continue;
            }
            $value = array(
                'value'    => $a_stylesheet,
                'label'    => $a_theme->display( 'Name' ),
                'selected' => ($a_stylesheet === $selected_option)
            );
            array_push( $values, $value );
		}
		self::echo_dropdown(
            array(
                'id' => SwitchMobileTheme::MOBILE_THEME_OPTION_NAME,
                'name' => SwitchMobileTheme::MOBILE_THEME_OPTION_NAME,
                'values' => $values
            )
        );
	}

	public static function mobile_theme_sanitize_callback( $value ) {
		return $value;
	}

    public static function echo_dropdown( $values ) {
        $select_attrs = array();
        if ( isset( $values['id'] ) ) {
            $select_attrs['id'] = $values['id'];
        }
        if ( isset( $values['name'] ) ) {
            $select_attrs['name'] = $values['name'];
        }
        if ( isset( $values['class'] ) ) {
            $select_attrs['class'] = $values['class'];
        }

        $select_html_attrs = self::html_attrs( $select_attrs );
?>
<select <?php echo $select_html_attrs ?>>
    <?php foreach ($values['values'] as $value): ?>
        <?php $selected = isset( $value['selected'] ) && $value['selected'] ? 'selected="selected"' : ''; ?>
        <option value="<?php echo esc_attr( $value['value'] )  ?>" <?php echo $selected ?>><?php echo $value['label'] ?></option>
    <?php endforeach; ?>
</select>
<?php
    }

    public static function html_attrs( $values ) {
        $attrs = array();
        foreach ( $values as $name => $value ) {
            if ( $value === true ) {
                array_push( $attrs, $name );
            } else {
                array_push( $attrs, $name . '="' . esc_attr( $value ) . '"' );
            }
        }
        return implode( ' ', $attrs );
    }
}
