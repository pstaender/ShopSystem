<% require ThemedCSS(shopcheckout) %>

<% include CheckoutNavigation %>



<% control Cart %>
	<% if IsComplete %>
	$Top.ContentSummary
	$Top.SummaryForm
	
	<br />
	<h2><% _t('Shop.Checkout.YourInvoiceAddress','%Your invoice address%') %></h2>
	<p>
	<% control InvoiceAddress %>
	$FirstName $Surname<br />
	<% if AdditionalAddress %>$AdditionalAddress<br /><% end_if %>
	$Street<br />
	$Country - $ZipCode $City<br />
	<% end_control %>
	</p>

	<br />
	<h2><% _t('Shop.Checkout.YourDeliveryAddress','%Your delivery address%') %></h2>
	<p>
	<% control DeliveryAddress %>
	$FirstName $Surname<br />
	<% if AdditionalAddress %>$AdditionalAddress<br /><% end_if %>
	$Street<br />
	$Country - $ZipCode $City<br />
	<% end_control %>
	</p>
	
	<h2><% _t('Shop.Checkout.Payment','%Payment%') %></h2>
	<p>
		$Payment.MethodTitle ($CalcPaymentCosts $Currency)
	</p>

	<h2><% _t('Shop.Checkout.Shipping','%Shipping%') %></h2>
	<p>
		$Shipping.MethodTitle ($CalcShippingCosts $Currency)
	</p>

	<h2><% _t('Shop.Checkout.Summary','%Summary%') %></h2>
	<p>&nbsp;</p>
	<div id="ItemSummary"><% include ItemSummary %></div>
	<% else %>
		<h2><% _t('Shop.Checkout.IncompleteOrder','%Incomplete Order%') %></h2>
		<% include ShopErrorMessage %>
	<% end_if %>
<% end_control %>

