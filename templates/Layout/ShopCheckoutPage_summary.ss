<% require ThemedCSS(shopcheckout) %>

<% include CheckoutNavigation %>

<% control Cart %>
	<% if IsComplete %>
	$Top.ContentSummary
	$Top.SummaryForm
	<div id="ItemSummary"><% include ItemSummary %></div>
	<% else %>
		<% include ShopErrorMessage %>
	<% end_if %>
<% end_control %>

