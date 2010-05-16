<div class="checkoutNavigation">
	<% control Step %>
	<div id="GoPrev"><% if Prev %><a href="{$Top.Link}$Prev"> « $PrevText</a><% end_if %></div>
	<div id="GoCurr">$CurrText</div>
	<div id="GoNext"><% if Next %><a href="{$Top.Link}$Next">$NextText » </a><% end_if %></div>
	<% end_control %>
</div>
<div style="clear:both;"></div>
