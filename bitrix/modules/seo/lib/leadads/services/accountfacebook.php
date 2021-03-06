<?

namespace Bitrix\Seo\LeadAds\Services;

use \Bitrix\Seo\LeadAds\Account;


class AccountFacebook extends Account
{
	const TYPE_CODE = 'facebook';

	const URL_ACCOUNT_LIST = 'https://www.facebook.com/bookmarks/pages';

	protected static $listRowMap = array(
		'ID' => 'ID',
		'NAME' => 'NAME',
	);

	public function getRowById($id)
	{
		$list = $this->getList();
		while ($row = $list->fetch())
		{
			if ($row['ID'] == $id)
			{
				return $row;
			}
		}

		return null;
	}

	public function getList()
	{
		return $this->getRequest()->send(array(
			'method' => 'GET',
			'endpoint' => 'me/accounts',
			'fields' => array(
				'fields' => 'id,name,category,access_token'
			)
		));
	}

	public function getProfile()
	{
		$response = $this->getRequest()->send(array(
			'method' => 'GET',
			'endpoint' => 'me/',
			'fields' => array(
				'fields' => 'id,name,picture,link'
			)
		));


		if ($response->isSuccess())
		{
			$data = $response->fetch();
			return array(
				'ID' => $data['ID'],
				'NAME' => $data['NAME'],
				'LINK' => $data['LINK'],
				'PICTURE' => $data['PICTURE'] ? $data['PICTURE']['data']['url'] : null,
			);
		}
		else
		{
			return null;
		}
	}
}