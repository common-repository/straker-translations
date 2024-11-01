(function($) {
	'use strict';
	var stPluginSeetingScript = {
		init: function () {
			/* Settings Langugae Management Tab */
			$( "#lang_mang_bulk_action" ).validate(
				{
					ignore: "",
					rules: {
						action: {
							require_from_group: [2, '.st-lang-spost']
						},
						st_soruce_post : {
							require_from_group: [2, '.st-lang-spost']
						}
					},
					messages: {
						action: {
							require_from_group: 'Please select language and source post. If you just want to change language then select No Soucre Post option from Source Post. <br />',
						},
						st_soruce_post: {
							require_from_group: 'Please select language and source post. If you just want to change language then select No Soucre Post option from Source Post. <br />',
						},
					},
					errorPlacement: function( error, element ) {
						if (error) {
							$( '#errr-msg' ).show();
							$( ".st-error" ).text( error[0].textContent );
						} else {
							$( '#errr-msg' ).hide();
							return true;
						}
					}
				}
			);
			$( "#lang_mang_bulk_action" ).submit( function(e) {

				let st_selected_posts = sessionStorage.getItem('st_lang_manag');
				if ( null !== st_selected_posts && '' !== st_selected_posts ) {
					$("#st_selected_posts").val(st_selected_posts);
					sessionStorage.removeItem('st_lang_manag');
					sessionStorage.clear();
					return true;
				} else {
					$( ".st-error" ).text( "Please select at least one post." );
					return false;
				}
			});

			// Language Management Bulk Filter
			$( "#lang_mang_bulk_filter_action" ).validate(
				{
					rules: {
						ignore: "",
						'.filters_lang_type': {
							required: true,
							minlength: 1
						},
						st_post_type_filter:
						{
							require_from_group: [1, '.filters_lang_type']
						},
						st_lang_filter:
						{
							require_from_group: [1, '.filters_lang_type']
						},
						groups:
						{
							checks: "st_post_type_filter st_lang_filter"
						},

					},
					messages: {
						st_post_type_filter:{
							require_from_group: "Please select filter by post types or language.<br />",
						},
						st_lang_filter:{
							require_from_group: "Please select filter by post types or language.<br />",
						},
					},
					errorPlacement: function( error, element ) {
						if (error) {
							$( '#errr-msg' ).show();
							$( ".st-error" ).text( error[0].textContent );

						} else {
							$( '#errr-msg' ).hide();
							return true;
						}
					}
				}
			);
			// URL Setttings Validate
			$( "#url_settings" ).validate(
				{
					ignore: "",
					rules: {
						'url[]': {
							required: function() {
								return $( '#rewrite_type_domain:checked' ).val() == 'domain';
							}
						}
					},
					errorPlacement: function(error, element) {
						if (error) {
							$( '#errr-msg' ).css( "display", "block" );
							$( '.st-error' ).css( "display", "block" );
						} else {
							return true;
						}
					}
				}
			);
			// Language Setttings Validate
			$( "#language_settings" ).validate(
				{
					ignore: "",
					rules: {
						sl: {
							required: true
						},
						'tl[]': {
							required: true,
							minlength: 1
						}
					},
					errorPlacement: function(error, element) {
						if (error) {
							$( '#errr-msg' ).css( "display", "block" );
							$( '.st-error' ).css( "display", "block" );
							$( window ).scrollTop( 0 );
						} else {
							return true;
						}
					}
				}
			);
			$( "#rewrite_type_code" ).click(
				function() {
					var re_type = $( this ).val();
					if ($( this ).is( ":checked" ) && re_type == "code") {
						$( ".st-tl-lang" ).attr( "hidden", true );
						$( ".st-url-structure" ).removeAttr( "hidden", true );
					}
				}
			);

			$( "#rewrite_type_domain" ).click(
				function() {
					var re_type = $( this ).val();
					if ($( this ).is( ":checked" ) && re_type == "domain") {
						$( ".st-tl-lang" ).removeAttr( "hidden" );
						$( ".st-url-structure" ).attr( "hidden", true );
					}
				}
			);

			$( "#rewrite_type_none" ).click(
				function() {
					var re_type = $( this ).val();
					if ($( this ).is( ":checked" ) && re_type == "none") {
						$( ".st-tl-lang" ).attr( "hidden", true );
						$( ".st-url-structure" ).attr( "hidden", true );
					}
				}
			);
			// ShortCode Settings
			$( "#st_shortcode_settings" ).validate(
				{
					ignore: "",
					rules: {
						'tl[]': {
							required: true,
							minlength: 1
						},
						'.display_flag_lang': {
							required: true,
							minlength: 1
						},
						display_flags:
						{
							require_from_group: [1, '.display_flag_lang']
						},
						display_langs:
						{
							require_from_group: [1, '.display_flag_lang']
						},
					},
					groups:
					{
						checks: "display_flags display_langs"
					},
					messages: {
						'tl[]': {
							required: "Please select at least one language.<br />",
						},
						'.display_flag_lang':{
							required: "Please select display flag or display language.<br />",
						},
						display_langs:{
							require_from_group: "Please select display flag or display language.<br />",
						},
					},
					errorPlacement: function(error, element) {
						error.appendTo( '#tagline-description' );
					}
				}
			);

			// Language Management
			$( ":checkbox.st-lang-manag" ).on( "change",
				function(e) {

					// let st_lang_manag = $("#st_selected_posts");
					let st_lang_manag = sessionStorage.getItem('st_lang_manag');
					let selected_posts = [];

					if ( null !== st_lang_manag && '' !== st_lang_manag ) {
						selected_posts = st_lang_manag.split(',');
					} else {
						sessionStorage.removeItem('st_lang_manag');
					}
					if( 'on' === e.target.value ) {

						$( ":checkbox.st-lang-manag" ).each(
							function(i,v) {

								if( true === v.checked ) {
									if( ! selected_posts.includes(v.value) && "on" != v.value ) {
										selected_posts.push(v.value);
									}
								} else 	if( false === v.checked ){
									let index = selected_posts.indexOf(v.value);

									if( index > -1 ) {
										selected_posts.splice(index , 1);
									}
								}
							}
						);

					} else {

						if( true === e.target.checked ) {
							if( ! selected_posts.includes(e.target.value) ) {
								selected_posts.push(e.target.value);
							}
						} else 	if( false === e.target.checked ){
							let index = selected_posts.indexOf( e.target.value );
							if( index > -1 ) {
								selected_posts.splice(index , 1);
							}
						}
					}

					sessionStorage.setItem('st_lang_manag', selected_posts.join());
					// $("#st_selected_posts").val(selected_posts.join());
				}
			);

			// Source Language DropDown
			$( '#langDropdown' ).ddslick(
				{
					width: 300,
					imagePosition: "left",
					onSelected: function(data) {
						if (data.selectedIndex > 0) {
							$( '#sl' ).val( data.selectedData.value );
						} else {
							$( '#sl' ).val( "" );
						}
					}
				}
			);
			// Copy ShortCode from ShortCode Settings Tab
			var clipboard = new Clipboard( '.st-cb-cp' );
			clipboard.on(
				'success',
				function(e) {
					$( "#st-copied" ).show().delay( 500 ).fadeOut();
				}
			);

			clipboard.on(
				'error',
				function(e) {
					$( '#st-shortcode' ).addClass( 'selectText' );
					$( "#st-copied" ).show().delay( 2500 ).fadeOut();
					$( "#st-copied" ).html( 'Press Ctrl/Cmd+C to copy' );
				}
			);

			let st_lang_manag_ss = sessionStorage.getItem('st_lang_manag');
			let st_selected_posts = [];
			if ( null !== st_lang_manag_ss && '' !== st_lang_manag_ss ) {
				st_selected_posts = st_lang_manag_ss.split(',');
				st_selected_posts.forEach(
					function(element) {
						$( "#st-lang-manag-" + element ).prop( 'checked', true );
					}
				);
			}
		},
	};
	stPluginSeetingScript.init();
})( jQuery );
