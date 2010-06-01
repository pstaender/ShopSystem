<% require ThemedCSS(shopcheckout) %>

<% include CheckoutNavigation %>

<% control Cart %>
	<% if IsComplete %>
	$Top.ContentSummary
	$Top.SummaryForm
	
	<% else %>
		<% include ShopErrorMessage %>
	<% end_if %>
<% end_control %>
<% include ItemSummary %>

