<?php
namespace WooCommerce_CJP;

class CJP_Coupon {

	/**
	 * Create CJP coupon based on card_number and birth_date
	 *
	 * @param $card_number
	 * @param array $options
	 *
	 * @return int|\WP_Error
	 */
	public function create( $card_number, $options = [] ) {

		$coupon_code = 'cjp_' . $card_number;

		$default_options = [
			'discount_type'  => get_option( 'woocommerce_cjp_discount_discount_type' ),
			'individual_use' => get_option( 'woocommerce_cjp_discount_individual_use' ),
			'amount'         => get_option( 'woocommerce_cjp_discount_amount' ),
			'usage_limit'    => get_option( 'woocommerce_cjp_discount_usage_limit' ),
		];

		$options = array_merge( $default_options, $options );
		$coupon  = wc_get_coupon_id_by_code( $coupon_code );

		if ( ! empty( $coupon ) ) {
			return $coupon;
		}

		$coupon = new \WC_Coupon();

		$coupon->set_code( $coupon_code );
		$coupon->set_discount_type( $options['discount_type'] );
		$coupon->set_amount( $options['amount'] );
		$coupon->set_individual_use( $options['individual_use'] );
		$coupon->set_usage_limit( $options['usage_limit'] );
		$coupon->set_description( 'Automatically generated coupon for CJP card ' . $card_number );

		$coupon_id = $coupon->save();

		return $coupon_id;
	}

}
