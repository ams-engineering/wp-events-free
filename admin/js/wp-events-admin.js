/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 */

jQuery(document).ready( function($) {
	
	const startDate = document.getElementById('wpevent-start-date');
	const endDate	= document.getElementById('wpevent-end-date');
	const phone		= document.getElementById('wpevent-phone');
	const seats		= document.getElementById('wpevent-seats');

	/**
	 * Ajax call for updating location data in metaboxes
	 * 
	 * @param {string} selectorID 
	 */
	function getLocationData( selectorID ) {
		var postID = $("#post_ID").val();
		$.ajax( {
			type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			dataType: 'json',
			data: { action: 'wpe_update_location',
					locationID: selectorID,
					eventID: postID,
				},
			success: function( response ) {
				var loc = JSON.parse( response );
				$( '#wpevent-venue' ).val( loc.venue );
				$( '#wpevent-address' ).val( loc.address );
				$( '#wpevent-country' ).val( loc.country );
				$( '#wpevent-city' ).val( loc.city );
				$( '#wpevent-state' ).val( loc.state );
				$( '#wpevent-zip' ).val( loc.zip );
				$( '#wpevent-map-url' ).val( loc.map_url );
			},
			error: function (error) {
				var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-error.png';
				wpe_popup('Location not found', imgURL );
			}
		} );
	}

	/**
	 * ajax call for creating new location
	 */
	function saveNewLocation() {
		var locObject 	  = new Object();
		locObject.venue   = $( '#wpevent-venue' ).val();
		locObject.address = $( '#wpevent-address' ).val();
		locObject.country = $( '#wpevent-country :selected' ).text();
		locObject.city    = $( '#wpevent-city' ).val();
		locObject.state   = $( '#wpevent-state' ).val();
		locObject.zip 	  = $( '#wpevent-zip' ).val();
		$.ajax( {
			type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			dataType: 'json',
			data: { action: 'wpe_create_location',
					location: locObject,
				},
			success: function( response ) {
				if ( response === 'Location Already Exists!' || response === 'Please fill all fields!' ) {
					var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-error.png';
					wpe_popup( response, imgURL );
				} else {
					var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-success.png';
					wpe_popup('New Location Added Successfully.', imgURL );
					$( '.wpe-location-fields' ).fadeOut();
					$( '#wpe-location-btn' ).fadeIn();
					$('#wpevent-location').append($('<option>', {
						value: response,
						text: $( '#wpevent-venue' ).val(),
					}));
					$("#wpevent-location").val( response ).change();
				}
			},
			error: function (error) {
				var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-error.png';
				wpe_popup('Location could not be added.', imgURL );
			}
		} );
	}

	//Check validity of metaboxes
	function metaboxValidation( inputArr, e ) {
		inputArr.forEach( function( input ){
			var title	 = '';
			var getTitle = input.getAttribute("title");
			if ( !input.checkValidity() ) {
				if ( input.validationMessage.includes("format") && getTitle != null ) {
					title = getTitle;
					showError( input, input.validationMessage + ' ' + title )
				} else if ( input.validationMessage.includes("fill out") ) {
					for( var i = 0; i < input.labels.length; i++ ) {
						showError( input, input.labels[i].textContent + ' cannot be empty.' )
					}
				} else {
					showError( input, input.validationMessage )
				}
				e.stopImmediatePropagation();             
			}else {
				showSucces( input );
			}
		});
	}

	//Show input error messages
	function showError( input, message ) {
		const eventControl 	   = input.parentElement;
		if ( eventControl.classList.contains( 'success' ) ) {
			eventControl.classList.remove( 'success' );
		}
		eventControl.className = eventControl.className + ' the-error';
		const small			   = eventControl.querySelector('small');
		small.innerText		   = message;
	}

	//show success colour
	function showSucces( input ) {
		const eventControl	   = input.parentElement;
		if ( eventControl.classList.contains( 'the-error' ) ) {
			eventControl.classList.remove( 'the-error' );
		}
		eventControl.className = eventControl.className + ' success';
	}

	$('.wpevent-location').keyup( function( e ) {
		e.preventDefault();
		$('#wpe-no-map').addClass('wpe-hidden');
		$('#wpe-latlng-map').addClass('wpe-hidden');
		$('#wpe-address-map').removeClass('wpe-hidden');
		setTimeout( locationChange, 1000 );
	});

	$('#wpe-location-btn').click( function( e ) {
		e.preventDefault();
		$('.wpe-location-field').val('');
		$("#wpevent-location").val("");
		$( '.wpe-location-fields' ).fadeIn();
		$( this ).fadeOut();
	});

	$('#wpe-save-location').click( function( e ) {
		e.preventDefault();
		saveNewLocation();
	});

	$('#wpevent-location').change( function( e ) {
		e.preventDefault();
		getLocationData( $(this).val() );
	});

	if ( $('body').hasClass('post-type-locations') ) {
		$(window).load(function() {
			var venue 	= $('#wpevent-loc-venue').val();
			var address = $('#wpevent-loc-address').val();
			var city 	= $('#wpevent-loc-city').val();
			var state 	= $('#wpevent-loc-state').val();
			var lat 	= $('#wpevent-latitude').val();
			var lng 	= $('#wpevent-logitude').val();
			if( ( venue === '' && address === '' && city === '' && state === '' ) && ( lat === '' && lng === '' ) ) {
				$('#wpe-address-map').addClass('wpe-hidden');
				$('#wpe-latlng-map').addClass('wpe-hidden');
				$('#wpe-no-map').removeClass('wpe-hidden');
			} else {
				$('#wpe-no-map').addClass('wpe-hidden');
			}
		});

		//Require post title when adding/editing Project Summaries
		$( 'body' ).on( 'submit.edit-post', '#post', function () {

			// If the title isn't set
			if ( $( "#title" ).val().replace( / /g, '' ).length === 0 ) {

				// Show the alert
				wpe_popup( 'A title is required.' );

				// Hide the spinner
				$( '#major-publishing-actions .spinner' ).hide();

				// The buttons get "disabled" added to them on submit. Remove that class.
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );

				// Focus on the title field.
				$( "#title" ).focus();

				return false;
			}
		});
	}


		/**
	 * if the pattern not matches with (123) 123-1224 the publish and update button will be disabled
	 * */
		 if ( $( 'body' ).hasClass( 'post-type-wp_events' ) ) {

			function checkPhoneNumber( input ) {
				let regex = /\(\d\d\d+\)\s\d\d\d-\d\d\d\d+/i;
				return regex.test( input );
			}
			$( document ).ready( function() {
				if ( ! checkPhoneNumber( $( '#wpevent-phone' ).val() ) ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", true );
				}
			});
	
			/**
			 * if the date fields are empty disable the publish button
			 * */
			$( '#wpevent-start-date' ).on( "change paste keyup", function() {
				if ( $( '#wpevent-start-date' ) == '' ) {
					$( '.editor-post-publish-button__button' ).prop("disabled", true );
				}
				else if ( checkPhoneNumber( $( '#wpevent-phone' ).val() ) && $( '#wpevent-start-date' ) != '' && $( '#wpevent-end-date' ) != '' ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", false );
				}
			});
			$( '#wpevent-end-date' ).on( "change paste keyup", function() {
				if ( $( '#wpevent-end-date' ) == '' ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", true );
				}
				else if ( checkPhoneNumber($( '#wpevent-phone' ).val()) && $( '#wpevent-start-date' ) != '' && $( '#wpevent-end-date' ) != '' ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", false );
				}
			});
			$( '#wpevent-phone' ).on( "change paste keyup", function( input ) {
				var startDate = $( '#wpevent-start-date' ).val();
				var endDate	  = $( '#wpevent-end-date' ).val();
				if ( ! checkPhoneNumber( $( '#wpevent-phone' ).val() ) ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", true );
				}else if ( checkPhoneNumber ($( '#wpevent-phone' ).val() ) && startDate != '' && endDate != '' ) {
					$( '.editor-post-publish-button__button' ).prop( "disabled", false );
				}
			});
		 }	

	//on event single page
	if ( $('body').hasClass('post-type-wp_events') ) {
		// on publish button click
		$(document).on( 'click', '.editor-post-publish-button', function(e){

			metaboxValidation( [startDate, endDate, seats, phone], e );

		});

		//disable next/prev controls if corresponding entries don't exist
		$(window).load(function() {
			var linkPrevious = $('#wpe-entry-previous').attr('href');
			var linkNext	 = $('#wpe-entry-next').attr('href');
			if( linkPrevious == '#' ) {
				$('#wpe-entry-previous').addClass('isDisabled');
			}
			if( linkNext == '#' ) {
				$('#wpe-entry-next').addClass('isDisabled');
			}
		});

		/*filter events by type when event status is future, past
		 *or ongoing.
		 */
		$('#post-query-submit').click( function( e ) {
			var param = "event_status=";
			var url   = window.location.href;
			var type  = $('#wp_events_type').val();
			var date  = $('#filter-by-date').val();
			if ( url.indexOf( param ) !== -1 ) {
				e.preventDefault();
				window.location.href = url + '&wp_events_type=' + type + '&m=' + date;
			}
		});
	}

	function wpe_datepicker() {
		$( "#wpevent-start-date" ).datepicker(
			{ dateFormat : 'yy-mm-dd' }
		);

		$( "#wpevent-end-date" ).datepicker(
			{ dateFormat : 'yy-mm-dd' }
		);

		$( "#wpe-filter-start-date" ).datepicker(
			{ dateFormat : 'yy-mm-dd' }
		);

		$( "#wpe-filter-end-date" ).datepicker(
			{ dateFormat : 'yy-mm-dd' }
		);

	}
	 
	// Initialize select2
	$("#wpe_titles").select2();
	$("#wpe_categories").select2();

	/**
	 * Contains all the functions executed on change events of start date,
	 * start time, end date and end time
	 */
	function date_validation() {

	var startDateSelector = '.wpevent-start-date';	
	var endDateSelector	  = '.wpevent-end-date';	
	var startTimeSelector = '.wpevent-start-time';	
	var endTimeSelector	  = '.wpevent-end-time';

	var verifyDateTime = new dateValidation( startDateSelector, endDateSelector, startTimeSelector, endTimeSelector );

	verifyDateTime.changeEndDate();
	verifyDateTime.changeStartDate();
	verifyDateTime.changeEndTime();
	verifyDateTime.changeStartTime();
	
	}

	// on export button click
	$("#wpe_export_events").click( function() {
		$.ajax( {
			type: 'POST',
		    url: wpe_ajaxobject.ajaxurl,
		    dataType: "json", // add data type
		    cache: false,
		    data: { action : 'wp_get_ajax_events',
		    postStatus: $( "#post_status" ).val() },
		    beforeSend: function() {
		       $( "#wpe_export_events" ).attr( "disabled", true );
		    },
		    success: function( response ) {
				window.open( response );
				deleteFile( response );
		    },
		    complete: function() {
		      $( "#wpe_export_events" ).removeAttr( "disabled" );
		    }   
		});

	});

	/**
	 * Event type select change
	 *
	 * Hides location info on the select of webinar
	 * */
	$('#event-type').on('change', function (e) {
		let valueSelected = this.value;
		if( valueSelected === 'webinar' ) {
			$( '.wp-events-location' ).fadeOut();
			$( '.wpe-map-div' ).fadeOut();
			var message = wpe_ajaxobject.webinarMessage;
			$( '#wpevent-confirmation-message' ).text(message);
		} else {
			$( '.wp-events-location' ).fadeIn();
			$( '.wpe-map-div' ).fadeIn();
			var message = wpe_ajaxobject.seminarMessage;
			$( '#wpevent-confirmation-message' ).text(message);
		}
	});

	/**
	 * Mail accordion
	 * */
	jQuery( document ).ready( function () {
		jQuery( ".other-hold" ).click( function () {
			if (jQuery( this ).hasClass( "active" ) ) {
				jQuery( this ).removeClass( "active" );
				jQuery( this ).next().slideUp( 700 );
			} else {
				jQuery( this ).addClass( "active" );
				jQuery( this ).next().slideDown( 700 );
			}
		});


	});

	// on Edit registration button click
	$(document).on('click', '.wpe-edit-registration', function(e){

		e.preventDefault();
		$('.wpe-edit-entry-form').removeClass('disabledform');
		$('#wpe_texting-id').removeAttr('disabled');
		$('#first_name-id').focus();
		$('.wpe-edit-registration').text('Save');
		$('.wpe-edit-registration').addClass('wpe-save-registration');
		$('.wpe-edit-form-button').removeClass('wpe-hidden');
		$('.wpe-edit-registration').removeClass('wpe-edit-registration');
	});

	$('#wpe-edit-entry-form').submit(function (e) {
		e.preventDefault();
	  });

	// on Save registration button click
	$( document ).on('click', '.wpe-save-registration', function(e) {
		e.preventDefault();
		let searchParams  = new URLSearchParams( window.location.search );
		let tab			  = searchParams.get('tab');
		var textingPerm   = $('#wpe_texting-id').prop('checked');
		if ( tab === 'registrations' ) {
			var seats  = $('#wpe_seats-id').val();
			var guests = $('#guests-id').val();
			var count  = guests.split(',').filter( (i) => i.length ).length;
			if( seats > 1 ) {
				if( count > ( seats-1 ) ) {
					wpe_popup('Only ' + ( seats-1 ) + ' guest(s) are allowed.' );
				} else if( count < ( seats-1 ) ) {
					wpe_popup('Please enter complete guest information.');
				} else {
					saveEntry( textingPerm );
				}
			} else {
				$('#guests-id').val('');
				saveEntry( textingPerm );
			}
		} else {
			saveEntry( textingPerm );
		}
	});

	$( document ).on('click', '.wpe-action-icon', function(){
		$(this).parent().parent().next().slideUp( 300 );
		$(this).removeClass('dashicons-arrow-up');
		$(this).addClass('dashicons-arrow-down');
	});

	$( document ).on('click', '.dashicons-arrow-down', function(){
		$(this).parent().parent().next().slideDown( 300 );
		$(this).addClass('dashicons-arrow-up');
		$(this).removeClass('dashicons-arrow-down');
	});

	//ajax call to update form data after entry is edited
	function updateEntry( dataString, textingPerm ){
		$.ajax( {
		  	type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			data: { action: 'wpe_update_entry',
					formData: dataString,
					permissions: textingPerm
				},
			success: function( response ) {
				if ( response != '0000' ) {
					var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-success.png';
					wpe_popup( 'Record Updated Successfully.', imgURL );
				} else {
					var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-error.png';
					wpe_popup('Could not update data!', imgURL );
				}
				$('.wpe-edit-entry-form').addClass('disabledform');
				$('.wpe-save-registration').text('Edit');
				$('.wpe-save-registration').addClass('wpe-edit-registration');
				$('.wpe-save-registration').removeClass('wpe-save-registration');
			},
			error: function (error) {
				var imgURL = wpe_ajaxobject.pluginsUrl + '/wp-events/assets/img/wpe-error.png';
				wpe_popup('Could not update data!', imgURL );
			}
		} );
	}

	// on Resend notification button click
	$( document ).on('click', '#resend-btn', function(e) {
		e.preventDefault();
		var form		 	  = document.getElementById( 'wpe-edit-entry-form' );
		var dataString	  	  = $( form ).serializeJSON();
		var adminNotification = $('#wpe-entry-notification').prop("checked");
		var userNotification  = $('#wpe-entry-notification-user').prop("checked");
		let searchParams 	  = new URLSearchParams( window.location.search );
		let tab			 	  = searchParams.get('tab');
		resendNotification( dataString, adminNotification, userNotification, tab );
	});

	//ajax call to update form data after entry is edited
	function resendNotification( dataString, adminNotification, userNotification, tab ){
		$.ajax( {
		  	type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			data: { action: 'wpe_resend_notification',
					formData: dataString,
					adminNoti: adminNotification,
					userNoti: userNotification,
					displayTab: tab
				},
			success: function( response ) {
				if( response == 1 ) {
					wpe_popup('Notification Resent.');
				} else if ( response == 2 ) {
					wpe_popup('Please select a checkbox.');
				} else {
					wpe_popup('Could Not Process Request.');
				}
			},
			error: function ( error ) {
				wpe_popup('Could Not Process Request');
			}
		} );
	}

	// on Resend notification button click
	$( document ).on('click', '.wpe-to-trash', function(e) {
		e.preventDefault();
		buttonText		 = $(this).text();
		let searchParams = new URLSearchParams( window.location.search );
		let tab			 = searchParams.get('tab');
		let entry		 = searchParams.get('entry');
		trashRestoreButton( buttonText, tab, entry );
	});

	$('#wpe_approve_registrations').change( function() {
		$checkbox = $(this).prop("checked");
		$.ajax( {
			type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			data: { action: 'wpe_update_entry_status',
					checkbox: $checkbox },
			success: function( response ) {
				// alert(response);
				if( response != 1 ) {
					wpe_popup('Could Not Process Request');
				}
			},
			error: function ( error ) {
				wpe_popup('Could Not Process Request');
			}
		} );
	});

	//ajax call to update form data after entry is edited
	function trashRestoreButton( buttonText, tab, entry ){
		$.ajax( {
		  	type: 'post',
			url:  wpe_ajaxobject.ajaxurl,
			data: { text: buttonText,
					entryID: entry,
					displayTab: tab,
					action: 'wpe_trash_restore' },
			success: function( response ) {
				if( response == 1 ) {
					if( buttonText === 'Move To Trash' ) {
						wpe_popup('Entry moved to Trash.');
						$('.wpe-to-trash').text('Restore');
						$('.wpe-entry-status').text('Entry Status: Trash');
					} else {
						wpe_popup('Entry restored.');
						$('.wpe-to-trash').text('Move To Trash');
						if( tab === 'registrations' ) {
							$('.wpe-entry-status').text('Entry Status: Pending Approval');
						} else {
							$('.wpe-entry-status').text('Entry Status: Active');
						}
					}
				}
			},
			error: function ( error ) {
				wpe_popup('Could Not Process Request');
			}
		} );
	}

	function saveEntry( textingPerm ) {
		$( "#wpe-edit-entry-form" ).submit();
		$('#wpe_texting-id').attr('disabled', true);
		var form = document.getElementById( 'wpe-edit-entry-form' );
		var dataString = $( form ).serializeJSON();
		updateEntry( dataString, textingPerm );
	}

	//display popup on current page
	function wpe_popup( message, image = 0 ) {
		$('body').prepend('<div class="wpe-popup"><div class="popup-inner"><span class="close-btn"></span><p>' + message + '</p></div></div>');
		if ( image != 0 ) {
			$('.popup-inner').prepend( '<img src="' + image + '">' );
		}
		setTimeout(function () {
			$('.wpe-popup').fadeOut();
		}, 3000);
	}

	/**
	 * Additional Guests info
	 * */
	$('#wpe_seats-id').on('change', function () {
		var seats = $(this).val();
		if( seats == 1 ) {
			$('.guest-div').removeClass('wpe-show');
			$('.guest-div').addClass('wpe-hidden');
		} else {
			$('.guest-div').addClass('wpe-show');
			$('.guest-div').removeClass('wpe-hidden');
			$('.guest-div small').removeClass('wpe-hidden');
			$('.guest-div small').css('visibility', 'visible');
			$('.guest-div small').css('color', 'black');
			$('.guest-div small').text('Enter ' + ( seats-1 ) + ' comma separated name(s) for guest(s).');
		}
	});

	$('#wpe_subscriber_enable_phone_number').change( function() {
		if ( $( this ).prop("checked") ) {
			$( '#wpe_subscriber_enable_texting_permission' ).removeAttr('disabled');
		} else {
			$( '#wpe_subscriber_enable_texting_permission' ).removeAttr('checked');
			$( '#wpe_subscriber_enable_texting_permission' ).attr("disabled", true);
		}
	});


	/**
	*  Export entries
	*/
	$("#export-event-entries").on( "click", function(e) {
		e.preventDefault();
		var WpeStartDate = $("#wpe-filter-start-date").val();
		var WpeEndDate 	 = $("#wpe-filter-end-date").val();
		var WpeEvent 	 = $("#wpe_titles").val();

		$.ajax( {
			type: 'POST',
		    url: wpe_ajaxobject.ajaxurl,
		    dataType: "json", // add data type
		    cache: false,
		    data: { action : 'wpe_event_entries',
		    Startdate: WpeStartDate,
			Enddate: WpeEndDate,
			wpeeventid: WpeEvent },
		    beforeSend: function() {
		       $( "#export-event-entries" ).attr( "disabled", true );
		    },
		    success: function( response ) {
				window.open( response );
				deleteFile( response );
		    },
		    complete: function() {
		      $( "#export-event-entries" ).removeAttr( "disabled" );
		    }   
		});
		
	});

	/**
	*  Export entries for Subscriptions
	*/
	$("#export-subscription").on("click", function(e) {
		e.preventDefault();

		$.ajax( {
			type: 'POST',
		    url: wpe_ajaxobject.ajaxurl,
		    dataType: "json", // add data type
		    cache: false,
		    data: { action : 'wpe_export_subscription'
		},
		    beforeSend: function() {
		       $( "#export-subscription" ).attr( "disabled", true );
		    },
		    success: function( response ) {
				window.open( response );
				deleteFile( response );
		    },
		    complete: function() {
		      $( "#export-subscription" ).removeAttr( "disabled" );
		    }   
		});
		
	});

	function deleteFile( fileURL ) {
		$.ajax( {
			type: 'POST',
		    url: wpe_ajaxobject.ajaxurl,
		    dataType: "text", // add data type
		    cache: false,
		    data: { action : 'wpe_delete_file',
					url : fileURL },
		    success: function( response ) {
		    },  
		});
	}

	if( window.location.href.includes('tab=export') || window.location.href.includes('tab=import') ) {
		$('#submit').css( 'display', 'none' );
	}

	//function calls
	wpe_datepicker();
	date_validation();
} );