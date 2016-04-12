<?php
class Zillow
{
	private $zws_id = "X1-ZWz1dyb53fdhjf_6jziz";
	
	function GetSearchResults($address, $citystatezip)
	{
		$url = "http://www.zillow.com/webservice/GetSearchResults.htm?zws-id=".$this->zws_id."&address=".urlencode($address)."&citystatezip=".urlencode($citystatezip);

		$result = file_get_contents($url);
		$data = simplexml_load_string($result);
		
		return $data;
	}
}
?>