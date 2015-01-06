<?php
/**
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
*/

if (!defined('_PS_VERSION_'))
	exit;

class Eurovatgenerator extends Module
{
	public static $europe_vat_array = array(
												'TVA FR 20%' 		=>	array('iso_country' => 'fr', 'rate' => 20),
												'USt. AT 20%'		=>	array('iso_country' => 'at', 'rate' => 20),
												'TVA BE 21%'		=>	array('iso_country' => 'be', 'rate' => 21),
												'ДДС BG 20%'		=>	array('iso_country' => 'bg', 'rate' => 20),
												'ΦΠΑ CY 19%'		=>	array('iso_country' => 'cy', 'rate' => 19),
												'DPH CZ 21%'		=>	array('iso_country' => 'cz', 'rate' => 21),
												'MwSt. DE 19%'		=>	array('iso_country' => 'de', 'rate' => 19),
												'moms DK 25%'		=>	array('iso_country' => 'dk', 'rate' => 25),
												'km EE 20%'			=>	array('iso_country' => 'ee', 'rate' => 20),
												'IVA ES 21%'		=>	array('iso_country' => 'es', 'rate' => 21),
												'ALV FI 24%'		=>	array('iso_country' => 'fi', 'rate' => 24),
												'VAT UK 20%'		=>	array('iso_country' => 'gb', 'rate' => 20),
												'ΦΠΑ GR 23%'		=>	array('iso_country' => 'gr', 'rate' => 23),
												'Croatia PDV 25%'	=>	array('iso_country' => 'hr', 'rate' => 25),
												'ÁFA HU 27%'		=>	array('iso_country' => 'hu', 'rate' => 27),
												'VAT IE 23%'		=>	array('iso_country' => 'ie', 'rate' => 23),
												'IVA IT 22%'		=>	array('iso_country' => 'it', 'rate' => 22),
												'PVM LT 21%'		=>	array('iso_country' => 'lt', 'rate' => 21),
												'TVA LU 17%'		=>	array('iso_country' => 'lu', 'rate' => 17),
												'PVN LV 21%'		=>	array('iso_country' => 'lv', 'rate' => 21),
												'VAT MT 18%'		=>	array('iso_country' => 'mt', 'rate' => 18),
												'BTW NL 21%'		=>	array('iso_country' => 'nl', 'rate' => 21),
												'PTU PL 23%'		=>	array('iso_country' => 'pl', 'rate' => 23),
												'IVA PT 23%'		=>	array('iso_country' => 'pt', 'rate' => 23),
												'TVA RO 24%'		=>	array('iso_country' => 'ro', 'rate' => 24),
												'Moms SE 25%'		=>	array('iso_country' => 'se', 'rate' => 25),
												'DDV SI 22%'		=>	array('iso_country' => 'si', 'rate' => 22),
												'DPH SK 20%'		=>	array('iso_country' => 'sk', 'rate' => 20)
											);
	public function __construct()
	{
		$this->name = 'eurovatgenerator';
		$this->tab = 'pricing_promotion';
		$this->version = '1.0.0';
		$this->author = 'PrestaShop';
		$this->need_instance = 1;
		$this->bootstrap = true;
		$this->_errors = array();
		$this->european_vat_name = 'European VAT for virtual products';

		parent::__construct();

		$this->displayName = $this->l('Europe VAT Generator');
		$this->description = $this->l('Easily get compliant with the new European VAT rules for virtual products.');
	}

	public function getContent()
	{
		$this->_postProcess();

		if (version_compare(_PS_VERSION_, '1.6.0', '>=') === true)
		{
			$done_tpl = 'done_bt.tpl';
			$configure_tpl = 'configure_bt.tpl';
		}
		else
		{
			$done_tpl = 'done.tpl';
			$configure_tpl = 'configure.tpl';
		}

		$this->context->smarty->assign(array(	'module_dir' => $this->_path,
												'euro_vat_list' =>	$this->getVATDetailsByCountry(),
												'available_vat_list' => $this->getAvailableTaxesDetails(),
												'errors' => $this->_errors,
												'adm_prd_link' => $this->context->link->getAdminLink('AdminProducts')
											)
										);

		if (Tools::isSubmit('submitGenererateMissingVAT') && !$this->_errors)
			$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/'.$done_tpl);
		else
			$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/'.$configure_tpl);

		return $output;
	}

