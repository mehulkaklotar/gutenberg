<?php
/**
 * REST API: WP_REST_Block_Editor_Settings_Controller class
 *
 * @package    WordPress
 * @subpackage REST_API
 */

/**
 * Core class used to retrieve the block editor settings via the REST API.
 *
 * @see WP_REST_Controller
 */
class WP_REST_Block_Editor_Settings_Controller extends WP_REST_Controller {
	/**
	 * Constructs the controller.
	 */
	public function __construct() {
		$this->namespace = '__experimental/wp-block-editor/v1';
		$this->rest_base = 'settings';
	}

	/**
	 * Registers the necessary REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Checks whether a given request has permission to read block editor settings
	 *
	 * @since 5.8.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|bool True if the request has permission, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		if ( current_user_can( 'edit_posts' ) ) {
			return true;
		}

		foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $post_type ) {
			if ( current_user_can( $post_type->cap->edit_posts ) ) {
				return true;
			}
		}

		return new WP_Error(
			'rest_cannot_read_block_editor_settings',
			__( 'Sorry, you are not allowed to read the block editor settings.', 'gutenberg' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	/**
	 * Returns the block editor's settings
	 *
	 * @since 5.8.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$context  = ! empty( $request['context'] ) ? $request['context'] : 'post-editor';
		$settings = array_merge(
			apply_filters( 'block_editor_settings', array() ),
			gutenberg_get_block_editor_settings( $context )
		);

		return rest_ensure_response( $settings );
	}

	/**
	 * Retrieves the block editor's settings schema, conforming to JSON Schema.
	 *
	 * @since 5.8.0
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'block-editor-settings-item',
			'type'       => 'object',
			'properties' => array(
				'__unstableEnableFullSiteEditingBlocks'  => array(
					'description' => __( 'Enables Full site editing blocks', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'styles'                                 => array(
					'description' => __( 'Editor styles', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'supportsTemplateMode'                   => array(
					'description' => __( 'Returns if the current theme is full site editing-enabled or not.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'supportsLayout'                         => array(
					'description' => __( 'Enable/disable layouts support in container blocks.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'widgetTypesToHideFromLegacyWidgetBlock' => array(
					'description' => __( 'Widget types to hide from legacy widget block.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'__experimentalFeatures'                 => array(
					'description' => __( 'Active theme settings and default values.', 'gutenberg' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
				),

				'__experimentalGlobalStylesUserEntityId' => array(
					'description' => __( 'Global styles user entity ID.', 'gutenberg' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
				),

				'__experimentalGlobalStylesBaseStyles'   => array(
					'description' => __( 'Global styles settings.', 'gutenberg' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
				),

				'alignWide'                              => array(
					'description' => __( 'Enable/Disable Wide/Full Alignments.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'allowedBlockTypes'                      => array(
					'description' => __( 'Enable/Disable Wide/Full Alignments.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'allowedMimeTypes'                       => array(
					'description' => __( 'List of allowed mime types and file extensions.', 'gutenberg' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
				),

				'blockCategories'                        => array(
					'description' => __( 'Returns all the categories for block types that will be shown in the block editor.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'disableCustomColors'                    => array(
					'description' => __( 'Disables custom colors.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'disableCustomFontSizes'                 => array(
					'description' => __( 'Disables custom font size.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'disableCustomGradients'                 => array(
					'description' => __( 'Disables custom font size.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'enableCustomLineHeight'                 => array(
					'description' => __( 'Enables custom line height.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'enableCustomSpacing'                    => array(
					'description' => __( 'Enables custom spacing.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'enableCustomUnits'                      => array(
					'description' => __( 'Enables custom units.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'isRTL'                                  => array(
					'description' => __( 'Determines whether the current locale is right-to-left (RTL).', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'imageDefaultSize'                       => array(
					'description' => __( 'Image default size slug.', 'gutenberg' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),

				'imageDimensions'                        => array(
					'description' => __( 'Available image dimensions.', 'gutenberg' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
				),

				'imageEditing'                           => array(
					'description' => __( 'Image Editing settings set to false to disable.', 'gutenberg' ),
					'type'        => 'boolean',
					'context'     => array( 'view' ),
				),

				'imageSizes'                             => array(
					'description' => __( 'Available image sizes.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'maxUploadFileSize'                      => array(
					'description' => __( 'Maximum upload size in bytes allowed for the site.', 'gutenberg' ),
					'type'        => 'number',
					'context'     => array( 'view' ),
				),

				'colors'                                 => array(
					'description' => __( 'Active theme color palette.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'fontSizes'                              => array(
					'description' => __( 'Active theme font sizes.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),

				'gradients'                              => array(
					'description' => __( 'Active theme gradients.', 'gutenberg' ),
					'type'        => 'array',
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $this->schema );
	}
}
