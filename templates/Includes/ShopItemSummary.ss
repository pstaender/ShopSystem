<h1>$Title</h1>
<h2>$Price $Currency</h2>
<h4><% if VATType == INCL %>inkl.<% else %>zzgl.<% end_if %> {$Top.Tax}% MwSt</h4>
$Picture.SetWidth(300)
	<span class="buttonOrder" key="$ID"><% _t('Shop.AddToCart','%add to cart%') %></span>
