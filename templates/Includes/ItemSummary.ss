<% control Top.Cart %>
	<input type="hidden" value="{$ItemsCount}" id="ShoppingCartItemsCount">
	<table class="shoppingItems">
	<% control Items %>
	<% if Quantity %>
		<tr class="item" id="ShopItem{$ID}" key="$ID">
			<td class="quantity">$Quantity</td>
			<td class="action"><div class="inc" quantity="1" key="$OriginalItem.ID" option="$OptionID">+</div><div class="dec" quantity="-1" key="$OriginalItem.ID" option="$OptionID">-</div><div class="remove" quantity="-1000" key="$OriginalItem.ID" option="$OptionID">x</div><a href="$OriginalItem.Link"><div class="info" key="$OriginalItem.ID">i</div></a></td>
			<td class="title"><a href="$OriginalItem.Link">$Title <% if Option %>($Option.Title $OptionPriceDifferenceText $Currency)<% end_if %></a></td>
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
		<% if Shipping.Price %>
		<div class="cartPosition shippingCosts">
			<span class="description"><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %></span> + $CalcShippingCosts.Decimal $Currency
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
		<div class="noItemsMessage">Kein Artikel im Warenkorb</div>
	<% end_if %>
	

</div>
<% end_control %>
<div class="goCheckout"><a href="<% control Top %>$CheckoutPage.Link<% end_control %>"><% _t('Shop.GoCheckout','%Go Checkout%') %></a></div>