<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Virtual Tour");
?>
<?
	if($_GET["tour"] != 'adc'){
		$src = 'http://ros.t198.topaz.fastwebserver.de/aero/aero/aero_out.html';
		?>
		<section class="container_tur">

			<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<title>&#1057;&#1094;&#1077;&#1085;&#1072; 1</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="apple-mobile-web-app-status-bar-style" content="black" />

			<script type="text/javascript" src="swfobject.js">
			</script>
			<script type="text/javascript">
				function hideUrlBar() {
					// hide URL field on the iPhone/iPod touch
					var p = String(navigator.platform);
					container = document.getElementById("container");
					if( p === 'iPad' || p === 'iPhone' || p === 'iPod touch' ){
						var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
						if (parseInt(v[1], 10)>=7) {
							// iOS >=7
							if (container) {
								container.style.top=(0) + "px";
								container.style.left=(0) + "px";
								container.style.width=(window.innerWidth) + "px";
								container.style.height=(window.innerHeight) + "px";
								if (pano) {
									pano.setViewerSize(window.innerWidth, window.innerHeight);
								}						}
							window.scrollTo(0, 0);
						} else {
							if (container) {
								var cheight;
								switch(window.innerHeight) {
									case 208:cheight=268; break; // landscape
									case 260:cheight=320; break; // landscape, fullscreen
									case 336:cheight=396; break; // portrait, in call status bar
									case 356:cheight=416; break; // portrait
									case 424:cheight=484; break; // portrait iPhone5, in call status bar
									case 444:cheight=504; break; // portrait iPhone5
									default: cheight=window.innerHeight;
								}
								if ((cheight) && ((container.offsetHeight!=cheight) || (window.innerHeight!=cheight))) {
									container.style.height=cheight + "px";
								}
							}
							document.getElementsByTagName("section")[0].style.marginTop="1px";
							window.scrollTo(0, 1);
						}
					}
				}
				if (window.addEventListener) {
					window.addEventListener("load", hideUrlBar);
					window.addEventListener("resize", hideUrlBar);
					window.addEventListener("orientationchange", hideUrlBar);
				}
			</script>
			<style type="text/css" title="Default">
				body, div, h1, h2, h3, span, p {
					font-family: Verdana,Arial,Helvetica,sans-serif;
					color: #000000;
				}
				/* fullscreen */
				html {
					height:100%;
				}
				body {
					/*height:100%;
					margin: 0px;
					overflow:hidden; *//* disable scrollbars */
				}
				body {
					font-size: 10pt;
					/*background : #ffffff;*/
				}
				table,tr,td {
					font-size: 10pt;
					border-color : #777777;
					background : #dddddd;
					color: #000000;
					border-style : solid;
					border-width : 2px;
					padding: 5px;
					border-collapse:collapse;
				}
				h1 {
					font-size: 18pt;
				}
				h2 {
					font-size: 14pt;
				}
				.warning {
					font-weight: bold;
				}
				/* fix for scroll bars on webkit & Mac OS X Lion */
				::-webkit-scrollbar {
					background-color: rgba(0,0,0,0.5);
					width: 0.75em;
				}
				::-webkit-scrollbar-thumb {
					background-color:  rgba(255,255,255,0.5);
				}
			</style>
			<script type="text/javascript" src="pano2vr_player.js">
			</script>
			<script type="text/javascript" src="skin.js">
			</script>
			<div id="container" style="width:100%;height:100%;">
				This content requires HTML5/CSS3, WebGL, or Adobe Flash Player Version 9 or higher.
			</div>
			<script type="text/javascript">
				// check for CSS3 3D transformations and WebGL
				if (ggHasHtml5Css3D() || ggHasWebGL()) {
					// use HTML5 panorama
					// create the panorama player with the container
					pano=new pano2vrPlayer("container");
					// add the skin object
					skin=new pano2vrSkin(pano);
					// load the configuration
					pano.readConfigUrl("aero_out.xml");
					// hide the URL bar on the iPhone
					setTimeout(function() { hideUrlBar(); }, 10);
					window.addEventListener("load", function() { initMap(); });
				} else
				if (swfobject.hasFlashPlayerVersion("10.0.0")) {
					var flashvars = {};
					var params = {};
					function ggXmlReady() { initMap(); }
					// enable javascript interface
					flashvars.externalinterface="1";
					params.quality = "high";
					params.bgcolor = "#ffffff";
					params.allowscriptaccess = "sameDomain";
					params.allowfullscreen = "true";
					var attributes = {};
					attributes.id = "pano";
					attributes.name = "pano";
					attributes.align = "middle";
					flashvars.skinxml="skin.xml";
					flashvars.panoxml="aero_out.xml";
					params.base=".";
					swfobject.embedSWF(
						"pano2vr_player.swf", "container",
						"100%", "100%",
						"9.0.0", "",
						flashvars, params, attributes);
				}
			</script>
			<noscript>
				<p><b>Please enable Javascript!</b></p>
			</noscript>
			<hr>
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
			<!--<div id="mapdiv" style="width:700px; height:500px">map goes here!</div>-->

			<script type="text/javascript">
				var lastFov = -1;
				var lastPan = -1;
				var lastZoom = -1;
				var radar=null;
				var activeNodeLatLng;
				var map;
				function updateRadar()
				{
					if ((!activeNodeLatLng) || (!pano) || (!map)) return;
					var d2r = Math.PI/180 ;
					var r2d = 180/Math.PI ;
					var fov = pano.getFov();
					var pan = pano.getPanNorth();
					var zoom = map.getZoom();
					var gps=pano.getNodeLatLng("");
					if ((gps.length>=2) && ((gps[0]!=0) || (gps[1]!=0))) {
						if (zoom<6) zoom = 6; // avoid large radar beams on world map
						if ((fov==lastFov) && (pan==lastPan) && (zoom==lastZoom) && (gps[0]==activeNodeLatLng.lat()) && (gps[1]==activeNodeLatLng.lng())) return; // nothing to do
						lastPan=pan;
						lastFov=fov;
						lastZoom=zoom;
						activeNodeLatLng = new google.maps.LatLng(gps[0], gps[1]);
						var rLat = 4.0*r2d / Math.pow(2,zoom);     // beam size
						var rLng = rLat/Math.cos(activeNodeLatLng.lat() * d2r);
						// generate radar fan for fov
						var radar_poly = new Array();
						radar_poly.push(activeNodeLatLng);
						var segments=5;
						for (i=-segments; i<=segments; i++) {
							var angle = (fov / (2*segments)) * i;
							var x = -rLng * Math.sin((pan+angle)*d2r) + activeNodeLatLng.lng();
							var y =  rLat * Math.cos((pan+angle)*d2r) + activeNodeLatLng.lat();
							radar_poly.push(new google.maps.LatLng(y, x));
						}
						// clear old radar
						if (radar) {
							radar.setMap(null);
							radar = null;
						}
						// show radar
						radar = new google.maps.Polygon({
							paths: radar_poly,
							strokeColor: "#000000",
							strokeOpacity: 0.8,
							strokeWeight: 1,
							fillColor: "#000000",
							fillOpacity: 0.35
						});
						radar.setMap(map);
					} else {
						// clear radar
						if (radar) {
							activeNodeLatLng = new google.maps.LatLng(0,0);
							radar.setMap(null);
							radar = null;
						}
					}
				}
				function initMap() {
					var gps=pano.getNodeLatLng("");
					if ((gps.length>=2) && ((gps[0]!=0) || (gps[1]!=0))) {
						activeNodeLatLng = new google.maps.LatLng(gps[0], gps[1]);
					} else {
						activeNodeLatLng = new google.maps.LatLng(0,0);
					}
					var mapOptions = {
						zoom: 14,
						center: activeNodeLatLng,
						mapTypeId: google.maps.MapTypeId.HYBRID,
						streetViewControl: false
					};
					map = new google.maps.Map(document.getElementById('mapdiv'), mapOptions);
					var ids=pano.getNodeIds();
					var marker;
					var markerLocation;
					var bounds=new google.maps.LatLngBounds();
					for(var i=0;i<ids.length;i++) {
						var id=ids[i];
						var gps=pano.getNodeLatLng(id);
						if ((gps.length>=2) && ((gps[0]!=0) || (gps[1]!=0))) {
							markerLocation = new google.maps.LatLng(gps[0], gps[1]);
							marker = new google.maps.Marker({position: markerLocation,map: map});
							marker.setTitle(pano.getNodeTitle(id));
							marker.setAnimation(google.maps.Animation.DROP);
							marker.setClickable(true);
							marker.ggId=id;
							bounds.extend(markerLocation);
							google.maps.event.addListener(marker, 'click', function() {
								pano.openNext("{" + this.ggId + "}");
								activeNodeLatLng=this.position;
								lastFov=-1; // force radar update
							});
						}
					}
					if (!bounds.isEmpty()) {
						map.fitBounds(bounds);
					}
					setInterval(function() { updateRadar(); }, 50);
				}
			</script>

		</section>
	<?
	}else{
		$src = 'http://ros.t198.topaz.fastwebserver.de/adc/adc/adc.html';?>
		<div class="container_tur">
			<iframe src="<?=$src?>" width="100%" height="500px" allowfullscreen></iframe>
		</div>
	<?
	}
?>

	<div style="clear: both;"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
