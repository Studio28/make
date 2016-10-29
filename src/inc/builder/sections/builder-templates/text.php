<?php
/**
 * @package Make
 */

ttfmake_load_section_header();

global $ttfmake_section_data, $ttfmake_is_js_template;
$section_id     = ( isset( $ttfmake_section_data['data']['id'] ) ) ? $ttfmake_section_data['data']['id'] : '{{ get("id") }}';
$section_name   = 'ttfmake-section[{{ get("id") }}]';
$columns_number = '{{ get("columns-number") }}';
$section_order  = ( ! empty( $ttfmake_section_data['data']['columns-order'] ) ) ? $ttfmake_section_data['data']['columns-order'] : range(1, 4);
$columns_class  = '{{ get("columns-number") }}';

/**
 * Execute code before the columns select input is displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_before_columns_select', $ttfmake_section_data );

/**
 * Execute code after the columns select input is displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_after_columns_select', $ttfmake_section_data );

/**
 * Execute code after the section title is displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_after_title', $ttfmake_section_data ); ?>
<div class="ttfmake-text-columns-stage ttfmake-text-columns-<?php echo $columns_class; ?>">
	<?php $j = 1; foreach ( $section_order as $key => $i ) : ?>
	<?php
		$column_name = $section_name . '[columns][' . $i . ']';
		$iframe_id   = 'ttfmake-iframe-' . $section_id . '-' . $i;
		$textarea_id = 'ttfmake-content-' . $section_id . '-' . $i;
		$overlay_id  = 'ttfmake-overlay-' . $section_id . '-' . $i;
		$link        = '{{ get("columns")['. $i .']["image-link"]';
		$image_id    = '{{ get("columns")['. $i .']["image-id"]';
		$title       = '{{ get("columns")['. $i .']["title"] }}';
		$content     = '{{ get("columns")['. $i .']["content"] }}';

		//$item_has_content = ( ! empty( $content ) ) ? ' item-has-content' : '';

		$column_buttons = array(
			100 => array(
				'label'              => __( 'Configure column', 'make' ),
				'href'               => '#',
				'class'              => 'configure-column-link ttfmake-overlay-open',
				'title'              => __( 'Configure column', 'make' ),
				'other-a-attributes' => ' data-overlay="#' . $overlay_id .'"',
			),
			200 => array(
				'label'              => __( 'Edit text column', 'make' ),
				'href'               => '#',
				'class'              => 'edit-content-link edit-text-column-link' . $item_has_content,
				'title'              => __( 'Edit content', 'make' ),
				'other-a-attributes' => 'data-textarea="' . esc_attr( $textarea_id ) . '" data-iframe="' . esc_attr( $iframe_id ) . '"',
			),
		);

		/**
		 * Filter the buttons added to a text column.
		 *
		 * @since 1.4.0.
		 *
		 * @param array    $column_buttons          The current list of buttons.
		 * @param array    $ttfmake_section_data    All data for the section.
		 */
		$column_buttons = apply_filters( 'make_column_buttons', $column_buttons, $ttfmake_section_data );
		ksort( $column_buttons );

		/**
		 * Filter the classes applied to each column in a Columns section.
		 *
		 * @since 1.2.0.
		 *
		 * @param string    $column_classes          The classes for the column.
		 * @param int       $i                       The column number.
		 * @param array     $ttfmake_section_data    The array of data for the section.
		 */
		$column_classes = apply_filters( 'ttfmake-text-column-classes', 'ttfmake-text-column ttfmake-text-column-position-' . $j, $i, $ttfmake_section_data );
	?>
	<div class="<?php echo esc_attr( $column_classes ); ?>" data-id="<?php echo $i; ?>">
		<div title="<?php esc_attr_e( 'Drag-and-drop this column into place', 'make' ); ?>" class="ttfmake-sortable-handle">
			<div class="sortable-background column-sortable-background"></div>
		</div>

		<?php
		/**
		 * Execute code before an individual text column is displayed.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $ttfmake_section_data    The data for the section.
		 */
		do_action( 'make_section_text_before_column', $ttfmake_section_data, $i );
		?>

		<?php foreach ( $column_buttons as $button ) : ?>
		<a href="<?php echo esc_url( $button['href'] ); ?>" class="column-buttons <?php echo esc_attr( $button['class'] ); ?>" title="<?php echo esc_attr( $button['title'] ); ?>" <?php if ( ! empty( $button['other-a-attributes'] ) ) echo $button['other-a-attributes']; ?>>
			<span>
				<?php echo esc_html( $button['label'] ); ?>
			</span>
		</a>
		<?php endforeach; ?>

		<?php echo ttfmake_get_builder_base()->add_uploader( $column_name, ttfmake_sanitize_image_id( $image_id ), __( 'Set image', 'make' ) ); ?>
		<?php ttfmake_get_builder_base()->add_frame( $section_id . '-' . $i, $column_name . '[content]', $content ); ?>

		<?php
		/**
		 * Execute code after an individual text column is displayed.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $ttfmake_section_data    The data for the section.
		 */
		do_action( 'make_section_text_after_column', $ttfmake_section_data, $i );
		?>

		<?php
		global $ttfmake_overlay_class, $ttfmake_overlay_id, $ttfmake_overlay_title;
		$ttfmake_overlay_class = 'ttfmake-configuration-overlay';
		$ttfmake_overlay_id    = $overlay_id;
		$ttfmake_overlay_title = __( 'Configure column', 'make' );

		get_template_part( '/inc/builder/core/templates/overlay', 'header' );

		/**
		 * Filter the definitions of the Columns section's column configuration inputs.
		 *
		 * @since 1.4.0.
		 *
		 * @param array    $inputs    The input definition array.
		 */
		$inputs = apply_filters( 'make_column_configuration', array(
			100 => array(
				'type'    => 'section_title',
				'name'    => 'title',
				'label'   => __( 'Enter column title', 'make' ),
				'default' => '{{ get("columns")['. $i .']["title"] }}',
				'class'   => 'ttfmake-configuration-title',
			),
			200 => array(
				'type'    => 'text',
				'name'    => 'image-link',
				'label'   => __( 'Image link URL', 'make' ),
				'default' => '{{ get("columns")['. $i .']["image-link"] }}',
			),
		) );

		// Sort the config in case 3rd party code added another input
		ksort( $inputs, SORT_NUMERIC );

		// Print the inputs
		$output = '';

		foreach ( $inputs as $input ) {
			if ( isset( $input['type'] ) && isset( $input['name'] ) ) {
				$section_data  = ( isset( $ttfmake_section_data['data']['columns'][ $i ] ) ) ? $ttfmake_section_data['data']['columns'][ $i ] : array();
				$output       .= ttfmake_create_input( $column_name, $input, $section_data );
			}
		}

		echo $output;

		get_template_part( '/inc/builder/core/templates/overlay', 'footer' );
		?>
	</div>
	<?php $j++; endforeach; ?>
</div>
<?php
/**
 * Execute code after all columns are displayed.
 *
 * @since 1.2.3.
 *
 * @param array    $ttfmake_section_data    The data for the section.
 */
do_action( 'make_section_text_after_columns', $ttfmake_section_data );
?>

<div class="clear"></div>
<input type="hidden" value="{{ get('columns-order') }}" name="<?php echo $section_name; ?>[columns-order]" class="ttfmake-text-columns-order" />
<input type="hidden" class="ttfmake-section-state" name="<?php echo $section_name; ?>[state]" value="<?php if ( isset( $ttfmake_section_data['data']['state'] ) ) echo esc_attr( $ttfmake_section_data['data']['state'] ); else echo '{{ get("state") }}'; ?>" />
<?php ttfmake_load_section_footer();
