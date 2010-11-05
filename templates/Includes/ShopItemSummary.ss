<span class="price"><h2>$Price $Currency</h2>
<h4><% if VATType == INCL %>inkl.<% else %>zzgl.<% end_if %> {$Top.Tax}% MwSt</h4>
</span>

	<img src="$Picture.URL" class="productPicture" />
	<div class="productSummary">$Summary</div>
	<span class="buttonOrder" key="$ID"><span><% _t('Shop.AddToCart','%Add to cart%') %></span></span>
	<% if Options %>
	<div class="productOptions">
		<% control Options %>
		<li class="productOption" id="ProductOption{$ID}" option="$ID">$Title ($OptionPriceDifferenceText) <strong>$Price $Item.Currency</strong></li>
		<% end_control %>
	</div>
	<% end_if %>
