<?php

/*
Plugin Name: Idno Feed for wordpress
Plugin URI: https://github.com/mapkyca/wordpress-idno-feedwidget
Description: Adds a widget for displaying the latest content from an idno feed.
Version: 1.0
Author: Marcus Povey
Author URI: http://www.marcus-povey.co.uk
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('IDNOFEEDWIDGET_VERSION', '1.0');

class IdnoFeedWidget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'idno_feed_widget', // Base ID
			__('Idno Feed Widget', 'text_domain'), // Name
			array( 'description' => __( 'List the latest content from your idno site.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
            
                $title = apply_filters( 'widget_title', $instance['title'] );
                                
                $domain = trim($instance['domain'], '/ ');
                $domain = str_replace('http:', '', $domain);
                $domain = str_replace('https:', '', $domain);
                
                $count = (int)$instance['count']; if (!$count) $count = 5;

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
            
                ?>
<div class="idno-wordpress-feed">
    <ul id="<?php echo $args['widget_id']; ?>-content" class="idno-wordpress-feed-content"></ul>
    <script>
        var widget_id = '<?= $args['widget_id']; ?>';
        var count = '<?= $instance['count']; ?>';
        
        var script = document.createElement('script');
        script.src = '<?php echo $domain; ?>/?callback=idno_feedwidget&_t=jsonp';
        document.getElementsByTagName('head')[0].appendChild(script);  
    </script>
</div>
<?php
                echo $args['after_widget'];
            
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
            
            // Link url
            if ( isset( $instance[ 'domain' ] ) ) {
                $domain = $instance[ 'domain' ];
            }
            else {
                $domain = "";
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'domain' ); ?>"><?php _e( 'Idno site:' ); ?></label> 
            <input placeholder="<?= __( 'Idno site url', 'text_domain' );?>" class="widefat" id="<?php echo $this->get_field_id( 'domain' ); ?>" name="<?php echo $this->get_field_name( 'domain' ); ?>" required type="url" value="<?php echo esc_attr( $domain ); ?>">
            </p>
            <?php 

            
            if ( isset( $instance[ 'title' ] ) ) {
                    $title = $instance[ 'title' ];
            }
            else {
                    $title = __( 'Latest...', 'text_domain' );
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input placeholder="<?= __('Title', 'text_domain'); ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <?php 
            
            
            if ( isset( $instance[ 'count' ] ) ) {
                    $count = $instance[ 'count' ];
            }
            else {
                    $count = 5;
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of articles:' ); ?></label> 
            <select  id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>">
                <option value="5" <?php if($count == 5) echo 'selected' ?>>5</option>
                <option value="10" <?php if($count == 10) echo 'selected' ?>>10</option>
                </select>
            </p>
            <?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
                $instance['domain'] = ( ! empty( $new_instance['domain'] ) ) ? strip_tags( $new_instance['domain'] ) : '';
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
                $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? (int) $new_instance['count']  : 5;

		return $instance;
	}
}

// Listen for init and header requests
add_action('init', function() {
    // Add a topbar (via javascript)
    add_action('wp_footer', function() {
        
        $url = trim(get_bloginfo('wpurl'), ' /');
        $url = str_replace('http:', '', $url);
        $url = str_replace('https:', '', $url);
        
        ?>
        <script src="<?= $url; ?>/wp-content/plugins/wordpress-idno-feedwidget/idno-feedwidget.js" />
        <?php
    });

});

// Add feature widget (for some reason this doesn't work in minds_wp_plugin_init)
add_action('widgets_init', function() {
    register_widget('IdnoFeedWidget');
});
