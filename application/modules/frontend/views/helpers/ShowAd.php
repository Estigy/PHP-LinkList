<?php

class Frontend_View_Helper_ShowAd extends Zend_View_Helper_Abstract {
	
	public function showAd($type)
	{
		$config = Core_Config::getConfig();
		
		$adDefinition = $config->adDefinition;
		
		if (!is_array($adDefinition)) {
			return '';
		}
		
		$ad = $adDefinition[$type];
		if (!is_array($ad)) {
			return '<!-- defitinition for ad-type "' . $type . '" not found -->';
		}
		
		$s = '
		<script type="text/javascript"><!--
			google_ad_client = "' . $config->googleAdsensePublisherId . '";
			google_ad_slot   = "' . $ad['slot']   . '";
			google_ad_width  = '  . $ad['width']  . ';
			google_ad_height = '  . $ad['height'] . ';
			//-->
		</script>
		<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
		
		return $s;
	}
}