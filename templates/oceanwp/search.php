<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package fixonweb-clean
 */

get_header();
?>
	<main id="primary" class="site-main">
		<?php //echo do_shortcode("[hfe_template id='79']") ?>
		<?php echo do_shortcode("[fix159154_busca_result']") ?>
		
	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
