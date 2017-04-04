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
 * @package   MessagingMiddleware/Operations
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigital\MessagingMiddleware\V1\Operations;

use GanbaroDigital\MessagingMiddleware\V1\Exceptions\CannotBase64Decode;

/**
 * decode a string from base64 encoding, with added error detection!!
 */
class DecodeBase64
{
    /**
     * decode a string from bsae64 encoding, with added error detection!!
     *
     * @param  string $item
     *         the base64-encoded data, which we will decode
     * @param  string $fieldOrVarName
     *         what is $item called in your own code?
     * @return string
     *         the original $item, before it was base64-encoded
     *
     * @throws CannotBase64Decode
     *         if PHP's base64_decode() rejects $item
     */
    public static function from(string $item, $fieldOrVarName='$item') : string
    {
        // deal with decoding problems
        $errorMessage = null;
        set_error_handler(function ($errno, $errstr) use (&$errorMessage) {
            $errorMessage = $errstr;
        });
        $retval = base64_decode($item, true);
        restore_error_handler();

        // did we manage to decode it?
        if ($errorMessage || !$retval) {
            // something went wrong
            throw CannotBase64Decode::newFromInputParameter($item, $fieldOrVarName, ['PHP_error' => $errorMessage]);
        }

        // if we get here, all is well
        return $retval;
    }
}