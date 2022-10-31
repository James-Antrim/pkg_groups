<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\FormField;

class Patterns extends FormField
{
	private const
		EMAIL = '^([\w\d\-_\.]+)@([\w\d\-_\.]+)$',
		NAME = '^([a-zß-ÿ]+ )*([a-zß-ÿ]+\')?[A-ZÀ-ÖØ-Þ](\.|[a-zß-ÿ]+)([ |-]([a-zß-ÿ]+ )?([a-zß-ÿ]+\')?[A-ZÀ-ÖØ-Þ](\.|[a-zß-ÿ]+))*$',
		NAME_SUPPLEMENT = '^[A-ZÀ-ÖØ-Þa-zß-ÿ ,.\-()†]+$',
		NONE = '',
		OTHER = '-1',
		TELEPHONE_EU = '^(\+[\d]+ ?)?( ?((\(0?[\d]*\))|(0?[\d]+(\/| \/)?)))?(([ \-]|[\d]+)+)$',
		TEXT = '^[^<>{}]+$';

	private array $patterns_de = [
		self::EMAIL           => 'E-Mail',
		self::NAME            => 'Name',
		self::NAME_SUPPLEMENT => 'Namenszusatz',
		self::NONE            => 'Keine',
		self::OTHER           => 'Eigene',
		self::TELEPHONE_EU    => 'Telefon (EU)',
		self::TEXT            => 'Einfaches Text'
	];

	private array $patterns_en = [
		self::EMAIL           => 'E-Mail',
		self::NAME            => 'Name',
		self::NAME_SUPPLEMENT => 'Name Supplement',
		self::NONE            => 'None',
		self::OTHER           => 'Custom',
		self::TELEPHONE_EU    => 'Telephone (EU)',
		self::TEXT            => 'Simple Text'
	];

	// TODO: two part input
	// TODO: first part select from preset patterns
	// TODO: second part text, show on other selection load where pattern is explicitly set and not existent
}