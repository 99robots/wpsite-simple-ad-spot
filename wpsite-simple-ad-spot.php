<?php
/*
Plugin Name: WPSite Simple Ad Spot Beta
plugin URI:
Description:
version: 1.0
Author:
Author URI:
License: GPL2
*/

/**
 * Global Definitions
 */

/* Plugin Name */

if (!defined('WPSITE_SIMPLE_AD_SPOT_PLUGIN_NAME'))
    define('WPSITE_SIMPLE_AD_SPOT_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

/* Plugin directory */

if (!defined('WPSITE_SIMPLE_AD_SPOT_PLUGIN_DIR'))
    define('WPSITE_SIMPLE_AD_SPOT_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WPSITE_SIMPLE_AD_SPOT_PLUGIN_NAME);

/* Plugin url */

if (!defined('WPSITE_SIMPLE_AD_SPOT_PLUGIN_URL'))
    define('WPSITE_SIMPLE_AD_SPOT_PLUGIN_URL', WP_PLUGIN_URL . '/' . WPSITE_SIMPLE_AD_SPOT_PLUGIN_NAME);

/* Plugin verison */

if (!defined('WPSITE_SIMPLE_AD_SPOT_VERSION_NUM'))
    define('WPSITE_SIMPLE_AD_SPOT_VERSION_NUM', '1.0.0');


/**
 * Activatation / Deactivation
 */

register_activation_hook( __FILE__, array('WPsiteSimpleAdSpot', 'register_activation'));

/**
 * Hooks / Filter
 */

add_action('widgets_init',
     create_function('', 'return register_widget("WPsiteSimpleAdSpot");')
);

add_action('init', array('WPsiteSimpleAdSpot', 'load_textdomain'));

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", array('WPsiteSimpleAdSpot', 'plugin_links'));

/**
 *  WPsiteSimpleAdSpot main class
 *
 * @since 1.0.0
 * @using Wordpress 3.8
 */

class WPsiteSimpleAdSpot extends WP_Widget {

	/**
	 * text_domain
	 *
	 * (default value: 'wpsite_simple_ad_spot')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $text_domain = 'wpsite_simple_ad_spot';

	/**
	 * prefix
	 *
	 * (default value: 'wpsite_simple_ad_spot_')
	 *
	 * @var string
	 * @access private
	 * @static
	 */
	private static $prefix = 'wpsite_simple_ad_spot_';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wpsite-simple-ad-spot', // Base ID
			__('WPsite Simple Ad Spot', self::$text_domain), // Name
			array( 'description' => __( 'Display an Ad', self::$text_domain), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if (isset($instance['ad_sense']) && $instance['ad_sense'] != '') {
			echo $instance['ad_sense'];
		} else if (isset($instance['image']) && isset($instance['link']) && $instance['image'] != '' && $instance['link'] != '') {
			echo '<div class="wps-sas-container"><a class="wps-sas-link" href="' . $instance['link'] . '" target="_blank"><img class="wps-sas-image" src="' . $instance['image'] . '"/></a></div>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Simple Ad Spot', self::$text_domain);
		}

		if ( isset( $instance[ 'ad_sense' ] ) ) {
			$ad_sense = $instance[ 'ad_sense' ];
		}
		else {
			$ad_sense = '';
		}

		if ( isset( $instance[ 'image' ] ) ) {
			$image = $instance[ 'image' ];
		}
		else {
			$image = '';
		}

		if ( isset( $instance[ 'link' ] ) ) {
			$link = $instance[ 'link' ];
		}
		else {
			$link = '';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', self::$text_domain); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ad_sense' ); ?>"><?php _e( 'Ad Sense Code:', self::$text_domain); ?></label>
			<textarea cols="46" rows="10" id="<?php echo $this->get_field_id( 'ad_sense' ); ?>" name="<?php echo $this->get_field_name( 'ad_sense' ); ?>"><?php echo esc_attr( $ad_sense ); ?></textarea>
			<em><?php _e('Leave this blank if you want to use the Image and Link instead.', self::$text_domain); ?></em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image URL:', self::$text_domain); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_url( $image ); ?>"><br/>
			<em><?php _e('URL to the image you want to display', self::$text_domain); ?></em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:', self::$text_domain); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_url( $link ); ?>"><br/>
			<em><?php _e('Where you want the image to link', self::$text_domain); ?></em>
		</p>

		<p>
			<span><?php _e("This widget's css id is:", self::$text_domain); ?></span> <strong><?php echo $this->id; ?></strong>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['ad_sense'] = ( ! empty( $new_instance['ad_sense'] ) ) ? sanitize_text_field( $new_instance['ad_sense'] ) : '';
		$instance['image'] = ( ! empty( $new_instance['image'] ) ) ? sanitize_text_field( $new_instance['image'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? sanitize_text_field( $new_instance['link'] ) : '';

		return $instance;
	}

	/**
	 * Load the text domain
	 *
	 * @since 1.0.0
	 */
	static function load_textdomain() {
		load_plugin_textdomain(self::$text_domain, false, WPSITE_SIMPLE_AD_SPOT_PLUGIN_DIR . '/languages');
	}

	/**
	 * Hooks to 'register_activation_hook'
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		/* Check if multisite, if so then save as site option */

		if (function_exists('is_multisite') && is_multisite()) {
			add_site_option(self::$prefix . 'version', WPSITE_SIMPLE_AD_SPOT_VERSION_NUM);
		} else {
			add_option(self::$prefix . 'version', WPSITE_SIMPLE_AD_SPOT_VERSION_NUM);
		}
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function plugin_links($links) {
		$settings_link = '<a href="widgets.php">Widget</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Hooks to 'admin_print_scripts-$page'
	 *
	 * @since 1.0.0
	 */
	static function include_admin_scripts() {

		/* CSS */

		wp_register_style(self::$prefix . 'style_css', WPSITE_SIMPLE_AD_SPOT_PLUGIN_URL . '/css/style.css');
		wp_enqueue_style(self::$prefix . 'style_css');
	}
}

?>