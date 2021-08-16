var mmap, exml, sidebar;
var geoXml = null;
var geoXmlDoc = null;
var currentid = null;
var lastOpen = '';
var lastOpenS = '';
var lastOpenO = '';
var myGeoXml3Zoom = true;

$(document).ready(function(e){
	sidebar = $('.obra-lista');
	iniciarApp();
});

function iniciarApp(){
	$('.abrir-year').click(function(){
		var y = $(this).data("open");
		if(lastOpen == y){
			$('.a-'+y).removeClass("open");
			lastOpen = '';
		}else{
			$('.obras').removeClass("open");
			$('.a-'+y).addClass("open");
			lastOpen = y;
		}
		$('.obras .obras-contenido').slideUp();
		$('.obras.open .obras-contenido').slideDown();

	});
	$('.abrir-seccion').click(function(){
		var seccion = $(this).data("open");
		if(lastOpenS == seccion){
			$('.os-'+seccion).removeClass("open");
			lastOpenS = '';
		}else{
			$('.obras-seccion').removeClass("open");
			$('.os-'+seccion).addClass("open");
			lastOpenS = seccion;
		}
		$('.obras-seccion .obras-seccion-contenido').slideUp();
		$('.obras-seccion.open .obras-seccion-contenido').slideDown();

	});
	$('.abrir-obra').click(function(){
		var id = $(this).data("open");
		var archivo = $(this).data("archivo");
		$('.obra').removeClass("open");
		$('.ow-'+id).addClass("open");
		lastOpenO = id;
		cargarMapa(id,archivo);
	});
	
	$(".obras-search-btn").click(function(){
		var id = $(this).data("id");
		var busqueda = $('.obras-search-input.s-'+id).val();
		var filtrados = $(".obra-lista.ol-"+id+" tr").filter(function () {
			var reg = new RegExp(busqueda, "ig");
			return reg.test($(this).text())
		});
		$( ".obra-lista.ol-"+id+" tr" ).addClass("ocultar");
		filtrados.removeClass("ocultar");
	});

	$(".obras-clear-btn").click(function(){
		var id = $(this).data("id");
		$(".obra-lista.ol-"+id+" tr").removeClass("ocultar");
		$('.obras-search-input.s-'+id).val("");
	});

	$('.obras-search-input').keyup(function(e){
		var code = e.key;
		if(code==="Enter"){
			e.preventDefault();
			var id = $(this).data("id");
			document.getElementById("btns-"+id).click();
		}
	});

	mmap = new google.maps.Map(document.getElementById("mapa"), {
		center: { lat: 28.6709132, lng: -106.1346581 },
		zoom: 8,
	});
}

function cargarMapa(id,mapa){
	currentid = id;
	mmap.innerHTML = '';
	sidebar.html('');
	mmap=new google.maps.Map(document.getElementById("mapa"), {
		center: { lat: 0, lng: 0 },
		zoom: 8,
	});

	geoXml = new geoXML3.parser({
		map: mmap,
		zoom: myGeoXml3Zoom,
		singleInfoWindow: true,
		afterParse: useTheData,
		failedParse: geoxmlErrorHandler
	});
	geoXml.parse(DMGConfig.urlcargas+"obras/obj"+id+"/"+mapa);

	makeSidebar();
	// google.maps.event.addListener(mmap, "bounds_changed", makeSidebar);
	// google.maps.event.addListener(mmap, "center_changed", makeSidebar);
	// google.maps.event.addListener(mmap, "zoom_changed", makeSidebar);
}

function geoxmlErrorHandler(doc){
	alert("GEOXML3: failed parse");
}

function makeSidebarPolygonEntry(i,j) {
	if (geoXml && geoXml.docs && geoXml.docs[j] && geoXml.docs[j].placemarks
			&& geoXml.docs[j].placemarks[i] && geoXml.docs[j].placemarks[i].name) {
	var name = geoXml.docs[j].placemarks[i].name;
		if (!name  || (name.length == 0)) name = "polygon #"+i;
		// alert(name);
		sidebarHtml += '<tr id="row'+i+'_'+j+'"><td onmouseover="kmlHighlightPoly('+i+','+j+');" onmouseout="kmlUnHighlightPoly('+i+','+j+');"><a href="javascript:kmlPgClick('+i+','+j+');">'+name+'</a></td></tr>';
		//  - <a href="javascript:kmlShowPlacemark('+i+','+j+');">Destacar</a>';
	}
}

function makeSidebarEntry(i,j) {
	if (geoXml && geoXml.docs && geoXml.docs[j] && geoXml.docs[j].placemarks
		   && geoXml.docs[j].placemarks[i] && geoXml.docs[j].placemarks[i].name) {
	var name = geoXmlDoc[j].placemarks[i].name;
	 if (!name  || (name.length == 0)) name = "marker #"+i;
	 // alert(name);
	 sidebarHtml += '<tr id="row'+i+'_'+j+'"><td><a href="javascript:kmlClick('+i+','+j+');">'+name+'</a></td></tr>';
	}
}

