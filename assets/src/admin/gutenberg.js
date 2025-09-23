/**
 * Gutenberg.
 */
/* global wp, lodash */

/**
 * Edit Gutenberg settings.
 * @return {void}
 */
wp.domReady(() => {
  // Remove quote styles.
  wp.blocks.unregisterBlockStyle('core/quote', 'default');
  wp.blocks.unregisterBlockStyle('core/quote', 'large');
  wp.blocks.unregisterBlockStyle('core/quote', 'plain');
  // Remove image styles.
  wp.blocks.unregisterBlockStyle('core/image', 'default');
  wp.blocks.unregisterBlockStyle('core/image', 'rounded');

  // Unregister blocks.
  // Define the blocks we support.
  const allowedBlocks = new Set(['core/paragraph', 'core/heading', 'core/list', 'core/list-item', 'core/image', 'core/quote', 'core/gallery', 'core/embed', 'core/video']);
  // Unregister all blocks that are not the ones we support.
  wp.blocks.getBlockTypes().forEach(blockType => {
    if (!allowedBlocks.has(blockType.name)) {
      wp.blocks.unregisterBlockType(blockType.name);
    }
  });

  // Remove embed block variations.
  // Define the variations we support.
  const allowedEmbedBlocks = new Set(['vimeo', 'youtube']);
  // Unregister all embed block variations that are not the ones we support.
  wp.blocks.getBlockVariations('core/embed').forEach(blockVariation => {
    if (!allowedEmbedBlocks.has(blockVariation.name)) {
      wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
    }
  });
});

/**
 * Disable alignment for some blocks
 * @return {array}
 */
wp.hooks.addFilter(
  'blocks.registerBlockType',
  'playground/gutenberg',
  (settings, name) => {
    const blocks = new Set(['core/gallery', 'core/video', 'core/embed']);

    if (blocks.has(name)) {
      return lodash.assign({}, settings, {
        supports: lodash.assign({}, settings.supports, {
          align: false,
        }),
      });
    }

    return settings;
  },
);
