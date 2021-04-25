<?php

namespace Inc\Api\Widgets;

use WP_Widget;

class IkWidget extends WP_Widget {

	/**
	 * @var string
	 */
	public $widget_ID;

	/**
	 * @var string
	 */
	public $widget_name;

	/**
	 * @var array
	 */
	public $widget_options = array();

	/**
	 * @var array
	 */
	public $control_options = array();

	/**
	 * IkWidget constructor.
	 */
	public function __construct() {

		$this->widget_ID = 'ik_widget_plugin';

		$this->widget_name = 'IK Widget Plugin';

		$this->widget_options = array(
			'classname'                   => $this->widget_ID,
			'description'                 => $this->widget_name,
			'customize_selective_refresh' => true
		);

		// Enqueue style if widget is active (appears in a sidebar) or if in Customizer preview.
		if ( is_active_widget( false, false, $this->widget_ID ) || is_customize_preview() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		$this->control_options = array(
			'width'  => 400,
			'height' => 350
		);

		parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );

	}

	/**
	 * Enqueue style if widget is active
	 */
    public function enqueue_scripts() {
		wp_enqueue_style( 'ik-widget-plugin-style', plugins_url( '/css/ik-widget.css', __FILE__ ), array(), '0.1' );
	}

	/**
	 * register_widget
	 */
	public function widgetInit() {
		register_widget( $this );
	}

	/**
     * Widget
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$this->print_list_users( $instance['sort_by'], $instance['show_quantity_comments'], $instance['quantity_of_users'], $instance['show_users_without_comments'], $instance['select_by_user_role'] );

		echo $args['after_widget'];
	}

	/**
     * Form
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title                       = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Custom text', 'ik-widget-plugin' );
		$sort_by                     = ! empty( $instance['sort_by'] ) ? esc_attr( $instance['sort_by'] ) : '';
		$show_quantity_comments      = ! empty( $instance['show_quantity_comments'] ) ? $instance['show_quantity_comments'] : '';
		$quantity_of_users           = ! empty( $instance['quantity_of_users'] ) ? $instance['quantity_of_users'] : '';
		$show_users_without_comments = ! empty( $instance['show_users_without_comments'] ) ? $instance['show_users_without_comments'] : '';
		$select_by_user_role         = ! empty( $instance['select_by_user_role'] ) ? $instance['select_by_user_role'] : '';

		$titleID = $this->get_field_id( $title );
		?>
        <p>
            <label for="<?php echo $titleID; ?>">Title:</label>
            <input type="text" class="widefat" id="<?php echo $titleID; ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>

            <label for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>">Choose sorting by:</label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'sort_by' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>">
                <option value="ASC" <?php echo $sort_by === "ASC" ? 'selected="selected"' : ''; ?> >ASC</option>
                <option value="DESC" <?php echo $sort_by === "DESC" ? 'selected="selected"' : ''; ?> >DESC</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_quantity_comments' ); ?>">Show Quantity Comments:</label>
            <input class="сheckbox" type="checkbox" id="<?php echo $this->get_field_id( 'show_quantity_comments' ); ?>"
                   name="<?php echo $this->get_field_name( 'show_quantity_comments' ); ?>" <?php checked( $show_quantity_comments, 'on' ); ?> >
        </p>
        <p>
            <label for="quantity_of_users">Display quantity of users:</label>
            <input type="number" class="widefat" id="<?php echo $titleID; ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'quantity_of_users' ) ); ?>"
                   value="<?php echo esc_attr( $quantity_of_users ); ?>" min="1">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_users_without_comments' ); ?>">Show Users Without Comments:</label>
            <input class="сheckbox" type="checkbox"
                   id="<?php echo $this->get_field_id( 'show_users_without_comments' ); ?>"
                   name="<?php echo $this->get_field_name( 'show_users_without_comments' ); ?>" <?php checked( $show_users_without_comments, 'on' ); ?> >
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'select_by_user_role' ) ); ?>">Select Users by Role:
            </label>
            <br>
            <select multiple="multiple" size="5"
                    name="<?php echo esc_attr( $this->get_field_name( 'select_by_user_role' ) ); ?>[]"
                    id="<?php echo esc_attr( $this->get_field_id( 'select_by_user_role' ) ); ?>">
				<?php $user_roles = $this->get_user_roles();
				foreach ( $user_roles as $role ) : ?>
                    <option value="<?php echo $role; ?>" <?php echo in_array( $role, (array) $select_by_user_role ) ? ' selected="selected"' : ''; ?> ><?php echo $role; ?></option>
				<?php endforeach; ?>
            </select>
        </p>
		<?php
	}

	/**
     * Update
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']                       = sanitize_text_field( $new_instance['title'] );
		$instance['sort_by']                     = esc_sql( $new_instance['sort_by'] );
		$instance['show_quantity_comments']      = $new_instance['show_quantity_comments'];
		$instance['quantity_of_users']           = $new_instance['quantity_of_users'];
		$instance['show_users_without_comments'] = $new_instance['show_users_without_comments'];
		$instance['select_by_user_role']         = esc_sql( $new_instance['select_by_user_role'] );

		return $instance;
	}


	/**
     * Print_list_users
	 * @param $sort_by
	 * @param $show_quantity_comments
	 * @param $quantity_of_users
	 * @param $show_users_without_comments
	 * @param $select_by_user_role
	 */
	public function print_list_users( $sort_by, $show_quantity_comments, $quantity_of_users, $show_users_without_comments, $select_by_user_role ) {

		$users = get_users( array(
			'orderby'  => 'user_registered',
			'number'   => $quantity_of_users,
			'role__in' => $select_by_user_role
		) );

		$array_users = array();
		foreach ( $users as $user ) {
			$user_id           = $user->data->ID;
			$user_name         = $user->data->user_nicename;
			$quantity_comments = $this->get_quantity_comments_by_user_id( $user_id );

			$array_users[ $user_name ] = ( $quantity_comments === 'no comments' ) ? 0 : $quantity_comments;
		}

		if ( $sort_by === 'ASC' ) {
			asort( $array_users );
		}
		if ( $sort_by === 'DESC' ) {
			arsort( $array_users );
		}

		echo '<style>.user_list{background-color: #' . get_theme_mod( 'background_color', 'D1E4DD' ) . '; }</style><ul class="user_list">';

		foreach ( $array_users as $user_name => $quantity_comments ) {
			if ( $show_users_without_comments !== 'on' && $quantity_comments == 0 ) {
				continue;
			} else {
				echo $show_quantity_comments === 'on' ? '<li><span class="user-name">' . $user_name . '</span> <span class="quantity-comments">(' . $quantity_comments . ')</span></li>' : '<li><span class="user-name">' . $user_name . '</span> <span class="quantity-comments"></span></li>';
			}
		}

		echo '</ul>';
	}


	/**
     * Get_quantity_comments_by_user_id
	 * @param $user_id
	 *
	 * @return int|string
	 */
	public function get_quantity_comments_by_user_id( $user_id ) {

		$args = array(
			'user_id' => $user_id,
		);

		if ( $comments = get_comments( $args ) ) {
			$count_comments = 0;
			foreach ( $comments as $comment ) {
				$count_comments ++;
			}
			return $count_comments;
		} else {
			return 'no comments';
		}
	}

	/**
     * Get_user_roles
	 * @return array
	 */
	public function get_user_roles() {

		global $wp_roles;
		$all_roles   = $wp_roles->roles;
		$array_roles = array();

		foreach ( $all_roles as $role ) {
			$array_roles[] = $role['name'];
		}

		return $array_roles;
	}
}