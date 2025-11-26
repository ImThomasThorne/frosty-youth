<?php
/**
 * Title: Linkable Resource
 * Slug: frost-youth/linkable-resource
 * Categories:  cya-blocks
 */
?>

<!-- wp:group {"metadata":{"name":"Linkable Resource","categories":["cya-blocks"],"patternName":"coblocks_pattern/linkable-resource"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|10"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"dimensions":{"minHeight":"20vh"},"border":{"radius":"8px"}},"backgroundColor":"secondary","textColor":"base","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
<div class="wp-block-group has-base-color has-secondary-background-color has-text-color has-background has-link-color" style="border-radius:8px;min-height:20vh;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:heading {"level":3,"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"lineHeight":1.6}},"textColor":"base"} -->
<h3 class="wp-block-heading has-base-color has-text-color has-link-color" style="line-height:1.6">Linkable Resource Title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Short Resource Description</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"base","textColor":"secondary","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"fontSize":"x-small"} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-secondary-color has-base-background-color has-text-color has-background has-link-color has-x-small-font-size has-custom-font-size wp-element-button">Link</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->