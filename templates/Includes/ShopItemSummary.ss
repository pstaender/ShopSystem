	<span class="price">
	<a href="$Link">
		<h1>$Title<% if Subtitle %><br />$Subtitle<% end_if %></h1>
		<h2>$Price.Decimal $Currency</h2>
		<h4><% if VATType == INCL %>inkl.<% else %>zzgl.<% end_if %> {$Top.Tax}% MwSt</h4>
	</a>
	</span>

	<img src="$Picture.URL" class="productPicture" />
	<div class="productSummary">$Summary
		<p><a href="$Link">Lesen Sie mehr...</a></p>
		<% include ShopItemOptions %>
	</div>
	<span class="buttonOrder" key="$ID"><span><% _t('Shop.AddToCart','%Add to cart%') %></span></span>

	