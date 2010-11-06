<% if Options %>
<div class="productOptions">
	<% control Options %>
	<div class="productOption <% if First %>selected<% end_if %>" id="ProductOption{$ID}" option="$ID">$Title <% if PriceDifference=0 %><% else %>($PriceDifferenceText $Item.Currency)<% end_if %>
	</div>
	<% end_control %>
</div>
<% end_if %>
