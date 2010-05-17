<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
		<% base_tag %>
		<title><% _t('Shop.Invoice.Invoice','%Invoice%') %></title>
		<meta name="robots" content="NOINDEX,NOFOLLOW" />
		<meta name="googlebot" content="NOARCHIVE, NOODP, NOSNIPPET" />
		
		<% require themedCSS(invoice) %>
	</head>

<body>

<% control Invoice %>
<div class="invoiceHeader">
	<div class="dateOfInvoice invoiceData">
		<div class="title"><% _t('Shop.Invoice.DateOfInvoice','%DateOfInvoice%') %></div>
		$InvoiceKey
	</div>
	<div class="dateOfDelivery invoiceData">
		<div class="title"><% _t('Shop.Invoice.DateOfDelivery','%DateOfDelivery%') %></div>
		$DateOfDelivery.Nice</div>
	<div class="invoiceKey invoiceData">
		<div class="title"><% _t('Shop.Invoice.InvoiceKey','%InvoiceKey%') %></div>
		$InvoiceKey
	</div>
</div>

<div style="clear:both;"></div>

<% control Order %>

	<div class="invoiceAddress">
		<div class="title"><% _t('Shop.Invoice.InvoiceAddress','%InvoiceAddress%') %></div>
	<% control InvoiceAddress %>
		<div><strong>$Company</strong></div>
		<div>$FirstName $Surname</div>
		<div>$AdditionalAddress</div>
		<div>$Street</div>
		<div>$Country - $ZipCode $City</div>
	<% end_control %>
	</div>
	
	<div class="companyAddress">
		<div><strong>Company</strong></div>
		<div>Street</div>
		<div>ZipCode City</div>
	</div>



			<table class="shoppingItems">
			<% control Items %>
				<tr class="item even">
					<td class="fieldNames quantity"><% _t("Shop.Invoice.Quantity","%Quantity%") %></td>
					<td class="fieldNames title"><% _t("Shop.Invoice.Product","%Product%") %></td>
					<td class="fieldNames price"><% _t("Shop.Invoice.Price","%Price%") %></td>
				</tr>
			
			<% if Quantity==0 %><% else %>
				<tr class="item $EvenOdd" id="ShopItem{$ID}" key="$ID">
					<td class="quantity">$Quantity</td>
					<td class="title"><a href="$OriginalItem.Link">$Title</a></td>
					<td class="price">$Total $Currency</td>
				</tr>
			<% end_if %>
			<% end_control %>
			</table>
		<!--
			$Calculate
		-->
		<div class="cartAmount">
				<div class="cartPosition amount">
					<span class="description"><% _t('Shop.Cart.Amount','%Amount%') %></span> $Amount $Currency
				</div>
				<% if ShippingCosts %>
				<div class="cartPosition shippingCosts">
					<span class="description"><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %></span> + $ShippingCosts $Currency
				</div>
				<% end_if %>
				<% if Discount %>
				<div class="cartPosition discount">
					<span class="description"><% _t('Shop.Cart.Discount','%Discount%') %></span>
					- $Discount $Currency
				</div>
				<% end_if %>
			<% if Total %>		
				<div class="cartPosition subTotal">
					<span class="description"><% _t('Shop.Cart.SubTotal','%SubTotal%') %></span>
					$SubTotal $Currency
				</div>
				<div class="cartPosition vatAmount">
					<% if VAT==INCL %>
						<span class="description">
							<% _t('Shop.Cart.TaxIncl','%TaxIncl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>
						</span>
						$VATAmount $Currency
					<% end_if %>
					<% if VAT==EXCL %>
						<span class="description">
							<% _t('Shop.Cart.TaxExcl','%TaxExcl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>
						</span>
						+ $VATAmount $Currency
					<% end_if %>
				</div>
				<div class="cartPosition total">
					<span class="description"><% _t('Shop.Cart.Total','%Total%') %></span>
					$Total $Currency
				</div>
			<% else %>


		</div>
		<% end_control %>



<% end_control %>

<% end_control %>

</body>
</html>