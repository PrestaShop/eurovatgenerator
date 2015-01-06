{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $errors}
	{foreach from=$errors item=error}
		{$error|escape:'htmlall':'UTF-8'}
	{/foreach}
{/if}

	<h3>{l s='European VAT for virtual products' mod='eurovatgenerator'}</h3>
	<fieldset>
		<p>
			{l s='This module helps you to easily create the needed European taxes so that you can comply with the new rule on VAT for virtual products.' mod='eurovatgenerator'}<br />
			{l s='Once done, you will have to amend your product catalog by assigning this European tax rule to the relevant virtual products.' mod='eurovatgenerator'}
		</p>
	</fieldset>


	<h3>{l s='VAT per country' mod='eurovatgenerator'}</h3>
	<form action="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" method="POST">
		<fieldset>
			<p>
				{l s='For each country, please indicate whether the normal VAT rate already exists on your shop. If not, it will be created automatically.' mod='eurovatgenerator'}<br />
			</p>
			<br/>
			{foreach from=$euro_vat_list item=euro_vat}
			<div style="margin: 10px 0 10px 0;">

					<div>
						<label>
							{$euro_vat.eur_vat_label|escape:'htmlall':'UTF-8'}:
							<span class="badge {if $euro_vat.vat_found}badge-success{else}badge-danger{/if}">
								<i class="icon {if $euro_vat.vat_found}icon-check{else}icon-remove{/if}"></i>
							</span>
						</label>
					</div>

					<div>
						<select name="euro_vat_{$euro_vat.iso_country|escape:'htmlall':'UTF-8'}">
						  <option value="exists" {if $euro_vat.vat_found}selected{/if}>{l s='VAT already exists on my shop' mod='eurovatgenerator'}</option>
						  <option value="not_found" {if !$euro_vat.vat_found}selected{/if}>{l s='VAT does not exist - it will be created' mod='eurovatgenerator'}</option>
						</select>

						{if $euro_vat.vat_found}
							<select name="available_vat_{$euro_vat.iso_country|escape:'htmlall':'UTF-8'}">
							{foreach from=$available_vat_list key=key item=tax}
							 
							  <option value="{$key}" {if $euro_vat.euro_vat_name == $tax}selected{/if}>{$tax}</option>

							{/foreach}
							</select> 
						{/if}
					</div>
			</div>
			{/foreach}

				<input id="next_step_vat_generator" type="submit" name="submitGenererateMissingVAT" value="{l s='Save' mod='eurovatgenerator'}"/>
				
			</div>
		</fieldset>
	</form>

</div>

