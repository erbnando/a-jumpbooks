(function ( $ ) {
	'use strict';

	var wcSquarePaymentForm;

	// create namespace to avoid any possible conflicts
	$.wc_square_payments = {

		init: function() {
			if ( $( '#payment_method_square' ).length ) {
				wcSquarePaymentForm = new SqPaymentForm({
					env: square_params.environment,
					applicationId: square_params.application_id,
					inputClass: 'sq-input',
					cardNumber: {
						elementId: 'sq-card-number',
						placeholder: square_params.placeholder_card_number   
					},
					cvv: {
						elementId: 'sq-cvv',
						placeholder: square_params.placeholder_card_cvv
					},
					expirationDate: {
						elementId: 'sq-expiration-date',
						placeholder: square_params.placeholder_card_expiration
					},
					postalCode: {
						elementId: 'sq-postal-code',
						placeholder: square_params.placeholder_card_postal_code
					},
					callbacks: {
						cardNonceResponseReceived: function( errors, nonce, cardData ) {
							if ( errors ) {
								var html = '';

								html += '<ul class="woocommerce_error woocommerce-error">';

								// handle errors
								$( errors ).each( function( index, error ) { 
									html += '<li>' + error.message + '</li>';
								});

								html += '</ul>';

								// append it to DOM
								$( '.payment_method_square fieldset' ).eq(0).prepend( html );
							} else {
								var $form = $( 'form.checkout' );

								// inject nonce to a hidden field to be submitted
								$form.append( '<input type="hidden" class="square-nonce" name="square_nonce" value="' + nonce + '" />' );

								$form.submit();
							}
						},

						unsupportedBrowserDetected: function() {
							var html = '';

							html += '<ul class="woocommerce_error woocommerce-error">';
							html += '<li>' + square_params.unsupported_browser + '</li>';
							html += '</ul>';

							// append it to DOM
							$( '.payment_method_square fieldset' ).eq(0).prepend( html );
						}
					},
					inputStyles: [
						{
							fontSize: '1.5em',
							padding: '8px',
							backgroundColor: '#ffffff'
						},
						{
							mediaMaxWidth: '500px',
							fontSize: '1em'
						}
					]
				});

				wcSquarePaymentForm.build();

				// when checkout form is submitted
				$( 'form.checkout' ).on( 'checkout_place_order_square', function( event ) {
					// remove any error messages first
					$( '.payment_method_square .woocommerce-error' ).remove();

					if ( $( '#payment_method_square' ).is( ':checked' ) && $( 'input.square-nonce' ).size() === 0 ) {
						wcSquarePaymentForm.requestCardNonce();

						return false;
					}

					return true;
				});

				$( document.body ).on( 'checkout_error', function() {
					$( 'input.square-nonce' ).remove();
				});
			}
		}
	}; // close namespace
		
	$( document.body ).on( 'updated_checkout', function() {
		// destroy the form and rebuild on each init
		if ( 'object' === $.type( wcSquarePaymentForm ) ) {
			wcSquarePaymentForm.destroy();
		}

		$.wc_square_payments.init();
	});
}( jQuery ) );
