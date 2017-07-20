<?php
namespace common\yellowpage;

class YellowPageTag extends \yii\db\ActiveRecord  
{  
    public static function model($className=__CLASS__)  
    {  
        return parent::model($className);  
    }

    public function tableName()  
    {  
        return 'catalog_yellow_page_tags';
    }

	public function rules()
	{
	    return array(
	        
	    );
	}

	public function search()
	{
	        // Warning: Please modify the following code to remove attributes that
	        // should not be searched.
	        $criteria=new CDbCriteria;
	 
	        return new CActiveDataProvider($this, array(
	                'criteria'=>$criteria
	        ));
	}
}