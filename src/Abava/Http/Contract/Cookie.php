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
}