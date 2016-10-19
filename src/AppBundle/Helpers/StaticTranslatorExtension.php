<?php
namespace AppBundle\Helpers;


use AppBundle\Entity\Item;

class StaticTranslatorExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('typeText', [$this, 'typeTextFilter']),
        );
    }

    public static function typeTextFilter($type, $plural = false)
    {
        if ($plural) {
            switch ($type) {
                case Item::TYPE_VEGETABLE:
                    return 'Vegetables';
                case Item::TYPE_FRUIT:
                    return 'Fruits';
                case Item::TYPE_HERB:
                    return 'Herbs';
                case Item::TYPE_NUT:
                    return 'Nuts';
                default:
                    return 'Unknown';
            }
        }
        switch ($type) {
            case Item::TYPE_VEGETABLE:
                return 'Vegetable';
            case Item::TYPE_FRUIT:
                return 'Fruit';
            case Item::TYPE_HERB:
                return 'Herb';
            case Item::TYPE_NUT:
                return 'Nut';
            default:
                return 'Unknown';
        }
    }
}