<% control Cart %>
	<% if AmountBelowMin %>
		<h2>Sie haben noch nicht den Mindestbestellwert von $MinAmount $Currency im Warenkorb</h2>
	<% end_if %>
	<%  control InvoiceAddress %>
		<% if ID==0 %>
			<h2>Sie haben noch keine Rechnungsanschrift angegeben</h2>
		<% end_if %>
	<% end_control %>

	<%  control DeliveryAddress %>
		<% if ID==0 %>
			<h2>Sie müssen eine Lieferadresse angeben</h2>
		<% end_if %>
	<% end_control %>

<% end_control %>