<% if Options %>
<div class="productOptions">
	<% control Options %>
	<span class="productOption <% if First %>selected<% end_if %>" id="ProductOption{$ID}" option="$ID">$Title ($OptionPriceDifferenceText $Item.Currency) <!--<strong>$Price $Item.Currency</strong>--></span>
	<% end_control %>
</div>
<% end_if %>
