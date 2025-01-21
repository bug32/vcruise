<?php

namespace common\helpers;

use IntlDateFormatter;

class FormatDate
{

    public static function formatDate($dateString, string $format): false|string
    {
        $fmt = datefmt_create(
            'ru_RU',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Moscow',
            IntlDateFormatter::GREGORIAN,
            $format
        );

        if( !$fmt ) {
            throw new \RuntimeException('Function datefmt_create error');
        }

        return datefmt_format($fmt, strtotime($dateString));
    }
}