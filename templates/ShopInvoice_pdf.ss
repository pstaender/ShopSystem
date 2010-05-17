<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
		<% base_tag %>
		<title><% _t('Shop.Invoice.Invoice','%Invoice%') %></title>
		<meta name="robots" content="NOINDEX,NOFOLLOW" />
		<meta name="googlebot" content="NOARCHIVE, NOODP, NOSNIPPET" />
		
		<style type="text/css">
		
		body, table {
			font-family: Arial;
			font-size: 12px;
		}
		
		</style>
	</head>

<body>
	


<% control Invoice %>


	<% control Order %>
	
	
	<table align="right">
	<tr>
		<td>
			<table>
				<tr><td><strong><% _t("Shop.Company.Name","%Name%") %></strong></td></tr>
				<tr><td><% _t("Shop.Company.Street","%Street%") %></td></tr>
				<tr><td><% _t("Shop.Company.ZipCode","%ZipCode%") %> <% _t("Shop.Company.City","%City%") %></td></tr>
				<tr><td><% _t("Shop.Company.Phone","%Phone%") %></td></tr>
				<tr><td><% _t("Shop.Company.Email","%Email%") %></td></tr>
				<tr><td>&nbsp;</td></tr>
			</table>
		</td>
	</tr>
	</table>
	
	<table id="address" width="50%">
		<tr>
			<td class="invoiceAddress">
			<% control InvoiceAddress %>
				<table width="50%">
					<tr><td><strong><% _t('Shop.Invoice.InvoiceAddress','%InvoiceAddress%') %>:</strong></td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td><strong>$Company</strong></td></tr>
					<tr><td>$FirstName $Surname</td><tr>
					<% if AdditionalAddress %><tr><td>$AdditionalAddress</tr></td><% end_if %>
					<tr><td>$Street</tr></td>
					<tr><td>$Country - $ZipCode $City</tr></td>
				</table>
			<% end_control %>
			</td>
		</tr>
	</table>
	
	<p>&nbsp;</p><p>&nbsp;</p>
	<% end_control %>

<br/><br/><br/>

<table class="invoiceHeader" width="90%">
	<tr>
	<td>
		<strong><% _t('Shop.Invoice.DateOfInvoice','%DateOfInvoice%') %></strong>
	</td>
	<td>
		<strong><% _t('Shop.Invoice.DateOfDelivery','%DateOfDelivery%') %></strong>
	</td>
	<td>
		<strong><% _t('Shop.Invoice.InvoiceKey','%InvoiceKey%') %></strong>
	</td>
	<td>
		<strong><% _t('Shop.Invoice.OrderKey','%OrderKey%') %></strong>
	</td>
	</tr>
	<tr>
		<td>
		$DateOfInvoice.Nice
		</td>
		<td>
		$DateOfDelivery.Nice
		</td>
		<td>
		$InvoiceKey
		</td>
		<td>
		$Order.OrderKey
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<% control Order %>

			<table class="shoppingItems" width="90%">
			<% control Items %>
				<tr class="item even">
					<td class="fieldNames quantity" bgColor="#dddddd"><strong><% _t("Shop.Invoice.Quantity","%Quantity%") %></strong></td>
					<td class="fieldNames title" bgColor="#dddddd"><strong><% _t("Shop.Invoice.Product","%Product%") %></strong></td>
					<td class="fieldNames price" bgColor="#dddddd" align="right"><strong><% _t("Shop.Invoice.Price","%Price%") %></strong></td>
				</tr>
			
			<% if Quantity==0 %><% else %>
				<tr class="item $EvenOdd" id="ShopItem{$ID}" key="$ID" bgColor="<% if Even %>#dddddd<% end_if %>">
					<td class="quantity">$Quantity</td>
					<td class="title"><strong>$Title</strong></td>
					<td class="price" align="right">$Total $Currency</td>
				</tr>
			<% end_if %>
			<tr><td>&nbsp;</td><td>&nbsp;</td><td align="right">________________</td></tr>
			<% end_control %>
			<!--
				$Calculate
			-->
			
				<tr>
					<td>&nbsp;</td>
					<td><% _t('Shop.Cart.Amount','%Amount%') %>:</td>
					<td align="right">
						$Amount $Currency
					</td>
				</tr>
				<% if ShippingCosts %><tr>
					<td>&nbsp;</td>
					<td><% _t('Shop.Cart.ShippingCosts','%ShippingCosts%') %>:</td>
					<td align="right">
						+ $ShippingCosts $Currency
					</td>
				</tr><% end_if %>
				<% if Discount %>
				<tr>
					<td>&nbsp;</td>
					<td><% _t('Shop.Cart.Discount','%Discount%') %>:</td>
					<td align="right">- $Discount $Currency</td>
				</tr>
				<% end_if %>			
			<% if Total %>		
				<tr>
					<td>&nbsp;</td>
					<td><% _t('Shop.Cart.SubTotal','%SubTotal%') %>:</td>
					<td align="right">$SubTotal $Currency</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<% if VAT==INCL %>
						<td>
							<% _t('Shop.Cart.TaxIncl','%TaxIncl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:
						</td>
						<td align="right">$VATAmount $Currency</td>
					<% end_if %>
					<% if VAT==EXCL %>
						<td>
							<% _t('Shop.Cart.TaxExcl','%TaxExcl%') %> {$Tax}% <% _t('Shop.Cart.TaxVAT','%TaxVAT%') %>:
						</td>
						<td align="right">+ $VATAmount $Currency</td>
					<% end_if %>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><% _t('Shop.Cart.Total','%Total%') %>:</td>
					<td align="right"><strong>$Total $Currency</strong></td>
				</tr>
			<% end_if %>
			</table>

<% end_control %>



<% end_control %>


</body>
</html>