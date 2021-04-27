/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { Popover } from '@wordpress/components';
import { useViewportMatch } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { store as blockEditorStore } from '../../store';
import BlockContextualToolbar from '../block-list/block-contextual-toolbar';

export default function UnifiedBlockToolbar() {
	const isLargeViewport = useViewportMatch( 'medium' );
	const hasFixedToolbar = useSelect(
		( select ) => select( blockEditorStore ).getSettings().hasFixedToolbar,
		[]
	);

	if ( hasFixedToolbar || ! isLargeViewport ) {
		return (
			<>
				<Popover.Slot name="block-toolbar" />
				<BlockContextualToolbar isFixed />
			</>
		);
	}

	return <Popover.Slot name="block-toolbar" />;
}