function makeSidebar() {
	$('.obras-search-input.s-'+currentid).val("");
	sidebarHtml = '<table>';
	// <tr><td><a href="javascript:showAll();">Mostrar todos los puntos</a></td></tr>
	var currentBounds = mmap.getBounds();
	// if bounds not yet available, just use the empty bounds object;
	if (!currentBounds) currentBounds=new google.maps.LatLngBounds();
	if (geoXmlDoc) {
		for (var j=0; j<geoXmlDoc.length; j++) {
			for (var i=0; i<geoXmlDoc[j].placemarks.length; i++) {
				if (geoXmlDoc[j].placemarks[i].polygon) {
					if (currentBounds.intersects(geoXmlDoc[j].placemarks[i].polygon.bounds)) {
					makeSidebarPolygonEntry(i,j);
					}
				}
				if (geoXmlDoc[j].placemarks[i].polyline) {
					if (currentBounds.intersects(geoXmlDoc[j].placemarks[i].polyline.bounds)) {
					makeSidebarPolylineEntry(i,j);
					}
				}
				if (geoXmlDoc[j].placemarks[i].marker) {
					if (currentBounds.contains(geoXmlDoc[j].placemarks[i].marker.getPosition())) {
					makeSidebarEntry(i,j);
					}
				}
			}
		}
	}
	sidebarHtml += "</table>";
	document.getElementById("o-"+currentid).innerHTML = sidebarHtml;
}

function useTheData(doc){
	var currentBounds = mmap.getBounds();
	if (!currentBounds) currentBounds=new google.maps.LatLngBounds();
	sidebarHtml = '<table><tr><td><a href="javascript:showAll();">Mostrar todos los puntos</a></td></tr>';
	geoXmlDoc = doc;
	for (var j = 0; j<geoXmlDoc.length;j++) {
	if (!geoXmlDoc[j] || !geoXmlDoc[j].placemarks || !geoXmlDoc[j].placemarks.length)
	continue;
	for (var i = 0; i < geoXmlDoc[j].placemarks.length; i++) {

	var placemark = geoXmlDoc[j].placemarks[i];
	if (placemark.polygon) {
		if (currentBounds.intersects(placemark.polygon.bounds)) {
		makeSidebarPolygonEntry(i,j);
		}
		var kmlStrokeColor = kmlColor(placemark.style.color);
		var kmlFillColor = kmlColor(placemark.style.fillcolor);
		var normalStyle = {
			strokeColor: kmlStrokeColor.color,
			strokeWeight: placemark.style.width,
			strokeOpacity: kmlStrokeColor.opacity,
			fillColor: kmlFillColor.color,
			fillOpacity: kmlFillColor.opacity
			};
		placemark.polygon.normalStyle = normalStyle;

		highlightPoly(placemark.polygon, i, j);
	}
	if (placemark.polyline) {
		if (currentBounds.intersects(placemark.polyline.bounds)) {
			makeSidebarPolylineEntry(i,j);
		}
		var kmlStrokeColor = kmlColor(placemark.style.color);
		var normalStyle = {
			strokeColor: kmlStrokeColor.color,
			strokeWeight: placemark.style.width,
			strokeOpacity: kmlStrokeColor.opacity
			};
		placemark.polyline.normalStyle = normalStyle;

		highlightPoly(placemark.polyline, i, j);
	}
	if (placemark.marker) {
		if (currentBounds.contains(placemark.marker.getPosition())) {
			makeSidebarEntry(i,j);
		}
	}

/*    doc[0].markers[i].setVisible(false); */
	}
	}  
	sidebarHtml += "</table>";
	document.getElementById("o-"+currentid).innerHTML = sidebarHtml;
};

function kmlPgClick(pm,doc) {
	if (geoXmlDoc[doc].placemarks[pm].polygon.getMap()) {
	   google.maps.event.trigger(geoXmlDoc[doc].placemarks[pm].polygon,"click", {vertex: 0});
	} else {
	   geoXmlDoc[doc].placemarks[pm].polygon.setMap(mmap);
	   google.maps.event.trigger(geoXmlDoc[doc].placemarks[pm].polygon,"click", {vertex: 0});
	}
 }
 function kmlPlClick(pm,doc) {
	if (geoXmlDoc[doc].placemarks[pm].polyline.getMap()) {
	   google.maps.event.trigger(geoXmlDoc[doc].placemarks[pm].polyline,"click", {vertex: 0});
	} else {
	   geoXmlDoc[doc].placemarks[pm].polyline.setMap(mmap);
	   google.maps.event.trigger(geoXmlDoc[doc].placemarks[pm].polyline,"click", {vertex: 0});
	}
 }
 function kmlClick(pm,docNum) {
	if (geoXmlDoc[docNum].placemarks[pm].marker.getMap()) {
	   google.maps.event.trigger(geoXmlDoc[docNum].placemarks[pm].marker,"click");
	} else {
	   geoXmlDoc[docNum].placemarks[pm].marker.setMap(mmap);
	   google.maps.event.trigger(geoXmlDoc[docNum].placemarks[pm].marker,"click");
	}
 }
 function kmlShowPlacemark(pm,doc) {
   if (geoXmlDoc[doc].placemarks[pm].polygon) {
	 mmap.fitBounds(geoXmlDoc[doc].placemarks[pm].polygon.bounds);
   } else if (geoXmlDoc[doc].placemarks[pm].polyline) {
	 mmap.fitBounds(geoXmlDoc[doc].placemarks[pm].polyline.bounds);
   } else if (geoXmlDoc[doc].placemarks[pm].marker) {
	 mmap.setCenter(geoXmlDoc[doc].placemarks[pm].marker.getPosition());
   } 
   for (var j=0; j< geoXmlDoc.length;j++) {
	for (var i=0;i<geoXmlDoc[j].placemarks.length;i++) {
	  var placemark = geoXmlDoc[j].placemarks[i];
	  if (i == pm) {
		if (placemark.polygon) placemark.polygon.setMap(mmap);
		if (placemark.polyline) placemark.polyline.setMap(mmap);
		if (placemark.marker) placemark.marker.setMap(mmap);
	  } else {
		if (placemark.polygon) placemark.polygon.setMap(null);
		if (placemark.polyline) placemark.polyline.setMap(null);
		if (placemark.marker) placemark.marker.setMap(null);
	  }
	}
  }
 }

