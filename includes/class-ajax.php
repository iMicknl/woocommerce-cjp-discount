<?php

namespace WooCommerce_CJP;

class Ajax {

	/**
	 * @action cjp_validate_credentials
	 */
	public static function cjp_validate_credentials() {

		// Verify Ajax request
		if ( check_ajax_referer( 'cjp_validate_credentials', 'security' ) === false ) {
			wp_send_json_error( [ 'message' => 'Invalid Request' ] );
		}

		// Retrieve variables and sanitize
		$card_number = sanitize_text_field( $_POST['card_number'] );
		$birthdate   = sanitize_text_field( $_POST['birthdate'] );

		if ( empty( $card_number ) || empty( $birthdate ) ) {
			wp_send_json_error( [
				'message' => 'Please fill in both fields.'
			] );
		}

		//TODO Use get_option('date_format') while converting date
		$birthdate = date( 'c', strtotime( $birthdate ) );
		$response  = self::check_cjp_card( $card_number, $birthdate );

		if ( $response !== true ) {
			wp_send_json_error( [
				'message' => $response,
			] );
		}

		wp_send_json_success();
	}

	/**
	 * @param $card_number string
	 * @param $birthdate string
	 *
	 * @return bool
	 */
	public static function check_cjp_card( $card_number, $birthdate ) {

		$data = [
			'card_number' => $card_number,
			'birthdate'   => $birthdate
		];

		$response = wp_remote_get( 'https://checkapi.nl/v1/validate?' . build_query( $data ), [
				'timeout'     => 5,
				'redirection' => 5,
				'blocking'    => true,
				'headers'     => [
					'Authorization' => 'Token token=' . get_option( 'woocommerce_cjp_discount_apikey' ),
					'Content-Type'  => 'application/json; charset=utf-8'
				]
			]
		);

		// Check if response contains errors
		if ( wp_remote_retrieve_response_code( $response ) >= 400 || is_wp_error( $response ) ) {
			return "We couldn't validate your credentials at the moment. Please try again later.";
		}

		// Check if CJP-number is valid
		if ( wp_remote_retrieve_body( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $response['valid'] !== true ) {
				return 'The provided Date of Birth and Card Number combination is not valid.';
			}
		}

		// Create coupon
		$coupon      = new CJP_Coupon();
		$coupon_code = $coupon->create( $card_number );

		// Add coupon to current cart
		WC()->cart->add_discount( $coupon_code );

		return true;
	}

}