	/*private function getEuroVATDataArray()
	{
		return array(
						'TVA FR 20%' 		=>	array('iso_country' => 'fr', 'rate' => 20),
						'USt. AT 20%'		=>	array('iso_country' => 'at', 'rate' => 20),
						'TVA BE 21%'		=>	array('iso_country' => 'be', 'rate' => 21),
						'ДДС BG 20%'		=>	array('iso_country' => 'bg', 'rate' => 20),
						'ΦΠΑ CY 19%'		=>	array('iso_country' => 'cy', 'rate' => 19),
						'DPH CZ 21%'		=>	array('iso_country' => 'cz', 'rate' => 21),
						'MwSt. DE 19%'		=>	array('iso_country' => 'de', 'rate' => 19),
						'moms DK 25%'		=>	array('iso_country' => 'dk', 'rate' => 25),
						'km EE 20%'			=>	array('iso_country' => 'ee', 'rate' => 20),
						'IVA ES 21%'		=>	array('iso_country' => 'es', 'rate' => 21),
						'ALV FI 24%'		=>	array('iso_country' => 'fi', 'rate' => 24),
						'VAT UK 20%'		=>	array('iso_country' => 'gb', 'rate' => 20),
						'ΦΠΑ GR 23%'		=>	array('iso_country' => 'gr', 'rate' => 23),
						'Croatia PDV 25%'	=>	array('iso_country' => 'hr', 'rate' => 25),
						'ÁFA HU 27%'		=>	array('iso_country' => 'hu', 'rate' => 27),
						'VAT IE 23%'		=>	array('iso_country' => 'ie', 'rate' => 23),
						'IVA IT 22%'		=>	array('iso_country' => 'it', 'rate' => 22),
						'PVM LT 21%'		=>	array('iso_country' => 'lt', 'rate' => 21),
						'TVA LU 17%'		=>	array('iso_country' => 'lu', 'rate' => 17),
						'PVN LV 21%'		=>	array('iso_country' => 'lv', 'rate' => 21),
						'VAT MT 18%'		=>	array('iso_country' => 'mt', 'rate' => 18),
						'BTW NL 21%'		=>	array('iso_country' => 'nl', 'rate' => 21),
						'PTU PL 23%'		=>	array('iso_country' => 'pl', 'rate' => 23),
						'IVA PT 23%'		=>	array('iso_country' => 'pt', 'rate' => 23),
						'TVA RO 24%'		=>	array('iso_country' => 'ro', 'rate' => 24),
						'Moms SE 25%'		=>	array('iso_country' => 'se', 'rate' => 25),
						'DDV SI 22%'		=>	array('iso_country' => 'si', 'rate' => 22),
						'DPH SK 20%'		=>	array('iso_country' => 'sk', 'rate' => 20)
					);

	}*/

	private function getAvailableTaxesDetails()
	{
		$all_taxes = Tax::getTaxes();
		$return_array = array();

		foreach ($all_taxes as $tax)
		{
			$tax_obj = new Tax((int)$tax['id_tax']);
			$return_array[(int)$tax['id_tax']] = (string)$tax_obj->name[1];
			unset($tax_obj);
		}

		return $return_array;
	}

	private function getVATDetailsByCountry()
	{
		$user_lang = (int)$this->context->employee->id_lang;
		$euro_vat_array = self::$europe_vat_array;
		$available_vat_array = $this->getAvailableTaxesDetails();

		foreach ($euro_vat_array as $euro_vat_name => $eur_vat_details)
		{
			$country_id = Country::getByIso((string)$eur_vat_details['iso_country']);
			$country_name = Country::getNameById($user_lang, (int)$country_id);

			if (in_array($euro_vat_name, $available_vat_array))
			{
				$id_tax = array_search($euro_vat_name, $available_vat_array);
				$euro_vat_array[$euro_vat_name]['vat_found'] = array('label' => (string)$available_vat_array[$id_tax], 'id_tax' => (int)$id_tax);
			}
			else
				$euro_vat_array[$euro_vat_name]['vat_found'] = false;

			$euro_vat_array[$euro_vat_name]['eur_vat_label'] = (string)$country_name.' ('.(string)$eur_vat_details['iso_country'].
												')'.((Tools::strlen($euro_vat_name) > 3) ? ' - '.(string)Tools::substr($euro_vat_name, -3) : '');

			$euro_vat_array[$euro_vat_name]['country_name'] = $country_name;
			$euro_vat_array[$euro_vat_name]['iso_country'] = (string)$eur_vat_details['iso_country'];
			$euro_vat_array[$euro_vat_name]['euro_vat_name'] = (string)$euro_vat_name;
		}
		return $euro_vat_array;
	}

	private function getVATDataFromIsoCountry($iso_country)
	{
		$euro_vat_array = self::$europe_vat_array;

		foreach ($euro_vat_array as $euro_vat_details)
		{
			if ($iso_country == $euro_vat_details['iso_country'])
				return $euro_vat_details;
		}

		return false;
	}

