/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { hasBlockSupport, store as blocksStore } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import NavigableToolbar from '../navigable-toolbar';
import BlockToolbar from '../block-toolbar';
import { store as blockEditorStore } from '../../store';

function BlockContextualToolbar( { focusOnMount, className, ...props } ) {
	const { blockType, hasSelection, hasParents } = useSelect( ( select ) => {
		const {
			getBlockName,
			getBlockParents,
			getSelectedBlockClientIds,
		} = select( blockEditorStore );
		const { getBlockType } = select( blocksStore );
		const selectedBlockClientIds = getSelectedBlockClientIds();
		const selectedBlockClientId = selectedBlockClientIds[ 0 ];
		return {
			blockType:
				selectedBlockClientId &&
				getBlockType( getBlockName( selectedBlockClientId ) ),
			hasSelection: selectedBlockClientIds.length,
			hasParents: getBlockParents( selectedBlockClientId ).length,
		};
	}, [] );

	if ( ! hasSelection ) {
		return null;
	}

	if ( blockType ) {
		if ( ! hasBlockSupport( blockType, '__experimentalToolbar', true ) ) {
			return null;
		}
	}

	// Shifts the toolbar to make room for the parent block selector.
	const classes = classnames(
		'block-editor-block-contextual-toolbar',
		className,
		{
			'has-parent': hasParents,
		}
	);

	return (
		<NavigableToolbar
			focusOnMount={ focusOnMount }
			className={ classes }
			/* translators: accessibility text for the block toolbar */
			aria-label={ __( 'Block tools' ) }
			{ ...props }
		>
			<BlockToolbar />
		</NavigableToolbar>
	);
}

export default BlockContextualToolbar;
