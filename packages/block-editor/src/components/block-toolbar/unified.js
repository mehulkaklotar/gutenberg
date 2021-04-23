/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { Popover } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { store as blockEditorStore } from '../../store';
import BlockContextualToolbar from '../block-list/block-contextual-toolbar';

export default function UnifiedBlockToolbar() {
	const hasFixedToolbar = useSelect(
		( select ) => select( blockEditorStore ).getSettings().hasFixedToolbar,
		[]
	);

	if ( hasFixedToolbar ) {
		return (
			<>
				<Popover.Slot name="block-toolbar" />
				<BlockContextualToolbar className="is-fixed" />
			</>
		);
	}

	return <Popover.Slot name="block-toolbar" />;
}
