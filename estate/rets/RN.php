<?php
namespace common\estate\rets;

class RN extends \common\estate\Rets
{
	public function getFirstMonReqd()
	{
		return $this->getData('first_mon_reqd') == 'Y' ? 'Yes' : 'No';
	}
}