	<span class="price <% if OutOfStock %>outOfStock<% end_if %>">
	<a href="$Link">
		<h1>$Title<% if Subtitle %><br />$Subtitle<% end_if %></h1>
		<h2>$Price.Decimal $Currency</h2>
		<h4><% if VATType == INCL %>inkl.<% else %>zzgl.<% end_if %> {$Top.Tax}% MwSt</h4>
	</a>
	</span>

	<img src="$Picture.URL" class="productPicture" />
	<div class="productSummary <% if OutOfStock %>outOfStock<% end_if %>">$Summary
		<p><a href="$Link"></a></p>
	</div>
	<% include ShopItemOptions %>
	<span class="buttonOrder <% if OutOfStock %>outOfStock<% end_if %>" key="$ID"><span><% _t('Shop.AddToCart','%Add to cart%') %></span></span>

	