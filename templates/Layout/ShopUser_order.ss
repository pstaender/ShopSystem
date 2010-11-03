<h2>Bestellungen</h2>
<ul>
<% control Orders %>
	<li><h3>Bestellung vom $PlacedOrderOn.Format(d.m.Y)</h3>
	
	<table class="shoppingItems">
	<% control Items %>
		<tr class="item" id="ShopItem{$ID}" key="$ID">
			<td class="quantity">{$Quantity}x</td>
			<td class="title"><a href="$OriginalItem.Link">$Title</a></td>
			<td class="price">$Total $Currency</td>
		</tr>
	<% end_control %>
		<!--
			$Calculate
		-->
		<tr class="amount">
			<td></td><td class="description"><% _t('Shop.Cart.Amount','%Amount%') %>:</td><td class="price">$Amount $Currency</td>
		</tr>
		<% if Shipping.Price %>
		<tr class="shippingCosts">
			<td></td><td class="description"><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %>:</td><td class="price"> + $CalcShippingCosts $Currency</td>
		</tr>
		<% end_if %>
		<% if Discount %>
		<tr class="discount">
			<td></td><td class="description"><% _t('Shop.Cart.Discount','%Discount%') %>:</td><td class="price">- $Discount $Currency</td>
		</tr>
		<% end_if %>
		<% if Total %>		
		<tr class="subTotal">
			<td></td><td class="description"><% _t('Shop.Cart.SubTotal','%SubTotal%') %>:</td><td class="price">$SubTotal $Currency</td>
		</tr>
		<tr class="vatAmount">
			<td></td>
			<% if VAT==INCL %><td class="description"><% _t('Shop.Cart.TaxIncl','%TaxIncl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:</td><td class="price">$VATAmount $Currency</td>
			<% end_if %>
			<% if VAT==EXCL %><td class="description"><% _t('Shop.Cart.TaxExcl','%TaxExcl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:</td><td class="price">+ $VATAmount $Currency</td>
			<% end_if %>
		</tr>
		<tr class="total">
			<td></td><td><% _t('Shop.Cart.Total','%Total%') %></td><td>$Total $Currency</td>
		</tr>
		<% end_if %>
	</table>
	Status: $Status
	</li>
	<ul><h3>Rechnungadresse:</h3>
		<% control InvoiceAddress %>
		<strong>$Company</strong><br/>
		$FirstName $Surname<br/>
		$Street<br/>
		$Country - $ZipCode $City<br/>
		<% end_control %>
	</ul>
<% end_control %>
</ul>