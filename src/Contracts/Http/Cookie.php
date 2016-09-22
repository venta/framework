<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

/**
 * Interface Cookie
 *
 * @package Venta\Contracts\Http
 */
interface Cookie
{
    /**
     * @param $string string DateInterval format
     * @return int timestamp
     */
    public static function inDateInterval(string $string);

    /**
     * @param $days int
     * @return int timestamp
     */
    public static function inDays($days);

    /**
     * @param $hours int
     * @return int timestamp
     */
    public static function inHours($hours);

    /**
     * @param $minutes int
     * @return int timestamp
     */
    public static function inMinutes($minutes);

    /**
     * @param $months int
     * @return int timestamp
     */
    public static function inMonths($months);

    /**
     * @param $weeks int
     * @return int timestamp
     */
    public static function inWeeks($weeks);

    /**
     * @return int timestamp older then now();
     */
    public static function outdated();

    /**
     * Method that transforms Class to a plain text to include it in Response header
     *
     * @return mixed
     */
    public function asPlainText();

    /**
     * @return string
     */
    public function getDomain();

    /**
     * @return mixed
     */
    public function getExpireTime();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string;
     */
    public function getValue();

    /**
     * @return bool
     */
    public function isHttpOnly();

    /**
     * @return bool
     */
    public function isSecure();
}