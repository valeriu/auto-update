<?php
/**
 * Main plugin class.
 *
 * @package Auto_Update
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin bootstrap and settings.
 *
 * @since 1.1.0
 */
class ATU_Auto_Update_Plugin {

	/**
	 * Option name used to store plugin settings.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	const ATU_OPTION_NAME = 'atu_auto_update_settings';

	/**
	 * Settings group slug.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	const ATU_SETTINGS_GROUP = 'atu_auto_update';

	/**
	 * Settings section slug.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	const ATU_SETTINGS_SECTION = 'atu_auto_update_general';

	/**
	 * Settings page slug.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	const ATU_SETTINGS_PAGE = 'atu-auto-update';

	/**
	 * Sets up the default option on plugin activation.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function atu_activate(): void {
		if ( false === get_option( self::ATU_OPTION_NAME, false ) ) {
			add_option( self::ATU_OPTION_NAME, self::atu_get_default_settings() );
		}
	}

	/**
	 * Plugin constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'atu_maybe_enable_updates' ) );
		add_action( 'admin_init', array( $this, 'atu_register_settings' ) );
		add_action( 'admin_menu', array( $this, 'atu_register_settings_page' ) );
		add_filter(
			'plugin_action_links_' . plugin_basename( ATU_PLUGIN_FILE ),
			array( $this, 'atu_add_plugin_action_links' )
		);
	}

	/**
	 * Enables update-related filters based on saved settings.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function atu_maybe_enable_updates(): void {
		add_filter( 'allow_minor_auto_core_updates', array( $this, 'atu_filter_minor_core_updates' ) );
		add_filter( 'allow_major_auto_core_updates', array( $this, 'atu_filter_major_core_updates' ) );
		add_filter( 'auto_update_plugin', array( $this, 'atu_filter_plugin_updates' ), 10, 2 );
		add_filter( 'auto_update_theme', array( $this, 'atu_filter_theme_updates' ), 10, 2 );
	}

	/**
	 * Registers the plugin settings and fields.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function atu_register_settings(): void {
		register_setting(
			self::ATU_SETTINGS_GROUP,
			self::ATU_OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'atu_sanitize_settings' ),
				'default'           => self::atu_get_default_settings(),
			)
		);

		add_settings_section(
			self::ATU_SETTINGS_SECTION,
			__( 'Automatic update preferences', 'auto-update' ),
			array( $this, 'atu_render_settings_section' ),
			self::ATU_SETTINGS_PAGE
		);

		$this->atu_register_checkbox_field(
			'core_minor',
			__( 'Minor core updates', 'auto-update' ),
			__( 'Allow automatic updates for minor WordPress core releases.', 'auto-update' )
		);
		$this->atu_register_checkbox_field(
			'core_major',
			__( 'Major core updates', 'auto-update' ),
			__( 'Allow automatic updates for major WordPress core releases.', 'auto-update' )
		);
		$this->atu_register_checkbox_field(
			'plugins',
			__( 'Plugin updates', 'auto-update' ),
			__( 'Allow automatic updates for installed plugins.', 'auto-update' )
		);
		$this->atu_register_checkbox_field(
			'themes',
			__( 'Theme updates', 'auto-update' ),
			__( 'Allow automatic updates for installed themes.', 'auto-update' )
		);
	}

	/**
	 * Registers the settings page.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function atu_register_settings_page(): void {
		add_options_page(
			__( 'Auto Update', 'auto-update' ),
			__( 'Auto Update', 'auto-update' ),
			'manage_options',
			self::ATU_SETTINGS_PAGE,
			array( $this, 'atu_render_settings_page' )
		);
	}

	/**
	 * Renders the settings page.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function atu_render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Auto Update', 'auto-update' ); ?></h1>
			<?php settings_errors( self::ATU_OPTION_NAME ); ?>
			<p>
				<?php esc_html_e( 'Choose which automatic updates to enable for this site and save your preferences. All update types are enabled by default.', 'auto-update' ); ?>
			</p>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::ATU_SETTINGS_GROUP );
				do_settings_sections( self::ATU_SETTINGS_PAGE );
				submit_button( __( 'Save Changes', 'auto-update' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Renders the settings section description.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function atu_render_settings_section(): void {
		echo '<p>' . esc_html__( 'Select which update types WordPress should handle automatically.', 'auto-update' ) . '</p>';
	}

	/**
	 * Renders a settings checkbox field.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Field arguments.
	 * @return void
	 */
	public function atu_render_checkbox_field( array $args ): void {
		$settings    = $this->atu_get_settings();
		$field_key   = $args['key'];
		$field_id    = 'atu-auto-update-' . $field_key;
		$is_checked  = ! empty( $settings[ $field_key ] );
		$description = $args['description'];
		?>
		<label for="<?php echo esc_attr( $field_id ); ?>">
			<input
				type="checkbox"
				id="<?php echo esc_attr( $field_id ); ?>"
				name="<?php echo esc_attr( self::ATU_OPTION_NAME . '[' . $field_key . ']' ); ?>"
				value="1"
				<?php checked( $is_checked ); ?>
			/>
			<?php echo esc_html( $description ); ?>
		</label>
		<?php
	}

