// Mapbox js 

(function(mapbox_map) {
	
	var map = {}; 
    var markers =[];
	mapbox_map.setMarkerLayer= function(url){


		L.mapbox.markerLayer().loadURL(url).addTo(this.map);

	};

	mapbox_map.init= function(lat,lon){


		this.map =  L.mapbox.map('map', 'sdh100shaun.map-k4g2kpbm')
    	.setView([lat, lon], 14);

	};

    mapbox_map.setZoom = function(a)
    {
        this.map.setZoom(a)
    }
	mapbox_map.center = function(lat,lon)
	{
		this.map.panTo([lat,lon]);
	}

    mapbox_map.addMarker=function(lat,lon)
    {
        
       var marker =  L.mapbox.featureLayer({
           // this feature is in the GeoJSON format: see geojson.org
           // for the full specification
           type: 'Feature',
           geometry: {
               type: 'Point',
               // coordinates here are in longitude, latitude order because
               // x, y is the standard for GeoJSON and many formats
               coordinates: [lon,lat]
           },
           properties: {
               'marker-size': 'large',
               'marker-color': '#296410', 
               'title':'JH - 34 Stoney Street Nottingham NG1 1NB'
           }
       });

        markers.push(marker);
        marker.addTo(this.map);

     this.map.show
        this.map.scrollWheelZoom.disable();
     this.map.panTo([lat,lon]);
        marker.on('ready', function() {
            // markerLayer.getBounds() returns the corners of the furthest-out markers,
            // and map.fitBounds() makes sure that the map contains these.
            map.fitBounds(marker.getBounds());
        });

     return marker;
    }

    mapbox_map.removeMarkers = function (m){

       for(var m in markers)
       {
          var mk = markers[m];
          mk.clearLayers();
       }
    }


    mapbox_map.getMarkers = function (){

        return markers;
    }

    mapbox_map.setGeoJson = function(geojson,map)
    {
        
        map.featureLayer.setGeoJSON(geojson);
        map.featureLayer.on('click', function(e) {
            e.layer.unbindPopup();
            window.location.href = e.layer.feature.properties.url;
        });
    }

    mapbox_map.openPopUpsByDefault = function(map)
    {
      foreach(marker in map.markers) 
      {
        marker.eachLayer(function(m) {
        m.openPopup();
      });
      }
    }

}(window.mapbox_map = window.mapbox_map || {}));

(function (phpminds, $, undefined) {

   phpminds.scroller = function(){

       $("document").ready(function(){
           $('a[href^="#"]').on('click', function(event) {
               var target = $(this.hash);
               if( target.length ) {
                   event.preventDefault();
                   $('html, body').animate({
                       scrollTop: (target.offset().top -35)
                   }, 1000);
               }
           });
       });
   };

})(window.phpminds = window.phpminds || {}, jQuery);


