<% if OrderIsPlaced %>
	<h1>Succesfull</h1>
	$ContentComplete	
<% else %>
	<h1>Error during checkout</h1>
	<% include ShopErrorMessage %>
<% end_if %>