<!DOCTYPE html>
<html lang="$ContentLocale">
  <head>
		<% base_tag %>
		<title><% _t('Shop.Invoice.Invoice','%Invoice%') %></title>
		$MetaTags(false)
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="NOINDEX,NOFOLLOW" />
		<meta name="googlebot" content="NOARCHIVE, NOODP, NOSNIPPET" />
		
		<% require themedCSS(invoice) %>
	</head>

<body>

<% control Invoice %>
<div class="invoiceHeader">
	<div class="dateOfInvoice invoiceData">
		<div class="title"><% _t('Shop.Invoice.DateOfInvoice','%DateOfInvoice%') %></div>
		$DateOfInvoice.Format(d.m.Y)
	</div>
	<div class="dateOfDelivery invoiceData">
		<div class="title"><% _t('Shop.Invoice.DateOfDelivery','%DateOfDelivery%') %></div>
		$DateOfDelivery.Format(d.m.Y)</div>
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
		<% include ShopCompanyAddress %>
	</div>



			<table class="shoppingItems">
				<tr class="item even">
					<td class="fieldNames quantity"><% _t("Shop.Invoice.Quantity","%Quantity%") %></td>
					<td class="fieldNames title"><% _t("Shop.Invoice.Product","%Product%") %></td>
					<td class="fieldNames price"><% _t("Shop.Invoice.Price","%Price%") %></td>
				</tr>
				
			<% control Items %>
			
			<% if Quantity==0 %><% else %>
				<tr class="item $EvenOdd" id="ShopItem{$ID}" key="$ID">
					<td class="quantity">$Quantity</td>
					<td class="title"><a href="$OriginalItem.Link">$Title <% if Option %><% control Option %>( $Title <% if PriceDifference=0 %><% else %>: $PriceDifferenceText $Item.Currency<% end_if %> )<% end_control %><% end_if %></a></td>
					<td class="price">$Total.Decimal $Currency</td>
				</tr>
			<% end_if %>
			<% end_control %>
			</table>
		<!--
			$Calculate
		-->
		<div class="cartAmount">
				<div class="cartPosition amount">
					<span class="description"><% _t('Shop.Cart.Amount','%Amount%') %></span> $Amount.Decimal $Currency
				</div>
				<% if ShippingCosts %>
				<div class="cartPosition shippingCosts">
					<span class="description"><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %></span> + $ShippingCosts.Decimal $Currency
				</div>
				<% end_if %>
				<% if Discount %>
				<div class="cartPosition discount">
					<span class="description"><% _t('Shop.Cart.Discount','%Discount%') %></span>
					- $Discount.Decimal $Currency
				</div>
				<% end_if %>
			<% if Total %>		
				<div class="cartPosition subTotal">
					<span class="description"><% _t('Shop.Cart.SubTotal','%SubTotal%') %></span>
					$SubTotal.Decimal $Currency
				</div>
				<div class="cartPosition vatAmount">
					<% if VAT==INCL %>
						<span class="description">
							<% _t('Shop.Cart.TaxIncl','%TaxIncl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>
						</span>
						$VATAmount.Decimal $Currency
					<% end_if %>
					<% if VAT==EXCL %>
						<span class="description">
							<% _t('Shop.Cart.TaxExcl','%TaxExcl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>
						</span>
						+ $VATAmount.Decimal $Currency
					<% end_if %>
				</div>
				<div class="cartPosition total">
					<span class="description"><% _t('Shop.Cart.Total','%Total%') %></span>
					$Total.Decimal $Currency
				</div>
			<% else %>


		</div>
		<% end_control %>

		<div style="text-align: left; margin-top:2em;">Bitte überweisen Sie den fälligen Betrag von <strong><% control Order %>$Total.Decimal $Currency</strong> mit den Betreff <strong>{$OrderKey}<% end_control %></strong></div>


<% end_control %>



<% end_control %>


</body>
</html>