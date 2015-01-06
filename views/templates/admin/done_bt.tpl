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

<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">×</button>
	{l s='All the taxes have been created. A new tax rule “European VAT for virtual products” is now available.' mod='eurovatgenerator'}
</div>

<div class="panel">
	<h3><i class="icon icon-info-sign"></i> {l s='What should I do now?' mod='eurovatgenerator'}</h3>
	<p>
		{l s='All the taxes have been created and gathered under a new tax rule “European VAT for virtual products”.' mod='eurovatgenerator'}<br />
		{l s='You now have to assign this new tax rule to the virtual products that are liable to be affected.' mod='eurovatgenerator'}<br />
		{l s='You can also safely delete this module.' mod='eurovatgenerator'}<br />
	</p>


	<div class="panel-footer">
			<button type="button" class="btn btn-default pull-right" onclick="window.location.href='{$adm_prd_link|escape:'html':'UTF-8'}';">
				<i class="process-icon-next"></i>
				{l s='Go to Catalog' mod='eurovatgenerator'}
			</button>
	</div>
</div>

