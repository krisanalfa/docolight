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

    protected static $localeDays = array(
        'id' => array(
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jum\'at',
            6 => 'Sabtu',
            7 => 'Minggu',
        ),
    );

    /**
     * Format date to Indonesia Locale.
     *
     * @param string $date
     * @param string $format
     * @param string $returnFormat
     *
     * @return string|null
     */
    public static function formatId($date, $format = 'Y-m-d H:i:s', $returnFormat = 'D, d M Y')
    {
        $date = Carbon::createFromFormat($format, $date);

        return ($date) ? static::formatLocale($date, 'id', $returnFormat)
                       : null;
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
        $date->setLocale('en');

        $replacedMonth = str_replace($date->format('M'), static::localeMonth($date, $locale), $date->format($returnFormat));
        $replacedDay = str_replace($date->format('D'), static::localeDay($date, $locale), $replacedMonth);

        return $replacedDay;
    }

    /**
     * Get month index. Useful for translation.
     *
     * @param \Carbon\Carbon $date
     *
     * @return int|null
     */
    public static function monthIndex(Carbon $date)
    {
        $date->setLocale('en');

        return def(static::$month, $date->format('M'));
    }

    public static function dayIndex(Carbon $date)
    {
        $date->setLocale('en');

        return def(static::$day, $date->format('D'));
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
     * Get translated locale day
     *
     * @param \Carbon\Carbon $date
     * @param string         $locale
     *
     * @return string|null
     */
    public static function localeDay(Carbon $date, $locale)
    {
        return def(static::localeDays($locale), static::dayIndex($date));
    }

    /**
     * Get days name lists
     *
     * @return array
     */
    public static function localeDays($locale)
    {
        return def(static::$localeDays, $locale, array());
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