	/**
	 * Sanitizes submitted settings.
	 *
	 * @since 1.1.0
	 *
	 * @param mixed $input Raw submitted settings.
	 * @return array
	 */
	public function atu_sanitize_settings( $input ): array {
		if ( ! is_array( $input ) ) {
			$input = array();
		}

		$sanitized = self::atu_get_default_settings();

		foreach ( array( 'core_minor', 'core_major', 'plugins', 'themes' ) as $key ) {
			$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $sanitized;
	}

	/**
	 * Adds plugin action links.
	 *
	 * @since 1.1.0
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function atu_add_plugin_action_links( array $links ): array {
		$settings_url = admin_url( 'options-general.php?page=' . self::ATU_SETTINGS_PAGE );
		$support_url  = 'https://wordpress.org/support/plugin/auto-update';

		array_unshift(
			$links,
			'<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'auto-update' ) . '</a>'
		);

		$links[] = '<a href="' . esc_url( $support_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support', 'auto-update' ) . '</a>';

		return $links;
	}

	/**
	 * Filters minor core auto-updates.
	 *
	 * @since 1.1.0
	 *
	 * @param bool $update Whether minor core updates are enabled.
	 * @return bool
	 */
	public function atu_filter_minor_core_updates( bool $update ): bool {
		unset( $update );

		return $this->atu_is_option_enabled( 'core_minor' );
	}

	/**
	 * Filters major core auto-updates.
	 *
	 * @since 1.1.0
	 *
	 * @param bool $update Whether major core updates are enabled.
	 * @return bool
	 */
	public function atu_filter_major_core_updates( bool $update ): bool {
		unset( $update );

		return $this->atu_is_option_enabled( 'core_major' );
	}

	/**
	 * Filters plugin auto-updates.
	 *
	 * @since 1.1.0
	 *
	 * @param bool|null $update Whether the plugin should auto-update.
	 * @param object    $item   The update offer.
	 * @return bool
	 */
	public function atu_filter_plugin_updates( $update, $item ): bool {
		unset( $update, $item );

		return $this->atu_is_option_enabled( 'plugins' );
	}

	/**
	 * Filters theme auto-updates.
	 *
	 * @since 1.1.0
	 *
	 * @param bool|null $update Whether the theme should auto-update.
	 * @param object    $item   The update offer.
	 * @return bool
	 */
	public function atu_filter_theme_updates( $update, $item ): bool {
		unset( $update, $item );

		return $this->atu_is_option_enabled( 'themes' );
	}

	/**
	 * Gets the plugin settings with defaults applied.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private function atu_get_settings(): array {
		$settings = get_option( self::ATU_OPTION_NAME, array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return wp_parse_args( $settings, self::atu_get_default_settings() );
	}

	/**
	 * Checks whether a specific update option is enabled.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key Settings key.
	 * @return bool
	 */
	private function atu_is_option_enabled( string $key ): bool {
		$settings = $this->atu_get_settings();

		return ! empty( $settings[ $key ] );
	}

	/**
	 * Gets the default plugin settings.
	 *
	 * @since 1.1.0
	 *
	 * @return array
	 */
	private static function atu_get_default_settings(): array {
		return array(
			'core_minor' => 1,
			'core_major' => 1,
			'plugins'    => 1,
			'themes'     => 1,
		);
	}

	/**
	 * Registers a checkbox field for the settings page.
	 *
	 * @since 1.1.0
	 *
	 * @param string $key         Field key.
	 * @param string $title       Field title.
	 * @param string $description Field description.
	 * @return void
	 */
	private function atu_register_checkbox_field( string $key, string $title, string $description ): void {
		add_settings_field(
			$key,
			$title,
			array( $this, 'atu_render_checkbox_field' ),
			self::ATU_SETTINGS_PAGE,
			self::ATU_SETTINGS_SECTION,
			array(
				'key'         => $key,
				'description' => $description,
			)
		);
	}
}
