<?php

add_action( 'woocommerce_checkout_order_processed', 'change_new_order' );

function change_new_order( $order_id ) {
	$order = wc_get_order( $order_id );

	$special_products = [];

	foreach ( $order->get_items() as $item_id => $item_data ) {
		$current_product          = $item_data->get_product();
		$current_product_id       = $current_product->id;
		$current_product_quantity = $item_data->get_quantity();

		if ( $current_product_id == 110 || $current_product_id == 111 || $current_product_id == 112 ) {
			$special_products[ $current_product_id ] = $current_product_quantity;

			wc_delete_order_item( $item_id );
		}
	}

	if ( count( $special_products ) > 0 ) {
		$order_data = $order->get_data();

		$new_order = wc_create_order();

		foreach ( $special_products as $pr => $q ) {
			$new_order->add_product( wc_get_product( $pr ), $q );
		}

		$new_order->calculate_totals();

		$new_order->set_address( $order_data['billing'], 'billing' );
		$new_order->set_address( $order_data['shipping'], 'shipping' );

		$new_order->set_customer_id( $order_data['customer_id'] );

		$new_order->set_payment_method( $order_data['payment_method'] );
		$new_order->set_payment_method_title( $order_data['payment_method_title'] );

		$new_order->set_status( 'wc-on-hold' );

		$new_order->save();
	}
}