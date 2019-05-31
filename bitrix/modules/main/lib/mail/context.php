<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2017 Bitrix
 */
namespace Bitrix\Main\Mail;

class Context
{
	const CAT_EXTERNAL = 1;

	protected $category;
	protected $smtp;

	public function __construct(array $params = null)
	{
		if (!empty($params) && is_array($params))
		{
			foreach ($params as $name => $value)
			{
				$setter = sprintf('set%s', $name);
				if (is_callable(array($this, $setter)))
					$this->$setter($value);
			}
		}
	}

	/**
	 * @param int $category See Context CAT_* constants.
	 * @return $this
	 */
	public function setCategory($category)
	{
		$this->category = $category;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @param Smtp\Config $config Smtp config.
	 * @return $this
	 */
	public function setSmtp(Smtp\Config $config)
	{
		$this->smtp = $config;
		return $this;
	}

	/**
	 * @return Smtp\Config|null
	 */
	public function getSmtp()
	{
		return $this->smtp;
	}
}
