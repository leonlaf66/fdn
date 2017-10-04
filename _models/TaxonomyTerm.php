<?php
namespace models;

class TaxonomyTerm extends ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_taxonomy_term';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}