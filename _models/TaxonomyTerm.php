<?php
namespace models;

class TaxonomyTerm extends ActiveRecord
{
    public static function tableName()
    {
        return 'taxonomy_term';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}