<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Square_Gateway extends WC_Payment_Gateway {
	protected $connect;
	protected $token;
	public $log;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id		            = 'square';
		$this->method_title 	    = __( 'Square', 'woocommerce-square' );
		$this->method_description   = __( 'Square works by adding payments fields in an iframe and then sending the details to Square for verification and processing.', 'woocommerce-square' );
		$this->has_fields 	        = true;
		$this->supports 	        = array(
			'products',
			'refunds',
		);

		// Load the form fields
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values
		$this->title           = $this->get_option( 'title' );
		$this->description     = $this->get_option( 'description' );
		$this->enabled         = $this->get_option( 'enabled' ) === 'yes' ? true : false;
		$this->capture         = $this->get_option( 'capture' ) === 'yes' ? true : false;
		$this->create_customer = $this->get_option( 'create_customer' ) === 'yes' ? true : false;
		$this->logging         = $this->get_option( 'logging' ) === 'yes' ? true : false;
		$this->connect         = new WC_Square_Payments_Connect(); // decouple in future when v2 is ready
		$this->token           = get_option( 'woocommerce_square_merchant_access_token' );

		$this->connect->set_access_token( $this->token );

		if ( WC_SQUARE_ENABLE_STAGING ) {
			$this->description .= ' ' . __( 'STAGING MODE ENABLED. In staging mode, you can use the card number 4111111111111111 with any CVC and a valid expiration date.', 'woocommerce-square' );
			
			$this->description  = trim( $this->description );
		}

		// Hooks
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * get_icon function.
	 *
	 * @access public
	 * @return string
	 */
	public function get_icon() {
		$icon  = '<img src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/visa.svg' ) . '" alt="Visa" width="32" style="margin-left: 0.3em" />';
		$icon .= '<img src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard.svg' ) . '" alt="Mastercard" width="32" style="margin-left: 0.3em" />';
		$icon .= '<img src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/amex.svg' ) . '" alt="Amex" width="32" style="margin-left: 0.3em" />';

		$icon .= '<img src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/discover.svg' ) . '" alt="Discover" width="32" style="margin-left: 0.3em" />';
		
		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	/**
	 * Check if required fields are set
	 */
	public function admin_notices() {
		if ( ! $this->enabled ) {
			return;
		}

		// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
		if ( ! WC_SQUARE_ENABLE_STAGING && get_option( 'woocommerce_force_ssl_checkout' ) === 'no' && ! class_exists( 'WordPressHTTPS' ) ) {
			echo '<div class="error"><p>' . sprintf( __( 'Square is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout is not secured! Please enable SSL and ensure your server has a valid SSL certificate.', 'woocommerce-square' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
		}
	}

	/**
	 * Check if this gateway is enabled
	 */
	public function is_available() {
		if ( $this->enabled ) {
			if ( ! WC_SQUARE_ENABLE_STAGING && is_checkout() && ! is_ssl() ) {
				return false;
			}

			if ( ! WC_SQUARE_ENABLE_STAGING && empty( $this->token ) ) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters( 'wc_square_gateway_settings', array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-square' ),
				'label'       => __( 'Enable Square', 'woocommerce-square' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => __( 'Title', 'woocommerce-square' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-square' ),
				'default'     => __( 'Credit card (Square)', 'woocommerce-square' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-square' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-square' ),
				'default'     => __( 'Pay with your credit card via Square.', 'woocommerce-square')
			),
			'capture' => array(
				'title'       => __( 'Delay Capture', 'woocommerce-square' ),
				'label'       => __( 'Enable Delay Capture', 'woocommerce-square' ),
				'type'        => 'checkbox',
				'description' => __( 'When enabled, the request will only perform an Auth on the provided card. You can then later perform either a Capture or Void.', 'woocommerce-square' ),
				'default'     => 'no'
			),
			'create_customer' => array(
				'title'       => __( 'Create Customer', 'woocommerce-square' ),
				'label'       => __( 'Enable Create Customer', 'woocommerce-square' ),
				'type'        => 'checkbox',
				'description' => __( 'When enabled, processing a payment will create a customer profile on Square.', 'woocommerce-square' ),
				'default'     => 'no'
			),
			'logging' => array(
				'title'       => __( 'Logging', 'woocommerce-square' ),
				'label'       => __( 'Log debug messages', 'woocommerce-square' ),
				'type'        => 'checkbox',
				'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'woocommerce-square' ),
				'default'     => 'no'
			),
		) );
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() { ?>
		<fieldset>
			<?php
				$allowed = array(
				    'a' => array(
				        'href' => array(),
				        'title' => array()
				    ),
				    'br' => array(),
				    'em' => array(),
				    'strong' => array(),
				    'span'	=> array(
				    	'class' => array(),
				    ),
				);
				if ( $this->description ) {
					echo apply_filters( 'wc_square_description', wpautop( wp_kses( $this->description, $allowed ) ) );
				}
			?>
			<p class="form-row form-row-wide">
				<label for="sq-card-number"><?php esc_html_e( 'Card Number', 'woocommerce-square' ); ?> <span class="required">*</span></label>
				<input id="sq-card-number" class="input-text wc-credit-card-form-card-number" type="text" maxlength="20" autocomplete="off" placeholder="•••• •••• •••• ••••" name="<?php echo esc_attr( $this->id ); ?>-card-number" />
			</p>
			
			<p class="form-row form-row-first">
				<label for="sq-expiration-date"><?php esc_html_e( 'Expiry (MM/YY)', 'woocommerce-square' ); ?> <span class="required">*</span></label>
				<input id="sq-expiration-date" class="input-text wc-credit-card-form-card-expiry" type="text" autocomplete="off" placeholder="<?php esc_attr_e( 'MM / YY', 'woocommerce-square' ); ?>" name="<?php echo esc_attr( $this->id ); ?>-card-expiry" />
			</p>

			<p class="form-row form-row-last">
				<label for="sq-cvv"><?php esc_html_e( 'Card Code', 'woocommerce-square' ); ?> <span class="required">*</span></label>
				<input id="sq-cvv" class="input-text wc-credit-card-form-card-cvv" type="text" autocomplete="off" placeholder="<?php esc_attr_e( 'CVV', 'woocommerce-square' ); ?>" name="<?php echo esc_attr( $this->id ); ?>-card-cvv" />
			</p>

			<p class="form-row form-row-first">
				<label for="sq-postal-code"><?php esc_html_e( 'Card Postal Code', 'woocommerce-square' ); ?> <span class="required">*</span></label>
				<input id="sq-postal-code" class="input-text wc-credit-card-form-card-postal-code" type="text" autocomplete="off" placeholder="<?php esc_attr_e( 'Card Postal Code', 'woocommerce-square' ); ?>" name="<?php echo esc_attr( $this->id ); ?>-card-postal-code" />
			</p>
		</fieldset>
		<?php
	}

	/**
	 * payment_scripts function.
	 *
	 *
	 * @access public
	 */
	public function payment_scripts() {
		if ( ! is_checkout() ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'square', 'https://js.squareup.com/v2/paymentform', '', '0.0.2', true );
		wp_register_script( 'woocommerce-square', WC_SQUARE_PLUGIN_URL . '/assets/js/wc-square-payments' . $suffix . '.js', array( 'jquery', 'square' ), WC_SQUARE_VERSION, true );

		wp_localize_script( 'woocommerce-square', 'square_params', array(
			'application_id'               => SQUARE_APPLICATION_ID,
			'environment'                  => WC_SQUARE_ENABLE_STAGING ? 'staging' : 'production',
			'placeholder_card_number'      => __( '•••• •••• •••• ••••', 'woocommerce-square' ),
			'placeholder_card_expiration'  => __( 'MM / YY', 'woocommerce-square' ),
			'placeholder_card_cvv'         => __( 'CVV', 'woocommerce-square' ),
			'placeholder_card_postal_code' => __( 'Card Postal Code', 'woocommerce-square' ),
		) );

		wp_enqueue_script( 'woocommerce-square' );
		
		wp_enqueue_style( 'woocommerce-square-styles', WC_SQUARE_PLUGIN_URL . '/assets/css/wc-square-frontend-styles.css' );

		return true;
	}

	/**
	 * Process the payment
	 */
	public function process_payment( $order_id, $retry = true ) {
		$order    = wc_get_order( $order_id );
		$nonce    = isset( $_POST['square_nonce'] ) ? wc_clean( $_POST['square_nonce'] ) : '';
		$currency = $order->get_order_currency();

		$this->log( "Info: Begin processing payment for order {$order_id} for the amount of {$order->order_total}" );

		try {
			$data = array(
				'idempotency_key' => uniqid(),
				'amount_money'    => array( 
					'amount'   => $this->format_amount( $order->order_total, $currency ),
					'currency' => $currency,
				),
				'reference_id'        => (string) $order->get_order_number(),
				'delay_capture'       => $this->capture ? true : false,
				'card_nonce'          => $nonce,
				'buyer_email_address' => $order->billing_email,
				'billing_address'     => array(
					'address_line_1'                  => $order->billing_address_1,
					'address_line_2'                  => $order->billing_address_2,
					'locality'                        => $order->billing_city,
					'administrative_district_level_1' => $order->billing_state,
					'postal_code'                     => $order->billing_postcode,
					'country'                         => $order->billing_country,
				),
				'note' => apply_filters( 'woocommerce_square_payment_order_note', 'WooCommerce: Order #' . (string) $order->get_order_number(), $order )
			);

			if ( $order->needs_shipping_address() ) {
				$data['shipping_address'] = array(
					'address_line_1'                  => $order->shipping_address_1,
					'address_line_2'                  => $order->shipping_address_2,
					'locality'                        => $order->shipping_city,
					'administrative_district_level_1' => $order->shipping_state,
					'postal_code'                     => $order->shipping_postcode,
					'country'                         => $order->shipping_country,					
				);
			}

			$result = $this->connect->charge_card_nonce( Woocommerce_Square::instance()->integration->get_option( 'location' ), $data );

			if ( is_wp_error( $result ) ) {
				wc_add_notice( __( 'Error: Square was unable to complete the transaction. Please try again later or use another means of payment.', 'woocommerce-square' ), 'error' );

				throw new Exception( $result->get_error_message() );
			}

			if ( ! empty( $result->errors ) ) {
				if ( 'INVALID_REQUEST_ERROR' === $result->errors[0]->category ) {
					wc_add_notice( __( 'Error: Square was unable to complete the transaction. Please try again later or use another means of payment.', 'woocommerce-square' ), 'error' );
				}
				
				if ( 'PAYMENT_METHOD_ERROR' === $result->errors[0]->category || 'VALIDATION_ERROR' === $result->errors[0]->category ) {
					// format errors for display
					$error_html = __( 'Payment Error: ', 'woocommerce-square' );
					$error_html .= '<br />';
					$error_html .= '<ul>';

					foreach( $result->errors as $error ) {
						$error_html .= '<li>' . $error->detail . '</li>';
					}

					$error_html .= '</ul>';

					wc_add_notice( $error_html, 'error' );
				}
				
				$errors = print_r( $result->errors, true );

				throw new Exception( $errors );
			}

			if ( 'CAPTURED' === $result->transaction->tenders[0]->card_details->status ) {
				// Store captured value
				update_post_meta( $order->id, '_square_charge_captured', 'yes' );

				// Payment complete
				$order->payment_complete( $result->transaction->id );

				// Add order note
				$complete_message = sprintf( __( 'Square charge complete (Charge ID: %s)', 'woocommerce-square' ), $result->transaction->id );
				$order->add_order_note( $complete_message );
				$this->log( "Success: $complete_message" );				
			} else {
				// Store captured value
				update_post_meta( $order->id, '_square_charge_captured', 'no' );
				add_post_meta( $order->id, '_transaction_id', $result->transaction->id, true );

				// Mark as on-hold
				$authorized_message = sprintf( __( 'Square charge authorized (Authorized ID: %s). Process order to take payment, or cancel to remove the pre-authorization.', 'woocommerce-square' ), $result->transaction->id );
				$order->update_status( 'on-hold', $authorized_message );
				$this->log( "Success: $authorized_message" );

				// Reduce stock levels
				$order->reduce_order_stock();
			}

			// we got this far which means the transaction went through
			if ( $this->create_customer ) {
				$this->maybe_create_customer( $order );
			}
			
			// Remove cart
			WC()->cart->empty_cart();

			// Return thank you page redirect
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);
		} catch ( Exception $e ) {
			$this->log( sprintf( __( 'Error: %s', 'woocommerce-square' ), $e->getMessage() ) );

			$order->update_status( 'failed', $e->getMessage() );

			return;
		}
	}

	/**
	 * Tries to create the customer on Square
	 *
	 * @param object $order
	 */
	public function maybe_create_customer( $order ) {
		$user               = get_current_user_id();
		$square_customer_id = get_user_meta( $user, '_square_customer_id', true );

		$create_customer = true;

		$customer = array(
			'given_name'    => $order->billing_first_name,
			'family_name'   => $order->billing_last_name,
			'email_address' => $order->billing_email,
			'address'       => array(
				'address_line_1'                  => $order->billing_address_1,
				'address_line_2'                  => $order->billing_address_2,
				'locality'                        => $order->billing_city,
				'administrative_district_level_1' => $order->billing_state,
				'postal_code'                     => $order->billing_postcode,
				'country'                         => $order->billing_country,
			),
			'phone_number' => (string) $order->billing_phone,
			'reference_id' => ! empty( $user ) ? (string) $user : __( 'Guest', 'woocommerce-square' ),
		);

		// to prevent creating duplicate customer
		// check to make sure this customer does not exist on Square
		if ( ! empty( $square_customer_id ) ) {
			$square_customer = $this->connect->get_customer( $square_customer_id );

			if ( empty( $square_customer->errors ) ) {
				// customer already exist on Square
				$create_customer = false;
			}
		}

		if ( $create_customer ) {
			$result = $this->connect->create_customer( $customer );

			// we don't want to halt any processes here just log it
			if ( is_wp_error( $result ) ) {
				$this->log( sprintf( __( 'Error creating customer: %s', 'woocommerce-square' ), $result->get_error_message() ) );
				$order->add_order_note( sprintf( __( 'Error creating customer: %s', 'woocommerce-square' ), $result->get_error_message() ) );
			}

			// we don't want to halt any processes here just log it
			if ( ! empty( $result->errors ) ) {
				$this->log( sprintf( __( 'Error creating customer: %s', 'woocommerce-square' ), print_r( $result->errors, true ) ) );
				$order->add_order_note( sprintf( __( 'Error creating customer: %s', 'woocommerce-square' ), print_r( $result->errors, true ) ) );
			}

			// if no errors save Square customer ID to user meta
			if ( ! is_wp_error( $result ) && empty( $result->errors ) && ! empty( $user ) ) {
				update_user_meta( $user, '_square_customer_id', $result->customer->id );
				$order->add_order_note( sprintf( __( 'Customer created on Square: %s', 'woocommerce-square' ), $result->customer->id ) );
			}
		}
	}

	/**
	 * Process amount to be passed to Square.
	 * @return float
	 */
	public function format_amount( $total, $currency = '' ) {
		if ( ! $currency ) {
			$currency = get_woocommerce_currency();
		}

		switch ( strtoupper( $currency ) ) {
			// Zero decimal currencies
			case 'BIF' :
			case 'CLP' :
			case 'DJF' :
			case 'GNF' :
			case 'JPY' :
			case 'KMF' :
			case 'KRW' :
			case 'MGA' :
			case 'PYG' :
			case 'RWF' :
			case 'VND' :
			case 'VUV' :
			case 'XAF' :
			case 'XOF' :
			case 'XPF' :
				$total = absint( $total );
				break;
			default :
				$total = round( $total, 2 ) * 100; // In cents
				break;
		}

		return $total;
	}

	/**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() ) {
			return false;
		}

		if ( 'square' === $order->payment_method ) {
			try {
				$this->log( "Info: Begin refund for order {$order_id} for the amount of {$amount}" );

				$trans_id = get_post_meta( $order_id, '_transaction_id', true );
				$captured = get_post_meta( $order_id, '_square_charge_captured', true );
				$location = Woocommerce_Square::instance()->integration->get_option( 'location' );

				$transaction_status = $this->connect->get_transaction_status( $location, $trans_id );

				if ( 'CAPTURED' === $transaction_status ) {
					$tender_id = $this->connect->get_tender_id( $location, $trans_id );

					$body = array();

					$body['idempotency_key'] = uniqid();
					$body['tender_id']       = $tender_id;

					if ( ! is_null( $amount ) ) {
						$body['amount_money'] = array(
							'amount'   => $this->format_amount( $amount ),
							'currency' => $order->get_order_currency(),
						);
					}

					if ( $reason ) {
						$body['reason'] = $reason;
					}

					$result = $this->connect->refund_transaction( $location, $trans_id, $body );

					if ( is_wp_error( $result ) ) {
						throw new Exception( $result->get_error_message() );

					} elseif ( ! empty( $result->errors ) ) {
						throw new Exception( "Error: " . print_r( $result->errors, true ) );
						
					} else {
						if ( 'APPROVED' === $result->refund->status || 'PENDING' === $result->refund->status ) {
							$refund_message = sprintf( __( 'Refunded %s - Refund ID: %s - Reason: %s', 'woocommerce-square' ), wc_price( $result->refund->amount_money->amount / 100 ), $result->refund->id, $reason );
						
							$order->add_order_note( $refund_message );
						
							$this->log( "Success: " . html_entity_decode( strip_tags( $refund_message ) ) );
						
							return true;
						}
					}
				}

			} catch ( Exception $e ) {
				$this->log( sprintf( __( 'Error: %s', 'woocommerce-square' ), $e->getMessage() ) );

				return false;
			}
		}
	}

	/**
	 * Logs
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		if ( $this->logging ) {
			WC_Square_Payment_Logger::log( $message );
		}
	}
}
