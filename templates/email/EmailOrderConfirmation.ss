<h2>Ihre Bestellung ist bei uns eingegangen</h2>

<p>Guten Tag <% if InvoiceAddress.Salutation %>$InvoiceAddress.Salutation $InvoiceAddress.Surname<% end_if %>,</p>

<p>Sie haben bei uns eine Bestellung für folgende Artikel aufgegeben:</p>

<p>
	<table styl="min-width:800px;">
		<tr><td><strong>Menge</strong></td><td><strong>Artikel</strong></td><td align="right"><strong>Preis</strong></td></tr>
	<% control Items %>
		<tr><td>$Quantity x</td><td>$Title <% if Option %>( $Option.Title )<% end_if %></td><td  align="right">$Total.Decimal $Currency</td></tr>
	<% end_control %>
		
		<tr>
			<td colspan="2"><% if Discount %>Preisnachlass:</td><td align="right"> - $Discount.Decimal $Currency<% end_if %></td>
		</tr>
		<tr>
			<td colspan="2">Porto und Versand:</td><td align="right">+ $CalcShippingCosts.Decimal $Currency</td>
		</tr>
		<tr>
			<td colspan="2">Zwischensumme:</td><td align="right">$SubTotal.Decimal $Currency</td>
		</tr>
		<tr>
			<td colspan="2">zzgl. {$Tax}% MwSt.</td><td align="right">+ $VATAmount $Currency</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Gesamtbetrag:</strong></td>
			<td align="right"><strong>$Total $Currency</strong></td>
		</tr>
	</table>
</p>

<p>Sie erhalten umgehend eine gültige Rechnung zu Ihrer Bestellung als PDF-Datei.</p>

<p>Ihre vorläufige Rechnung können Sie hier online einsehen:</p>
<p><strong><a href="{$BaseHref}$Invoice.Link">{$BaseHref}$Invoice.Link</a></strong></p>
<p>Im die Rechnung zu entfernen, klicken Sie <a href="{$BaseHref}$Invoice.Link?remove=1">hier</a>.</p>

<p>Wir danken Ihnen für Ihre Bestellung bei uns.</p>
<p>Bei Fragen erreichen Sie uns auch telefonisch unter 02232 / 579399-0</p>
<p>Ihr FotoWerkstatt GbR-Team</p>

<% include ShopEmailSignature %>