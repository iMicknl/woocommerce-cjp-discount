<?php
namespace WooCommerce_CJP;

/**
 * Class Admin
 * @package WooCommerce_CJP
 */
class Admin {

	public function __construct() {
		add_filter( 'woocommerce_get_settings_checkout', [ $this, 'init_settings' ], 1, 2 );
	}

	/**
	 * Add CJP specific settings to WooCommerce Checkout Settings
	 *
	 * (settings are not in a new tab due to WooCommerce limitations)
	 * https://github.com/woocommerce/woocommerce/issues/9120
	 *
	 * @param $settings array
	 * @param $current_section string
	 *
	 * @return array
	 */
	public function init_settings( $settings, $current_section ) {

		$prefix = 'woocommerce_cjp_discount';

		$settings[] = [
			'type'  => 'title',
			'title' => __( 'CJP Discount', 'woocommerce-cjp-discount' ),
			'id'    => $prefix,
			'desc'  => 'Cultureel Jongeren Paspoort cardholders can receive a discount automatically after their card is verified via the CJP API. <a target="_blank" href="https://www.cjp.nl/over-cjp/2016/04/controleer-cjp-pas-op-eigen-website/77719/">Read more about implementing the CJP check on your own website.</a>',
		];

		$settings[] = array(
			'title'    => __( 'API key', 'woocommerce-cjp-discount' ),
			'desc_tip' => 'Get your API key from CJP.nl',
			'id'       => $prefix . '_apikey',
			'default'  => '',
			'type'     => 'text',
			'autoload' => false
		);


		$settings[] = array(
			'title'       => __( 'Description', 'woocommerce_cjp_discount' ),
			'type'        => 'textarea',
			'description' => __( 'Description that the customer will see on your checkout.', 'woocommerce_cjp_discount' ),
			'default'     => 'With a [(Dutch) CJP-card] you get a €10,- discount!',
			'desc_tip'    => false,
			'desc'     => __( "Text between brackets [] will link to the 'Become a Cardholder' page on CJP.nl", 'woocommerce_cjp_discount' ),
			'id'       => $prefix . '_description',
			'autoload' => false,
			'class'    => 'wide-input'
		);

		$settings[] = array(
			'title'    => __( 'Individual use', 'woocommerce_cjp_discount' ),
			'desc'     => __( 'No, the discount can not be used in combination with other coupons.', 'woocommerce_cjp_discount' ),
			'id'       => $prefix . '_individual_use',
			'default'  => 'yes',
			'type'     => 'checkbox',
			'autoload' => false
		);

		$settings[] = array(
			'title'    => __( 'Discount Type', 'woocommerce_cjp_discount' ),
			'desc'     => __( '', 'woocommerce' ),
			'id'       => $prefix . '_discount_type',
			'options'  => array(
				'fixed_cart' => 'Fixed Cart',
				'percent'    => 'Percent',
			),
			'type'     => 'select',
			'desc_tip' => __( '', 'woocommerce_cjp_discount' ),
			'autoload' => false
		);

		$settings[] = array(
			'title'    => __( 'Usage Limit', 'woocommerce_cjp_discount' ),
			'desc'     => __( '', 'woocommerce' ),
			'id'       => $prefix . '_usage_limit',
			'default'  => '1',
			'type'     => 'number',
			'desc_tip' => __( '', 'woocommerce_cjp_discount' ),
			'autoload' => false
		);

		$settings[] = array(
			'title'    => __( 'Amount', 'woocommerce' ),
			'desc'     => __( '', 'woocommerce' ),
			'id'       => $prefix . '_amount',
			'default'  => '10',
			'type'     => 'number',
			'desc_tip' => __( '', 'woocommerce_cjp_discount' ),
			'autoload' => false
		);

		$settings[] = array( 'type' => 'sectionend', 'id' => 'woocommerce_cjp_discount' );

		return $settings;
	}

}
