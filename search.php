<?php
require "Zillow.php";

//Create Instance of Class Zillow
$zillow = new Zillow();

//Check to see if input addr is set in url
if(isset($_GET['addr']))
{
	//Split full address into street address and citystatezip based on first comma
	list($address, $citystatezip) = explode(',', trim($_GET['addr']), 2);
	
	//Get results from zillow web service GetSearchResults
	$data = $zillow->GetSearchResults($address, $citystatezip);
	
	//Get first result from result set
	$result = $data->response->results->result[0];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Zillow Search<?php echo((!empty($result->address->street) ? " - ".$result->address->street : "")) ?></title>
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="header"><a href="search.php">Zillow Search</a></div>
<br>

<form>
<input type="text" name="addr" id="addr" value="<?php echo($_GET['addr']) ?>" placeholder="Address, City, State or Zip"><br>
<input type="submit" value="Search">
</form>

<?php 
if($data->message->code != 0)
{
	//Display message text and code if exists
	?>
	<br><div class="error"><?php echo($data->message->text) ?> (Code <?php echo($data->message->code) ?>)</div>
	<?php
}

if(!empty($result->zpid))
{
	//Display results if zillow id is found
	?>
	<br><br>
	
	<div class="result"><?php echo($result->address->street.",") ?></div>
	<div class="result"><?php echo($result->address->city.", ".$result->address->state." ".$result->address->zipcode) ?></div>
	<br>
	
	<div class="result">Zestimate:</div>
	<div class="sub-result"><b>&#36;<?php echo(number_format((int) $result->zestimate->amount)) ?></b></div>
	<div class="sub-result">Last updated on <?php echo($result->zestimate->{'last-updated'}) ?></div>
	<div class="sub-result">Valuation estimated between &#36;<?php echo(number_format((int) $result->zestimate->valuationRange->low)) ?> and &#36;<?php echo(number_format((int) $result->zestimate->valuationRange->high)) ?> </div>
	<div class="sub-result">Last change in valuation was a<?php echo($result->zestimate->valueChange < 0 ? " decrease" : "n increase") ?> of &#36;<?php echo(number_format((int) abs($result->zestimate->valueChange))) ?></div>
	<div class="sub-result">Current Zestimate percentile is <?php echo($result->zestimate->percentile) ?>%</div>
	<br>
		
	<a href="<?php echo($result->links->homedetails) ?>">Home Details</a> | 
	<a href="<?php echo($result->links->graphsanddata) ?>">Graphs and Data</a> | 
	<a href="<?php echo($result->links->mapthishome) ?>">Map this Home</a> | 
	<a href="<?php echo($result->links->comparables) ?>">Comparables</a>
	<br><br>
	
	<div class="result">Region:</div>
	<div class="sub-result"><?php echo($result->localRealEstate->region['name'])." (".(ucwords($result->localRealEstate->region['type']).")") ?></div>
	<div class="sub-result">Zillow Home Value Index is &#36;<?php echo($result->localRealEstate->region->zindexValue) ?></div>
	<br>
	
	<a href="<?php echo($result->localRealEstate->region->links->overview) ?>">Overview</a> | 
	<a href="<?php echo($result->localRealEstate->region->links->forSale) ?>">For Sale</a> | 
	<a href="<?php echo($result->localRealEstate->region->links->forSaleByOwner) ?>">For Sale By Owner</a>
	<br>
	
	<br>
	<iframe width="612" height="250" frameborder="0" style="border:0"
	src="https://www.google.com/maps/embed/v1/search?q=<?php echo(urlencode($result->address->street.",".$result->address->city.", ".$result->address->state." ".$result->address->zipcode)) ?>&key=AIzaSyBugt6Ag5GelE5SwiSxqfq0yikgN8lfhQM" allowfullscreen></iframe> 
	<br><span style="font-size: 12px; color: gray;">(Longitude: <?php echo($result->address->longitude) ?>, Latitude: <?php echo($result->address->latitude) ?>)</span>
	<br><br><span style="font-size: 12px; color: gray;">Zillow ID: <?php echo($result->zpid) ?></span>
	<?php
}
?>
</body>
</html>
