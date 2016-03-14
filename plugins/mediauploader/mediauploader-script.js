/**
* IFFD Media Uploader Sctipt
*/

(function( $ ) {
	$( window ).load(function() {

		// Parse files ids from page
		var filesIds = ( '' !== $( '#iffd-mediauploader-files-ids' ).val() ) ?
			filesIds = $( '#iffd-mediauploader-files-ids' ).val().split( ',' ) :
			[];

		// Parse multi select parameter
		var multipleSelect = ( 0 === $( '#iffd-mediauploader' ).data( 'multiple-select' ) ) ?
			false :
			true;

		// Show add button
		if ( 1 > filesIds.length || true === multipleSelect ) {
			$( '#iffd-mediauploder-btn-wrapper' ).show();
		}

		// Add files
		$( '#iffd-mediauploader-btn' ).click(function( e ) {

			e.preventDefault();

			var uploaderBtn = $( this );
			var mimeType = $( '#iffd-mediauploader' ).data( 'mime-type' );

			// Call wp.media and add files
			var uploader = wp.media({
				className: 'media-frame ignet-media-frame',
				title: 'Загрузка медиафайлов',
				multiple: multipleSelect,

				library: {
					type: mimeType,
				},
				button: {
					text: 'Добавить',
				},
			}).on( 'select', function() {
				addFiles( uploader.state().get( 'selection' ) );
			}).open();
			
		});

		// Remove file
		$( '.iffd-mediauploader-file-remove-btn' ).live( 'click', function() {
			removeFile( this );
		});

		/*
		* Add files
		*/
		function addFiles( files ) {

			// Get files ids from wp.media
			files.each(function( file ) {
				fileProperties = file.toJSON();

				if ( -1 === jQuery.inArray( jQuery.trim( fileProperties.id ), filesIds ) ) {

					// Get file image url
					var fileImageUrl;

					// Get thumbnail  or file icon
					if ( 'image' === fileProperties.type ) {
						if ( fileProperties.sizes.thumbnail ) {
							fileImageUrl = fileProperties.sizes.thumbnail.url;
						} else {
							fileImageUrl = fileProperties.sizes.full.url;
						}
					} else {
						fileImageUrl = fileProperties.icon;	
					}

					// Create file view
					var fileWrapper = $( '<div/>', {
						'data-file-id': fileProperties.id,
						'class': 'iffd-mediauploader-files-wrapper',
						style: 'background-image: url(' + fileImageUrl + ')',
					});

					// Add file name
					var fileName = $( '<span/>', {
						'class': 'iffd-mediauploader-caption',
						text: fileProperties.title,
					});					

					// Add file delete button
					var fileDeleteBtn = $( '<a/>', {
						'class': 'iffd-mediauploader-file-remove-btn',
						text: '×',
					});

					// Set to page
					fileWrapper.append( fileName );
					fileWrapper.append( fileDeleteBtn );
					$( '#iffd-mediauploader' ).prepend( fileWrapper );

					// Add new file id to array
					filesIds.push( fileProperties.id.toString() );
				}
			});

			// Add files ids to hidden field
			$( '#iffd-mediauploader-files-ids' ).val( filesIds.join( ',' ) );

			// Hide add button
			if ( 0 < filesIds.length && false === multipleSelect ) {
				$( '#iffd-mediauploder-btn-wrapper' ).hide();
			}
		}

		/*
		* Remove file
		*/
		function removeFile( removeBtn ) {

			// Get file id
			var fileId = $( removeBtn ).parent().data( 'file-id' );
			
			// Remove file id and wrapper
			filesIds.splice( filesIds.indexOf( fileId.toString() ), 1 );
			$( removeBtn ).parent().remove();

			// Compile and set other ids
			$( '#iffd-mediauploader-files-ids' ).val( filesIds.join( ',' ) );

			// Show add button
			if ( filesIds.length < 1 && false === multipleSelect ) {
				$( '#iffd-mediauploder-btn-wrapper' ).show();
			}
		}
		
	});
})( jQuery );
