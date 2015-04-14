<?php
class IpGrabber {

    public static function doIt() {

    	$ip = Request::getClientIp();

    	if(!IpRange::check($ip)) {

    		try {

    			$skip 								= false;

    			$newEntry							= new Sauron();
			    $eGeoData							= new Geodata();
			    $eDevice							= new Devicedata();
			    $eSegment							= new Segment();

			    $brd 								= BrowserDetect::detect();
    			$loc 								= Location::get( $ip );

    			// Browser information
			    $eDevice->os 						= $brd->osFamily;
			    $eDevice->os_version_major 			= $brd->osVersionMajor;
			    $eDevice->os_version_minor 			= $brd->osVersionMinor;
			    $eDevice->os_version_patch 			= $brd->osVersionPatch;
			    $eDevice->device 					= $brd->deviceFamily;
			    $eDevice->device_model 				= $brd->deviceModel;
			    $eDevice->device_grade 				= $brd->mobileGrade;
			    $eDevice->browser 					= $brd->browserFamily;
			    $eDevice->browser_version_major 	= $brd->browserVersionMajor;
			    $eDevice->browser_version_major 	= $brd->browserVersionMinor;
			    $eDevice->browser_version_patch 	= $brd->browserVersionPatch;
			    $eDevice->is_desktop 				= $brd->isDesktop;
			    $eDevice->is_mobile 				= $brd->isMobile;
			    $eDevice->is_tablet 				= $brd->isTablet;
			    $eDevice->is_robot 					= $brd->isBot;
			    $eDevice->css_version 				= $brd->cssVersion;
			    $eDevice->javascript_support 		= $brd->javaScriptSupport;

			    if(!$eDevice->save()) {
			    	
			    	$skip = true;

			    	Log::error('Error al guardar la informacion del dispositivo');

			    } else $dev_id = $eDevice->id;

			    if(!$skip) {

				    // Geo location information
				    $eGeoData->city 		= $loc->cityName;
				    $eGeoData->country 		= $loc->countryName;
				    $eGeoData->country_code = $loc->countryCode;
				    $eGeoData->latitude 	= $loc->latitude;
				    $eGeoData->longitude 	= $loc->longitude;
				    $eGeoData->region 		= $loc->regionCode;
				    $eGeoData->region_name 	= $loc->regionName;
				    $eGeoData->time_zone 	= $loc->timezone;
				    $eGeoData->zip 			= $loc->zipCode;

				    if(!$eGeoData->save()) {

				    	$skip 	= true;

				    	$dd 	= Devicedata::find($dev_id);

				    	$dd->delete();

				    	Log::error('Error al guardar la informacion del Geo Localizacion');

				    } else $geo_id = $eGeoData->id;

				}

			    // Segmentos
			    $eSegment->method 					= Request::method();
			    $eSegment->url 						= Request::fullUrl();
			    $eSegment->meta_segments 			= json_encode(Request::segments());
			    $eSegment->ip 						= $ip;

			    if(!$eSegment->save()) {
			    	
			    	$skip 	= true;
			    	
			    	$dd 	= Devicedata::find($dev_id);
			    	$gd 	= Geodata::find($geo_id);

			    	$gd->delete();
			    	$dd->delete();

			    	Log::error('Error al guardar la informacion del segmentacion');

			    } else $seg_id = $eSegment->id; 

			    if(!$skip) {

				    // Log
				    $newEntry->ip 				= $ip;
				    $newEntry->hostname 		= gethostbyaddr( $ip );
				    $newEntry->network 			= $loc->network;
				    $newEntry->isp 				= $loc->isp;
				    $newEntry->lang 			= substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
				    $newEntry->referer 			= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
				    $newEntry->geodata_id 		= $geo_id;
				    $newEntry->devicedata_id 	= $dev_id;
				    $newEntry->segment_id 		= $seg_id;

				    if(!$newEntry->save()) {
				    	
				    	$dd = Devicedata::find($dev_id);
				    	$gd = Geodata::find($geo_id);

				    	$gd->delete();
				    	$dd->delete();

				    	Log::error('Error al guardar la informacion del usuario');
				    }
				}
    			
    		} catch (Exception $e) {
    			Log::error($e);
    		} finally {
    			Log::error('And now.... wtf!!!!');
    		}

    	}

    }
}