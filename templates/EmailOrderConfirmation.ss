<h2>Ihre Bestellung bei uns ist eingegangen</h2>

<p>Guten Tag <% if InvoiceAddress.Gender %>$InvoiceAddress.Salutation $InvoiceAddress.Surname<% end_if %>,</p>

<p>Sie haben bei uns eine Bestellung für folgende Artikel aufgegebn:</p>

<p>
	<table>
		<tr><td>Menge</td><td>Artikel</td><td align="right">Preis</td></tr>
	<% control Items %>
		<tr><td>$Quantity x</td><td>$Title</td><td  align="right">$Total $Currency</td></tr>
	<% end_control %>
		
		<tr>
			<td colspan="2"><% if Discount %>Preisnachlass:</td><td align="right"> - $Discount $Currency<% end_if %></td>
		</tr>
		<tr>
			<td colspan="2">Porto und Versand:</td><td align="right">+ $CalcShippingCosts $Currency</td>
		</tr>
		<tr>
			<td colspan="2">Zwischensumme:</td><td align="right">$SubTotal $Currency</td>
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

<p>Ihre vorläufige Rechnung können Sie online einsehen unter:</p>
<p><strong><a href="{$BaseHref}$Invoice.Link">{$BaseHref}$Invoice.Link</a></strong></p>
<p>Im die Rechnung zu entfernen, klicken Sie <a href="{$BaseHref}$Invoice.Link?remove=1">hier</a>.</p>

<p>Wir danken Ihnen für Ihre Bestellung bei uns.</p>
<p>Bei Fragen erreichen Sie uns auch telefonisch unter:</p>

<p>Todo Adresse</p>

