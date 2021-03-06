mxn.addProxyMethods( mxn.Mapstraction, [ 'enableGeoMashupExtras' ]);

mxn.register( 'googlev3', {
	Mapstraction: {
		enableGeoMashupExtras: function() {
			var me = this;
			me.markerAdded.addHandler( function( name, source, args ) {
				if ( args.marker.draggable ) {
					// add marker dragend event
					args.marker.dragend = new mxn.Event( 'dragend', args.marker );
					google.maps.event.addListener( args.marker.proprietary_marker, 'dragend', function( mouse ) {
						args.marker.dragend.fire( { location: new mxn.LatLonPoint( mouse.latLng.lat(), mouse.latLng.lng() ) } );
					});
				}
			});
		}
	}
});