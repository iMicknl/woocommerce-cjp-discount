<?php
namespace WooCommerce_CJP;

class Checkout {

	public function __construct() {
		add_action( 'wc_ajax_cjp_validate_credentials', [ Ajax::class, 'cjp_validate_credentials' ] );
		add_action( 'wc_ajax_nopriv_cjp_validate_credentials', [ Ajax::class, 'cjp_validate_credentials' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'woocommerce_before_checkout_form', [ $this, 'add_checkout_form' ], 10 );
	}

	/**
	 * Add info message and form to checkout page
	 */
	public function add_checkout_form() {

		// Add URL to info message
		$info_message = preg_replace_callback(
			'/(\[.*?\])/',
			function ( $matches ) {
				$text = trim( $matches[0], '[]' );
				$url  = '<a href="https://expat.cjp.nl" target="_blank">' . $text . '</a>';

				return $url;
			},
			get_option( 'woocommerce_cjp_discount_description' )
		);

		$info_message .= ' <a href="#" class="js-showcjp">Click here to claim your discount</a>';

		wc_print_notice( $info_message, 'notice' );
		?>

        <form class="checkout_cjp_discount" method="post" style="display:none">
            <p class="form-row form-row-first">
                <input type="text" name="card_number" class="input-text"
                       placeholder="<?php esc_attr_e( 'Card Number' ); ?>"
                       id="card_number" value=""/>
                <input type="text" name="birthdate" class="input-text"
                       placeholder="<?php esc_attr_e( 'Date of birth' ); ?>"
                       id="datepicker" value=""/>
            </p>

			<?php wp_nonce_field( 'cjp_validate_credentials' ); ?>

            <p class="form-row form-row-last">
                <input type="submit" class="button" name="apply_discount" value="<?php esc_attr_e( 'Validate' ); ?>"/>
            </p>

            <div class="clear"></div>
        </form>

		<?php
	}

	/**
	 * Enqueue scripts and styles on checkout page
	 */
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( is_checkout() ) {
			wp_enqueue_script( 'cjp-checkout', plugin_dir_url( CJP_DISCOUNT_PATH ) . 'assets/js/checkout' . $suffix . '.js', [
				'jquery-ui-datepicker',
				'jquery'
			], 1.0, true );

			wp_enqueue_style( 'jquery-ui', plugin_dir_url( CJP_DISCOUNT_PATH ) . 'assets/css/jquery-ui' . $suffix . '.css', [], '1.12.1' );
		}

	}

}
