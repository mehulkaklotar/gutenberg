/**
 * WordPress dependencies
 */
import { BlockControls } from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';

/**
 * Internal dependencies
 */
import { getWidgetIdFromBlock } from '../utils';

/**
 * Internal dependencies
 */
import MoveToSidebar from '../components/move-to-sidebar';

const withMoveToSidebarToolbarItem = createHigherOrderComponent(
	( BlockEdit ) => ( props ) => {
		const widgetId = getWidgetIdFromBlock( props );

		return (
			<>
				<BlockEdit { ...props } />
				<BlockControls>
					<MoveToSidebar widgetId={ widgetId } />
				</BlockControls>
			</>
		);
	},
	'withMoveToSidebarToolbarItem'
);

addFilter(
	'editor.BlockEdit',
	'core/customize-widgets/block-edit',
	withMoveToSidebarToolbarItem
);
