<?php
namespace common\estate\rets;

class MF extends \common\estate\Rets
{
	public function getUnitItems()
    {
        $count = intval($this->no_units);
        
        $items = [];
        for($unitNo=1;$unitNo<=$count;$unitNo++) {
            $items[$unitNo] = [
                'no'=>$unitNo,
                'rms'=>$this->getUnitData('rms', $unitNo),
                'beds'=>$this->getUnitData('bedrms', $unitNo),
                'baths'=>$this->getUnitBathCount($unitNo),
                'rent'=>$this->getUnitData('rent', $unitNo, '')
            ];
        }

        return $items;
    }

    public function getUnitBathCount($unitNo)
    {
        $returnValue = '';

        if($fullBathCount = intval($this->getUnitData('f_bths', $unitNo))) {
            $returnValue = t('rets', '{count} full', ['count'=>$fullBathCount]);
        }
        if($halfBathCount = intval($this->getUnitData('h_bths', $unitNo))) {
            $returnValue .= ', '.t('rets', '{count} half', ['count'=>$halfBathCount]);
        }

        return $returnValue == '' ? \common\rets\record\Mls::unknown() : $returnValue;
    }

    public function getUnitData($attribute, $unitNo, $connector='_')
    {
        $value = $this->getData("{$attribute}{$connector}{$unitNo}");
        return (! $value || $value == '') ? \common\rets\record\Mls::unknown() : $value;
    }
}