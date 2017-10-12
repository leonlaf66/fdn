<?php
namespace models;

class YellowPageTag extends ActiveRecord
{
    public static function model($className=__CLASS__)  
    {  
        return parent::model($className);  
    }
    
    public static function tableName()
    {
        return 'yellow_page_tag';
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