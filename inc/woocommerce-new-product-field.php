<?php

add_action( 'woocommerce_before_add_to_cart_button', 'add_user_price_field' );

function add_user_price_field() {
//	global $product;

	ob_start();

	?>
    <div>
        <label for="user_price">Enter your price</label>
        <input type="text" name="user_price">
    </div>
    <div class="clear"></div>

	<?php

	$content = ob_get_contents();
	ob_end_flush();

	return $content;
}

add_filter( 'woocommerce_add_cart_item_data', 'add_user_price_to_cart', 10, 3 );

function add_user_price_to_cart( $cart_item_data, $product_id, $variation_id ) {
	if ( isset( $_REQUEST['user_price'] ) ) {
		$cart_item_data['user_price'] = sanitize_text_field( $_REQUEST['user_price'] );
	}

	return $cart_item_data;
}

add_filter( 'woocommerce_get_item_data', 'add_user_price_meta', 10, 2 );

function add_user_price_meta( $item_data, $cart_item ) {
	if ( array_key_exists( 'user_price', $cart_item ) ) {
		$custom_details = $cart_item['user_price'];

		$item_data[] = array(
			'key'   => 'Your price',
			'value' => $custom_details
		);
	}

	return $item_data;
}


add_action( 'woocommerce_checkout_create_order_line_item', 'add_user_price_item_meta', 10, 4 );

function add_user_price_item_meta( $item, $cart_item_key, $values, $order ) {

	if ( array_key_exists( 'user_price', $values ) ) {
		$item->add_meta_data( 'User price', $values['user_price'] );
	}
}
