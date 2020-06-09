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
		<?php //echo do_shortcode("[fix159154_busca_result']") ?>

		<?php 


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

	
		?>
		
	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
