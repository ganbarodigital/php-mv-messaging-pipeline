<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   MessagingPipeline/Operations
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-pipeline
 */

namespace GanbaroDigital\MessagingPipeline\V1\Operations;

use GanbaroDigital\MessagingPipeline\V1\Exceptions\CannotJsonEncode;

/**
 * convert a PHP value to JSON encoding format, with error detection!
 */
class EncodeJson
{
    /**
     * convert a PHP value to JSON encoding format, with error detection!
     *
     * @param  mixed $item
     *         the PHP value that you want to encode
     * @param  string $fieldOrVarName
     *         what is $item called in your code?
     * @return string
     *         the JSON-encoded version of $item
     *
     * @throws CannotJsonEncode
     *         if the PHP value cannot be represented in JSON encoding format
     */
    public static function from($item, $fieldOrVarName='$item') : string
    {
        // deal with encoding problems
        $errorMessage = null;
        set_error_handler(function ($errno, $errstr) use (&$errorMessage) {
            $errorMessage = $errstr;
        });
        $retval = json_encode($item);
        restore_error_handler();

        // did we manage to encode it?
        if ($errorMessage || !$retval) {
            // something went wrong
            throw CannotJsonEncode::newFromInputParameter($item, $fieldOrVarName, ['PHP_error' => $errorMessage]);
        }

        // if we get here, all is well
        return $retval;
    }
}