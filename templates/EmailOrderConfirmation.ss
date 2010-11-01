<h2>Bestätigungsmail für Ihre Bestellung</h2>

<p>Guten Tag <% if InvoiceAddress.Gender %>$InvoiceAddress.Gender InvoiceAddress.Surname<% end_if %>,</p>

<p>Sie haben bei uns eine Bestellung für folgende Artikel aufgegebn:</p>

<p>
	<table>
		<tr><td>Menge</td><td>Artikel</td><td>Preis</td></tr>
	<% control Items %>
		<tr><td>$Quantity x</td><td>$Title</td><td>$Total $Currency</td></tr>
	<% end_control %>
		
		<tr>
			<td colspan="2"><% if Discount %>Preisnachlass:</td><td align="right"> - $Discount $Currency<% end_if %></td>
		</tr>
		<tr>
			<td colspan="2">Porto und Versand:</td><td align="right">$CalcShippingCosts $Currency</td>
		</tr>
		<tr>
			<td colspan="2">Zwischensumme:</td><td align="right">$SubTotal $Currency</td>
		</tr>
		<tr>
			<td colspan="2">zzgl. {$Tax}% MwSt.</td><td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><strong>Gesamtbetrag:</strong></td>
			<td align="right"><strong>$Total $Currency</strong></td>
		</tr>
	</table>
</p>

<p>Sie erhalten umgehend eine Rechnung zu Ihrer Bestellung als PDF-Datei.</p>

<p>Ihre vorläufige Rechnung können Sie online einsehen unter:</p>
<p><strong><a href="{$BaseHref}$Invoice.Link">{$BaseHref}$Invoice.Link</a></strong></p>
<p>Im die Rechnung zu verbergen, klicken Sie <a href="{$BaseHref}$Invoice.Link">hier</a>.</p>

<p>Vielen Dank für Ihre Bestellung.</p>
<p>Bei Fragen erreichen Sie uns auch telefonisch unter:</p>

<p>Todo Adresse</p>

