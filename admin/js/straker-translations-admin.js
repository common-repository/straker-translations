(function($) {
	'use strict';

	$(
		function() {
			$( '.st-cart-update img' ).hide();
			$( '.st_success_msg' ).hide();
			$( '.st_error_msg' ).hide();
			// My Jobs Tabs
			$( '.st-tabs .st-tab-links a' ).on(
				'click',
				function(e) {
					var currentAttrValue = $( this ).attr( 'href' );
					// Show/Hide Tabs
					$( '.st-tabs ' + currentAttrValue ).siblings().slideUp( 400 );
					$( '.st-tabs ' + currentAttrValue ).delay( 400 ).slideDown( 400 );
					// Change/remove current tab to active
					$( this ).parent( 'li' ).addClass( 'st-active' ).siblings().removeClass( 'st-active' );
					e.preventDefault();
				}
			);

			$( '#st-cart-btn' ).click(

				function(e){
					e.preventDefault();
					var postID  = document.getElementById( 'stCartPostID' ).value;

					$.ajax(
						{
							url: stCartAjaxObejct.admin_ajax_url,
							data: {
								'action': 'st_translation_cart_ajax',
								'nonce_security': stCartAjaxObejct.st_cart_nonce,
								'postID': postID
							},
							beforeSend: function() {

								$( '#st-cart-btn' ).hide();
								$( '.st-cart-update img' ).show();
								$( '.st-cart-update p' ).hide();

							},
							success: function( response ) {

								if ( response.data.isResponse ) {

									$( '.st-cart-update img' ).hide();
									$( '#st-cart-btn' ).remove();
									$( '.st_success_msg' ).show();

								} else {

									$( '.st-cart-update img' ).hide();
									$( '.st_error_msg' ).show();
									$( '.st-cart-update p' ).show();
									$( '#st-cart-btn' ).show();

								}
							}
						}
					);
				}
			);

			$( "#st-lang-url-err-msg" ).click(
				function() {
					$( '.lang-error' ).css( "display", "block" );
				}
			);

		}
	);

})( jQuery );
