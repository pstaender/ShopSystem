<% require ThemedCSS(shopuser) %>
<h1>Bestellungen</h1>
<% if Orders %>
<ul id="OrderTable">
<% control Orders %>
	<li class="orderSegment"><h2>Bestellung vom $PlacedOrderOn.Format(d.m.Y)</h2>
	<h6>Bestellnummer '$OrderKey'</h6>
	<br />
	<table id="ShopOrders" class="shoppingItems">
	<% control Items %>
		<tr class="item" id="ShopItem{$ID}" key="$ID">
			<td class="quantity">{$Quantity}x</td>
			<td class="title"><a href="$OriginalItem.Link" target="_blank">$Title</a>
				<% if Option %>
					<% control Option %>
					( $Title [$PriceDifference.Decimal $Item.Currency] )
					
					<% end_control %>
					<% if HasDownload %>ok
					<h4>Laden Sie hier die Datei <br /><a href="$DownloadFile.DownloadURL" target="_blank">'$DownloadFile.Name'</a><br />(Dateigröße ca. $DownloadFile.Size)</h4>
					<% end_if %>
				<% end_if %>
				</td>
			<td class="price">$Total.Decimal $Currency</td>
		</tr>
	<% end_control %>

		<tr class="amount">
			<td></td><td class="description"><% _t('Shop.Cart.Amount','%Amount%') %>:</td><td class="price">$Amount.Decimal $Currency</td>
		</tr>
		<% if Shipping.Price %>
		<tr class="shippingCosts">
			<td></td><td class="description"><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %>:</td><td class="price"> + $CalcShippingCosts.Decimal $Currency</td>
		</tr>
		<% end_if %>
		<% if Discount %>
		<tr class="discount">
			<td></td><td class="description"><% _t('Shop.Cart.Discount','%Discount%') %>:</td><td class="price">- $Discount.Decimal $Currency</td>
		</tr>
		<% end_if %>
		<% if Total %>		
		<tr class="subTotal">
			<td></td><td class="description"><% _t('Shop.Cart.SubTotal','%SubTotal%') %>:</td><td class="price">$SubTotal.Decimal $Currency</td>
		</tr>
		<tr class="vatAmount">
			<td></td>
			<% if VAT==INCL %><td class="description"><% _t('Shop.Cart.TaxIncl','%TaxIncl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:</td><td class="price">$VATAmount.Decimal $Currency</td>
			<% end_if %>
			<% if VAT==EXCL %><td class="description"><% _t('Shop.Cart.TaxExcl','%TaxExcl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:</td><td class="price">+ $VATAmount.Decimal $Currency</td>
			<% end_if %>
		</tr>
		<tr class="total">
			<td></td><td class="description"><% _t('Shop.Cart.Total','%Total%') %></td><td class="price"><strong>$Total.Decimal $Currency</strong></td>
		</tr>
		<% end_if %>
	</table>
	<p><strong>Letzter Stand:</strong> $StatusTranslated <!--am $LastEdited.Format(Y.m.d h:m) Uhr--></p>
	</li>
	<table class="adresses">
	<tr>
	<td class="invoiceAddress">
	<h4>Rechnungadresse</h4>
		<% control InvoiceAddress %>
		<strong>$Company</strong><br/>
		$FirstName $Surname<br/>
		$Street<br/>
		<p>$Phone</p>
		<% if Country %>$Country - <% end_if %>$ZipCode.ZipCode $City<br/>
		<% end_control %>
	</td>
	<td class="deliveryAddress">
	<h4>Versandadresse</h4>
		<% control DeliveryAddress %>
		<strong>$Company</strong><br/>
		$FirstName $Surname<br/>
		$Street<br/>
		<% if Country %>$Country - <% end_if %>$ZipCode.ZipCode $City<br/>
		<% end_control %>
	</td></tr>
	</table>
<% end_control %>
</ul>
<% end_if %>
<h3>Keine vorhanden</h3>