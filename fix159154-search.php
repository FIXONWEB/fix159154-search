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
    		$s = isset($_GET['s']) ? $_GET['s'] : '';
            wp_redirect( site_url()."/busca/?busca=".$s );
			exit;
        }
    }
}

// add_action('pre_get_posts','search_filter');

add_shortcode("fix159154_busca_form", "fix159154_busca_form");
function fix159154_busca_form($atts, $content = null){
	$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
	ob_start();
	?>
		<div id="medium-searchform" class="header-searchform-wrap clr">
			<form method="get" action="<?php site_url() ?>/busca/" class="header-searchform" role="search" aria-label="Medium Header Search">
				<input type="search" name="busca" autocomplete="off" value="<?php echo $busca ?>">
				<button class="search-submit"><i class="icon-magnifier"></i></button>
				<div class="search-bg"></div>
			</form>
		</div>
	<?php
	return ob_get_clean();
}

//--request
add_action( 'parse_request', 'fix159157_parse_request');

function fix159157_parse_request( &$wp ) {
  	$s = isset($_GET['s']) ? $_GET['s'] : '';
  	if($s) {
		wp_redirect( site_url()."/busca/?busca=".$s );
		exit;
	}
	// if($wp->request == 'fixbackup_info'){

}



add_shortcode("fix159154_busca_result", "fix159154_busca_result");
function fix159154_busca_result($atts, $content = null){
	global $wpdb;

	$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

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
	limit 0,20
	";
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$q=$mysqli->query($sql);

	while($r=$q->fetch_assoc()) {
    	// print_r($r);
    	// $post_title = $r->post_title;
    	$post_title = $r['post_title'];
    	?>
    	<div>
    		<?php echo $post_title; ?>	
    	</div>
    	<?php
	}
	// $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	// $result = mysqli_query($mysqli, $sql);

	
	?>


	<?php
	return ob_get_clean();
}
