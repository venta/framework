<?php
/**
 * Created by PhpStorm.
 * User: iljalapkovskis
 * Date: 8/5/16
 * Time: 17:06
 */

namespace Abava\Http\Contract;


Interface Cookie
{
    /**
     * Method that transforms Class to a plain text to include it in Response header
     *
     * @return mixed
     */
    public function asPlainText();

    /**
     * @param $minutes int
     * @return int timestamp
     */
    public static function inMinutes($minutes);

    /**
     * @param $hours int
     * @return int timestamp
     */
    public static function inHours($hours);

    /**
     * @param $days int
     * @return int timestamp
     */
    public static function inDays($days);

    /**
     * @param $weeks int
     * @return int timestamp
     */
    public static function inWeeks($weeks);

    /**
     * @param $months int
     * @return int timestamp
     */
    public static function inMonths($months);

    /**
     * @param $string string DateInterval format
     * @return int timestamp
     */
    public static function inDateInterval(string $string);

    /**
     * @return int timestamp older then now();
     */
    public static function outdated();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string;
     */
    public function getValue();

    /**
     * @return string
     */
    public function getDomain();

    /**
     * @return timestamp
     */
    public function getExpireTime();

    /**
     * @return bool
     */
    public function isSecure();

    /**
     * @return bool
     */
    public function isHttpOnly();
}