<% if Options %>
<div class="productOptions">
	<% control Options %>
	<li class="productOption" id="ProductOption{$ID}" option="$ID">$Title ($OptionPriceDifferenceText $Item.Currency) <strong>$Price $Item.Currency</strong></li>
	<% end_control %>
</div>
<% end_if %>
