<% control Cart %>
	<table class="shoppingItems">
	<% control Items %>
	<% if Quantity==0 %><% else %>
		<tr class="item" id="ShopItem{$ID}" key="$ID">
			<td class="quantity">$Quantity</td>
			<td class="action"><div class="inc" quantity="1" key="$OriginalItem.ID">+</div><div class="dec" quantity="-1" key="$OriginalItem.ID">-</div><div class="remove" quantity="-1000" key="$OriginalItem.ID">x</div><a href="$OriginalItem.Link"><div class="info" key="$OriginalItem.ID">i</div></a></td>
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
		<div class="noItemsMessage">Kein Artikel im Warenkorb</div>
	<% end_if %>
	

</div>
<% end_control %>
<div class="goCheckout"><a href="$CheckoutPage.Link"><% _t('Shop.GoCheckout','%Go Checkout%') %></a></div>