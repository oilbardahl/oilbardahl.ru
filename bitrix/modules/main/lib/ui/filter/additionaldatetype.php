<?

namespace Bitrix\Main\UI\Filter;


/**
 * Class AdditionalDateType. Available additional subtypes of date field
 * @package Bitrix\Main\UI\Filter
 */
class AdditionalDateType
{
	const CUSTOM_DATE = "CUSTOM_DATE";


	/**
	 * Gets subtypes list of date field
	 * @return array
	 */
	public static function getList()
	{
		$reflection = new \ReflectionClass(__CLASS__);
		return $reflection->getConstants();
	}
}