<?php
/**
 * Plugin Name:     Fixonweb Search
 * Plugin URI:      https://fixonweb.com.br/plugin/fix159154-search
 * Description:     Ref: 159154 - Ajustes no serch padrão
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix159154
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Fix159154
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://github.com/fixonweb/fix159154-search',__FILE__, 'fix159154-search/fix159154-search');



function search_filter($query) {
    if ( !is_admin() && $query->is_main_query() ) {

		if ($query->is_search) {
		// 	$s = isset($_GET['s']) ? $_GET['s'] : '';
		// 	wp_redirect( site_url()."/busca/?busca=".$s );
		// 	exit;
			print_r ($query);
			die();
		}
    }
}

// add_action('pre_get_posts','search_filter');

// add_action('pre_get_posts', function (\WP_Query $q) {
// 	if ( !is_admin() && $q->is_main_query() ) {
// 		wp_reset_query();
// 		$s = 'testeeeeeeee';
// 		$q = " LIKE '%$s%' ";

// 		$q->set('s','fato');
// 	}
// });














add_shortcode("fix159154_busca_form", "fix159154_busca_form");
function fix159154_busca_form($atts, $content = null){
	$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
	ob_start();
	?>
	<style type="text/css" media="screen">
		.fix159154_busca_form {
			display: inline-block;
			position: relative;
		}
		.fix159154_busca_form input {
		    position: relative;
		    background-color: transparent !important;
		    border: 0;
		    margin: 0;
		    padding: 6px 38px 6px 12px;
		    max-width: 178px;
		    z-index: 2;
		}
		.fix159154_busca_form button {
		    right: 0;
		    width: 38px;
		    background-color: transparent;
		    color: #555;
		    border: 0;
		    padding: 0;
		    z-index: 2;
		    -webkit-transition: all 0.3s ease;
		    -moz-transition: all 0.3s ease;
		    -ms-transition: all 0.3s ease;
		    -o-transition: all 0.3s ease;
		    transition: all 0.3s ease;
    		}
	</style>
		<div id="medium-searchform" class="header-searchform-wrap clr">
			<form method="get" action="<?php site_url() ?>/busca/" class="fix159154_busca_form" role="search" aria-label="Medium Header Search">
				<input type="search" name="busca" autocomplete="off" value="<?php echo $busca ?>">
				<button class="search-submit"><i class="icon-magnifier"></i></button>
				<div class="search-bg"></div>
			</form>
		</div>
	<?php
	return ob_get_clean();
}

//--request
// add_action( 'parse_request', 'fix159157_parse_request');
function fix159157_parse_request( &$wp ) {
  	$s = isset($_GET['s']) ? $_GET['s'] : '';
  	if($s) {
  		// if ( !is_admin() && $query->is_main_query() ) {
			wp_redirect( site_url()."/busca/?busca=$s" );
			exit;
		// }
	}
}


add_filter('template_include','my_custom_search_template');

function my_custom_search_template($template){
    global $wp_query;
    if (!$wp_query->is_search){
        return $template;
    }
    return dirname( __FILE__ ) . '/templates/oceanwp/search.php';
}

add_shortcode("fix159154_busca_result", "fix159154_busca_result");
function fix159154_busca_result($atts, $content = null){
	global $wpdb;

	// $busca = isset($_GET['busca']) ? $_GET['busca'] : '';
	$busca = isset($_GET['s']) ? $_GET['s'] : '';

	ob_start();

	$sql = "
	SELECT 
	* 
	FROM ".$wpdb->prefix."posts 
	WHERE 
		(
			post_title LIKE '$busca %' 
			or post_title LIKE '% $busca %' 
			or post_title LIKE '% $busca' 
			or post_title LIKE '% $busca.'
		)
		and post_status = 'publish'

	limit 0,20
	";
	// echo $sql;
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$q=$mysqli->query($sql);

	?>
	<div style="border: 0px solid gray; margin: 10px;">
	<?php


	$i = 0;
	while($r=$q->fetch_assoc()) {
		$i++;
    	// print_r($r);
    	// $post_title = $r->post_title;
    	$post_title = $r['post_title'];
    	?>
    	<div>
    		<h2><a href="<?php echo $r['guid'] ?>" title=""><?php echo $post_title; ?></a></h2>
    	</div>
    	<?php
	}
	


	?>
	</div>
	<?php


	
	?>
	<div>
		<i>
			<?php echo $i ?> results: <?php echo $busca; ?>
		</i>
	</div>
	<?php


	return ob_get_clean();
}