	private function getTaxRuleIdFromUnique($id_tax_rules_group, $id_country, $id_tax)
	{
		$ret = Db::getInstance()->executeS('
			SELECT tr.`id_tax_rule`
			FROM `'._DB_PREFIX_.'tax_rule` tr
			WHERE tr.`id_tax_rules_group` = '.(int)$id_tax_rules_group.'
			AND tr.`id_country` = '.(int)$id_country.'
			AND tr.`id_tax` = '.(int)$id_tax
		);

		if ($ret)
			return (int)$ret[0]['id_tax_rule'];
		else
			return false;
	}


	private function generateEuropeTaxRule()
	{
		$euro_vat_array = self::$europe_vat_array;
		$euro_tax_rule_grp_id = TaxRulesGroup::getIdByName((string)$this->european_vat_name);

		if (!$euro_tax_rule_grp_id)
		{
			// Create it
			$trg = new TaxRulesGroup();
			$trg->name = (string)$this->european_vat_name;
			$trg->active = 1;

			if (!$trg->save())
			{
				$this->_errors[] = Tools::displayError('Tax rule cannot be saved.');
				return false;
			}

			$euro_tax_rule_grp_id = (int)$trg->id;
		}

		$tax_rules_euro_group = TaxRule::getTaxRulesByGroupId((int)Context::getContext()->language->id, (int)$euro_tax_rule_grp_id);
		$euro_group_taxes_rules = array();

		foreach ($tax_rules_euro_group as $tax_rule)
			$euro_group_taxes_rules[] = $tax_rule;

		foreach ($euro_vat_array as $euro_vat_name => $euro_vat_details)
		{
			$posted_euro_vat = 'euro_vat_'.(string)$euro_vat_details['iso_country'];
			$posted_available_vat = 'available_vat_'.(string)$euro_vat_details['iso_country'];
			$country_id = Country::getByIso((string)$euro_vat_details['iso_country']);

			if (Tools::isSubmit($posted_euro_vat))
			{
				if (!Tools::isSubmit($posted_available_vat))
				{
					$id_tax_found = Tax::getTaxIdByName((string)$euro_vat_name);

					if ($id_tax_found !== false)
						$tax = new Tax((int)$id_tax_found);
					else
						$tax = new Tax();

					$tax->name[(int)Configuration::get('PS_LANG_DEFAULT')] = (string)$euro_vat_name;
					$tax->rate = (float)$euro_vat_details['rate'];
					$tax->active = 1;

					if (($tax->validateFields(false, true) !== true) || ($tax->validateFieldsLang(false, true) !== true))
					{
						$this->_errors[] = Tools::displayError('Invalid tax properties.');
						continue;
					}

					if (!$tax->save())
					{
						$this->_errors[] = Tools::displayError('An error occurred while saving the tax: ');
						continue;
					}

					$id_tax_rule = $this->getTaxRuleIdFromUnique($euro_tax_rule_grp_id, $country_id, $id_tax_found);

					if ($id_tax_rule !== false)
						$tr = new TaxRule((int)$id_tax_rule);
					else
						$tr = new TaxRule();

					$tr->id_tax_rules_group = (int)$euro_tax_rule_grp_id;
					$tr->id_country = (int)$country_id;
					$tr->id_state = 0;
					$tr->id_county = 0;
					$tr->zipcode_from = 0;
					$tr->zipcode_to = 0;
					$tr->behavior = 0;
					$tr->description = '';
					$tr->id_tax = (int)$tax->id;
					$tr->save();
				}
				else
				{
					$assoc_id_tax = (int)Tools::getValue($posted_available_vat);
					$id_tax_rule = $this->getTaxRuleIdFromUnique($euro_tax_rule_grp_id, $country_id, $assoc_id_tax);

					if ($id_tax_rule !== false)
						$tr = new TaxRule((int)$id_tax_rule);
					else
						$tr = new TaxRule();

					$tr->id_tax_rules_group = (int)$euro_tax_rule_grp_id;
					$tr->id_country = (int)$country_id;
					$tr->id_state = 0;
					$tr->id_county = 0;
					$tr->zipcode_from = 0;
					$tr->zipcode_to = 0;
					$tr->behavior = 0;
					$tr->description = '';
					$tr->id_tax = (int)$assoc_id_tax;
					$tr->save();
				}
			}
			else
				$this->_errors[] = Tools::displayError('Invalid parameters received');
		}
	}

	protected function _postProcess()
	{
		if (Tools::isSubmit('submitGenererateMissingVAT'))
			$this->generateEuropeTaxRule();
	}
}
