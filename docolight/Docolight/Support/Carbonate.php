<?php

namespace Docolight\Support;

use Carbon\Carbon;

/**
 * Date helper.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Carbonate
{
    protected static $month = array(
        'Jan' => 1,
        'Feb' => 2,
        'Mar' => 3,
        'Apr' => 4,
        'May' => 5,
        'Jun' => 6,
        'Jul' => 7,
        'Aug' => 8,
        'Sep' => 9,
        'Oct' => 10,
        'Nov' => 11,
        'Dev' => 12,
    );

    protected static $day = array(
        'Mon' => 1,
        'Tue' => 2,
        'Wed' => 3,
        'Thu' => 4,
        'Fri' => 5,
        'Sat' => 6,
        'Sun' => 7,
    );

    protected static $localeMonth = array(
        'id' => array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ),
    );

    /**
     * Format date to Indonesia Locale.
     *
     * @param string $date
     * @param string $format
     *
     * @return string|null
     */
    public static function formatId($date, $format = 'Y-m-d H:i:s')
    {
        $date = Carbon::createFromFormat($format, $date);

        return ($date) ? static::formatLocale($date, 'id', 'd M Y')
                       : null;
    }

    /**
     * Get month index. Useful for translation.
     *
     * @param \Carbon\Carbon $date
     *
     * @return int|null
     */
    public function monthIndex(Carbon $date)
    {
        return def(static::$month, $date->format('M'));
    }

    /**
     * Format our locale date
     *
     * @param Carbon\Carbon $date
     * @param string        $locale
     * @param string        $returnFormat
     *
     * @return string
     */
    public static function formatLocale(Carbon $date, $locale, $returnFormat)
    {
        return str_replace($date->format('M'), static::localeMonth($date, $locale), $date->format($returnFormat));
    }

    /**
     * Get translated locale month
     *
     * @param \Carbon\Carbon $date
     * @param string         $locale
     *
     * @return string|null
     */
    public static function localeMonth(Carbon $date, $locale)
    {
        return def(static::localeMonths($locale), static::monthIndex($date));
    }

    /**
     * Get month name lists
     *
     * @return array
     */
    public static function localeMonths($locale)
    {
        return def(static::$localeMonth, $locale, array());
    }

    /**
     * Return differ time in hours.
     *
     * @param string $start  Date you want to start from.
     * @param string $end    Date you want to calculate the difference from the start.
     * @param string $format The `$start` and `$end` format. They must be in the in same format.
     *
     * @return int|null
     */
    public static function diff($start, $end, $format = 'Y-m-d')
    {
        $start = Carbon::createFromFormat($format, $start);
        $end = Carbon::createFromFormat($format, $end);

        return ($start and $end) ? $start->diffInDays($end)
                                 : null;
    }
}
