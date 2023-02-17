<?php

add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {

	$tabs['new_tab'] = array(
		'title'    => __( 'Information for goners', 'woocommerce' ),
		'priority' => 50,
		'callback' => 'information_for_goners_tab_content'
	);

	return $tabs;
}

function information_for_goners_tab_content() {
	$prod_id = get_the_ID();
	echo'<p>'.get_post_meta($prod_id,'information_for_goners',true).'</p>';
}


add_action( 'add_meta_boxes', 'product_information_for_goners', 1 );

function product_information_for_goners() {
	add_meta_box( 'information_for_goners', 'Information for goners', 'information_for_goners_box', 'product', 'normal', 'high' );
}

function information_for_goners_box( $post ) {
	?>
	<textarea id="information_for_goners" name="information_for_goners"><?= get_post_meta($post->ID, 'information_for_goners', true); ?></textarea>
	<?php
}

add_action( 'save_post', 'information_for_goners_fields_update', 0 );

function information_for_goners_fields_update( $post_id ){
	if (isset($_POST['information_for_goners'])) {
		update_post_meta( $post_id, 'information_for_goners', $_POST['information_for_goners'] );
	}

	return $post_id;
}