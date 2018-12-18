<?php
/**
 * Astra Addon - Gutenberg Editor CSS
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Addon_Gutenberg_Editor_CSS' ) ) {

	/**
	 * Addon_Gutenberg_Editor_CSS initial setup
	 *
	 * @since 1.6.2
	 */
	class Addon_Gutenberg_Editor_CSS {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			if ( Astra_Ext_Extension::is_active( 'typography' ) ) {
				add_filter( 'astra_block_editor_dynamic_css', array( $this, 'typography_addon_gutenberg_dynamic_css' ) );
			}
			if ( Astra_Ext_Extension::is_active( 'colors-and-background' ) ) {
				add_filter( 'astra_block_editor_dynamic_css', array( $this, 'colors_and_background_addon_gutenberg_dynamic_css' ) );
			}
			if ( Astra_Ext_Extension::is_active( 'spacing' ) ) {
				add_filter( 'astra_block_editor_dynamic_css', array( $this, 'spacing_addon_gutenberg_dynamic_css' ) );
			}
		}

		/**
		 * Dynamic CSS - Typography
		 *
		 * @since  1.6.2
		 * @param  string $dynamic_css          Astra Gutenberg Dynamic CSS.
		 * @param  string $dynamic_css_filtered Astra Gutenberg Dynamic CSS Filters.
		 * @return string
		 */
		public function typography_addon_gutenberg_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {
			$h1_font_family    = astra_get_option( 'font-family-h1' );
			$h1_font_weight    = astra_get_option( 'font-weight-h1' );
			$h1_line_height    = astra_get_option( 'line-height-h1' );
			$h1_text_transform = astra_get_option( 'text-transform-h1' );

			$h2_font_family    = astra_get_option( 'font-family-h2' );
			$h2_font_weight    = astra_get_option( 'font-weight-h2' );
			$h2_line_height    = astra_get_option( 'line-height-h2' );
			$h2_text_transform = astra_get_option( 'text-transform-h2' );

			$h3_font_family    = astra_get_option( 'font-family-h3' );
			$h3_font_weight    = astra_get_option( 'font-weight-h3' );
			$h3_line_height    = astra_get_option( 'line-height-h3' );
			$h3_text_transform = astra_get_option( 'text-transform-h3' );

			$h4_font_family    = astra_get_option( 'font-family-h4' );
			$h4_font_weight    = astra_get_option( 'font-weight-h4' );
			$h4_line_height    = astra_get_option( 'line-height-h4' );
			$h4_text_transform = astra_get_option( 'text-transform-h4' );

			$h5_font_family    = astra_get_option( 'font-family-h5' );
			$h5_font_weight    = astra_get_option( 'font-weight-h5' );
			$h5_line_height    = astra_get_option( 'line-height-h5' );
			$h5_text_transform = astra_get_option( 'text-transform-h5' );

			$h6_font_family    = astra_get_option( 'font-family-h6' );
			$h6_font_weight    = astra_get_option( 'font-weight-h6' );
			$h6_line_height    = astra_get_option( 'line-height-h6' );
			$h6_text_transform = astra_get_option( 'text-transform-h6' );

			$parse_css = '';
			/**
			 * Typography
			 */
			$typography_css_output = array(
				/**
				 * Heading - <h1>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h1' => array(
					'font-weight'    => astra_get_css_value( $h1_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h1_font_family, 'font' ),
					'line-height'    => esc_attr( $h1_line_height ),
					'text-transform' => esc_attr( $h1_text_transform ),
				),

				/**
				 * Heading - <h2>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h2' => array(
					'font-weight'    => astra_get_css_value( $h2_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h2_font_family, 'font' ),
					'line-height'    => esc_attr( $h2_line_height ),
					'text-transform' => esc_attr( $h2_text_transform ),
				),

				/**
				 * Heading - <h3>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h3' => array(
					'font-weight'    => astra_get_css_value( $h3_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h3_font_family, 'font' ),
					'line-height'    => esc_attr( $h3_line_height ),
					'text-transform' => esc_attr( $h3_text_transform ),
				),

				/**
				 * Heading - <h4>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h4' => array(
					'font-weight'    => astra_get_css_value( $h4_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h4_font_family, 'font' ),
					'line-height'    => esc_attr( $h4_line_height ),
					'text-transform' => esc_attr( $h4_text_transform ),
				),

				/**
				 * Heading - <h5>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h5' => array(
					'font-weight'    => astra_get_css_value( $h5_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h5_font_family, 'font' ),
					'line-height'    => esc_attr( $h5_line_height ),
					'text-transform' => esc_attr( $h5_text_transform ),
				),

				/**
				 * Heading - <h6>
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h6' => array(
					'font-weight'    => astra_get_css_value( $h6_font_weight, 'font' ),
					'font-family'    => astra_get_css_value( $h6_font_family, 'font' ),
					'line-height'    => esc_attr( $h6_line_height ),
					'text-transform' => esc_attr( $h6_text_transform ),
				),
			);
			$parse_css .= astra_parse_css( $typography_css_output );

			return $dynamic_css .= $parse_css;
		}

		/**
		 * Dynamic CSS - Colors and Background
		 *
		 * @since  1.6.2
		 * @param  string $dynamic_css          Astra Gutenberg Dynamic CSS.
		 * @param  string $dynamic_css_filtered Astra Gutenberg Dynamic CSS Filters.
		 * @return string
		 */
		public function colors_and_background_addon_gutenberg_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

			$h1_color = astra_get_option( 'h1-color' );
			$h2_color = astra_get_option( 'h2-color' );
			$h3_color = astra_get_option( 'h3-color' );
			$h4_color = astra_get_option( 'h4-color' );
			$h5_color = astra_get_option( 'h5-color' );
			$h6_color = astra_get_option( 'h6-color' );

			$parse_css = '';
			/**
			 * Colors and Background
			 */
			$colors_and_background_output = array(
				/**
				 * Content <h1> to <h6> headings
				 */
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h1'                      => array(
					'color' => esc_attr( $h1_color ),
				),
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h2'                      => array(
					'color' => esc_attr( $h2_color ),
				),
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h3'                      => array(
					'color' => esc_attr( $h3_color ),
				),
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h4'                      => array(
					'color' => esc_attr( $h4_color ),
				),
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h5'                      => array(
					'color' => esc_attr( $h5_color ),
				),
				'.gutenberg-editor-page #wpwrap .edit-post-visual-editor h6'                      => array(
					'color' => esc_attr( $h6_color ),
				),
			);
			$parse_css                   .= astra_parse_css( $colors_and_background_output );

			$container_layout = get_post_meta( get_the_id(), 'site-content-layout', true );
			if ( 'default' === $container_layout ) {
				$container_layout = astra_get_option( 'single-' . get_post_type() . '-content-layout' );

				if ( 'default' === $container_layout ) {
					$container_layout = astra_get_option( 'site-content-layout' );
				}
			}

			$boxed_container = array();

			$container_layout = get_post_meta( get_the_id(), 'site-content-layout', true );
			if ( 'default' === $container_layout ) {
				$container_layout = astra_get_option( 'single-' . get_post_type() . '-content-layout' );

				if ( 'default' === $container_layout ) {
					$container_layout = astra_get_option( 'site-content-layout' );
				}
			}

			if ( 'content-boxed-container' === $container_layout || 'boxed-container' === $container_layout ) {
				$content_bg_obj = astra_get_option( 'content-bg-obj' );

				$boxed_container = array(
					'.gutenberg-editor-page #wpwrap .edit-post-visual-editor .editor-writing-flow' => astra_get_background_obj( $content_bg_obj ),

				);
			}

			$parse_css .= astra_parse_css( $boxed_container );

			return $dynamic_css .= $parse_css;

		}

		/**
		 * Dynamic CSS - Spacing Addon
		 *
		 * @since  1.6.2
		 * @param  string $dynamic_css          Astra Gutenberg Dynamic CSS.
		 * @param  string $dynamic_css_filtered Astra Gutenberg Dynamic CSS Filters.
		 * @return string
		 */
		public function spacing_addon_gutenberg_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

			$container_layout = get_post_meta( get_the_id(), 'site-content-layout', true );
			if ( 'default' === $container_layout ) {
				$container_layout = astra_get_option( 'single-' . get_post_type() . '-content-layout' );

				if ( 'default' === $container_layout ) {
					$container_layout = astra_get_option( 'site-content-layout' );
				}
			}

			$boxed_container = array();

			if ( 'content-boxed-container' === $container_layout || 'boxed-container' === $container_layout ) {

				$continder_outside_spacing = astra_get_option( 'container-outside-spacing' );
				$site_content_width        = astra_get_option( 'site-content-width', 1200 );

				$boxed_container = array(

					'.gutenberg-editor-page #wpwrap .editor-block-list__layout, .gutenberg-editor-page #wpwrap .editor-post-title'  => array(
						'padding-top'    => astra_responsive_spacing( $continder_outside_spacing, 'top', 'desktop' ),
						'padding-bottom' => astra_responsive_spacing( $continder_outside_spacing, 'bottom', 'desktop' ),
						'padding-left'   => 'calc( ' . astra_responsive_spacing( $continder_outside_spacing, 'left', 'desktop' ) . ')',
						'padding-right'  => 'calc( ' . astra_responsive_spacing( $continder_outside_spacing, 'right', 'desktop' ) . ')',
					),

					'.gutenberg-editor-page #wpwrap .editor-block-list__layout' => array(
						'padding-top' => '0',
					),

					'.gutenberg-editor-page #wpwrap .editor-post-title' => array(
						'padding-bottom' => '0',
					),

					'.gutenberg-editor-page #wpwrap .edit-post-visual-editor .editor-block-list__block'  => array(
						'max-width' => 'calc(' . astra_get_css_value( $site_content_width, 'px' ) . ' - ' . astra_responsive_spacing( $continder_outside_spacing, 'left', 'desktop' ) . ')',
					),

				);

			}

			$parse_css = astra_parse_css( $boxed_container );

			return $dynamic_css .= $parse_css;
		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Addon_Gutenberg_Editor_CSS::get_instance();
