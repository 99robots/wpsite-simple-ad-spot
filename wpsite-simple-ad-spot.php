<?php
/**
 * Plugin Name: Simple Ad Spot
 * Plugin URI: https://www.99robots.com
 * Description: The 99 Robots Simple Ad Spot plugin is the easiest way to place an advertisement in your WordPress site.
 * Version: 1.1.1
 * Author: 99 Robots
 * Author URI: https://www.99robots.com
 * License: GPL2
 */

/**
 * Hooks / Filter
 */
add_action( 'widgets_init', function() {
	register_widget( 'WPsite_Simple_Ad_Spot' );
} );

$plugin = plugin_basename( __FILE__ );
add_action( 'init', array( 'WPsite_Simple_Ad_Spot', 'load_textdomain' ) );
add_filter( "plugin_action_links_$plugin", array( 'WPsite_Simple_Ad_Spot', 'plugin_links' ) );

/**
 *  WPsite_Simple_Ad_Spot main class
 *
 * @since 1.0.0
 * @using Wordpress 3.8
 */
class WPsite_Simple_Ad_Spot extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'wpsite-simple-ad-spot',
			esc_html__( '99 Robots Simple Ad Spot', 'wpsite_simple_ad_spot' ),
			array( 'description' => esc_html__( 'Display an Ad', 'wpsite_simple_ad_spot' ) )
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

		if ( isset( $instance['ad_sense'] ) && '' !== $instance['ad_sense'] ) {
			echo $instance['ad_sense'];
		} elseif ( isset( $instance['image'] ) && isset( $instance['link'] ) && '' !== $instance['image'] && '' !== $instance['link'] ) {
			echo '<div class="wps-sas-container" style="max-width:100%;overflow:hidden;"><a class="wps-sas-link" href="' . $instance['link'] . '" target="_blank"><img class="wps-sas-image" src="' . $instance['image'] . '"/></a></div>';
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

		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Simple Ad Spot', 'wpsite_simple_ad_spot' );
		$ad_sense = isset( $instance['ad_sense'] ) ? $instance['ad_sense'] : '';
		$image = isset( $instance['image'] ) ? $instance['image'] : '';
		$link = isset( $instance['link'] ) ? $instance['link'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'wpsite_simple_ad_spot' ) ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ad_sense' ); ?>"><?php esc_html_e( 'Ad Sense Code:', 'wpsite_simple_ad_spot' ) ?></label>
			<textarea cols="46" rows="10" id="<?php echo $this->get_field_id( 'ad_sense' ); ?>" name="<?php echo $this->get_field_name( 'ad_sense' ); ?>"><?php echo esc_attr( $ad_sense ); ?></textarea>
			<em><?php esc_html_e( 'Leave this blank if you want to use the Image and Link instead.', 'wpsite_simple_ad_spot' ) ?></em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php esc_html_e( 'Image URL:', 'wpsite_simple_ad_spot' ) ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_url( $image ); ?>" placeholder="http://example.com/image.png"><br/>
			<em><?php esc_html_e( 'URL to the image you want to display', 'wpsite_simple_ad_spot' ) ?></em>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php esc_html_e( 'Destination URL:', 'wpsite_simple_ad_spot' ) ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_url( $link ); ?>" placeholder="http://example.com"><br/>
			<em><?php esc_html_e( 'Where you want the image to link', 'wpsite_simple_ad_spot' ) ?></em>
		</p>

		<p>
			<span><?php esc_html_e( 'This widget\'s css id is:', 'wpsite_simple_ad_spot' ) ?></span> <strong><?php echo $this->id; ?></strong>
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
		$instance['ad_sense'] = ( ! empty( $new_instance['ad_sense'] ) ) ? $new_instance['ad_sense'] : '';
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

		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpsite_simple_ad_spot' );

		load_textdomain(
			'wpsite_simple_ad_spot',
			WP_LANG_DIR . '/wpsite-simple-ad-spot/wpsite-simple-ad-spot-' . $locale . '.mo'
		);

		load_plugin_textdomain(
			'wpsite_simple_ad_spot',
			false,
			untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/languages/'
		);
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function plugin_links( $links ) {

		$settings_link = '<a href="widgets.php">Widget</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}