function kmlColor (kmlIn) {
	var kmlColor = {};
	if (kmlIn) {
		aa = kmlIn.substr(0,2);
		bb = kmlIn.substr(2,2);
		gg = kmlIn.substr(4,2);
		rr = kmlIn.substr(6,2);
		kmlColor.color = "#" + rr + gg + bb;
		kmlColor.opacity = parseInt(aa,16)/256;
	} else {
		// defaults
		kmlColor.color = randomColor();
		kmlColor.opacity = 0.45;
	}
	return kmlColor;
}

function randomColor(){ 
	var color="#";
	var colorNum = Math.random()*8388607.0;  // 8388607 = Math.pow(2,23)-1
	var colorStr = colorNum.toString(16);
	color += colorStr.substring(0,colorStr.indexOf('.'));
	return color;
};

var highlightOptions = {fillColor: "#FFFF00", strokeColor: "#000000", fillOpacity: 0.9, strokeWidth: 10};
var highlightLineOptions = {strokeColor: "#FFFF00", strokeWidth: 10};

function showAll() {
	var bounds = null;
	for (var i=0; i<geoXmlDoc.length; i++) {
	  if (i ==0) {
	   bounds = geoXmlDoc[i].internals.bounds;
	  } else {
	   bounds.union(geoXmlDoc[i].internals.bounds);
	 }
	}
	mmap.fitBounds(bounds);
  for (var j=0;j<geoXmlDoc.length;j++) {
	for (var i=0;i<geoXmlDoc[j].placemarks.length;i++) {
	  var placemark = geoXmlDoc[j].placemarks[i];
	  if (placemark.polygon) placemark.polygon.setMap(mmap);
	  if (placemark.polyline) placemark.polyline.setMap(mmap);
	  if (placemark.marker) placemark.marker.setMap(mmap);
	}
	}
}

function highlightPoly(poly, polynum, doc) {
	//    poly.setOptions({fillColor: "#0000FF", strokeColor: "#0000FF", fillOpacity: 0.3}) ;
	google.maps.event.addListener(poly,"mouseover",function() {
	  var rowElem = document.getElementById('row'+polynum+'_'+doc);
	  if (rowElem) rowElem.style.backgroundColor = "#FFFA5E";
	  if (poly instanceof google.maps.Polygon) {
		poly.setOptions(highlightOptions);
	  } else if (poly instanceof google.maps.Polyline) {
		poly.setOptions(highlightLineOptions);
	  }
	});
	google.maps.event.addListener(poly,"mouseout",function() {
	  var rowElem = document.getElementById('row'+polynum+'_'+doc);
	  if (rowElem) rowElem.style.backgroundColor = "#FFFFFF";
	  poly.setOptions(poly.normalStyle);
	});
} 

function kmlHighlightPoly(pm, doc) {
 for (var j=0; j<geoXmlDoc.length;j++) {
   for (var i=0;i<geoXmlDoc[j].placemarks.length;i++) {
     var placemark = geoXmlDoc[j].placemarks[i];
     if (i == pm && j == doc) {
       if (placemark.polygon) placemark.polygon.setOptions(highlightOptions);
       if (placemark.polyline) placemark.polyline.setOptions(highlightLineOptions);
     } else {
       if (placemark.polygon) placemark.polygon.setOptions(placemark.polygon.normalStyle);
       if (placemark.polyline) placemark.polyline.setOptions(placemark.polyline.normalStyle);
     }
   }
 }
}
function kmlUnHighlightPoly(pm, doc) {
 for (var j=0; j<geoXmlDoc.length; j++) {
   for (var i=0;i<geoXmlDoc[j].placemarks.length;i++) {
     if (i == pm && j == doc) {
       var placemark = geoXmlDoc[j].placemarks[i];
       if (placemark.polygon) placemark.polygon.setOptions(placemark.polygon.normalStyle);
       if (placemark.polyline) placemark.polyline.setOptions(placemark.polyline.normalStyle);
     }
   }
 }
}