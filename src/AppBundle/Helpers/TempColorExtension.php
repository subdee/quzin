<?php
namespace AppBundle\Helpers;


class TempColorExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tempcolor', [$this, 'convertTempToColor']),
        );
    }

    public static function convertTempToColor($temperature)
    {
        if ($temperature < -10) {
            return 'frozen-blue';
        }
        if ($temperature < 10) {
            return 'cold-blue';
        }
        if ($temperature < 15) {
            return 'cold-green';
        }
        if ($temperature < 20) {
            return 'warm-green';
        }
        if ($temperature < 25) {
            return 'warm-yellow';
        }
        if ($temperature < 30) {
            return 'cold-orange';
        }
        if ($temperature < 40) {
            return 'warm-orange';
        }
        if ($temperature >= 40) {
            return 'red';
        }

        return '';
    }
}