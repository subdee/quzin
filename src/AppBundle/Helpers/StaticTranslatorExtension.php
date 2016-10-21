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
        return ItemTypeTranslator::translate($type, $plural);
    }
}