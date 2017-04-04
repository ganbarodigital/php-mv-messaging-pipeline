<?php

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
 * @package   MessagingMiddleware/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigital\MessagingMiddleware\V1\Checks;

use GanbaroDigital\MissingBits\Checks\Check;
use GanbaroDigital\MissingBits\Checks\ListCheck;
use GanbaroDigital\MissingBits\Checks\ListCheckHelper;

/**
 * do we have an encryption cipher that we can use?
 */
class IsValidEncryptionCipher implements Check, ListCheck
{
    // saves us having to implement inspectList() ourselves
    use ListCheckHelper;

    /**
     * do we have an encryption cipher that we can use?
     *
     * @param  string $fieldOrVar
     *         the item to be checked
     * @return bool
     *         TRUE if the item is a supported encryption cipher
     *         FALSE otherwise
     */
    public static function check(string $fieldOrVar) : bool
    {
        // OpenSSL will give us a list of encryption ciphers that
        // it supports
        $supportedAlgos = openssl_get_cipher_methods();
        return in_array($fieldOrVar, $supportedAlgos);
    }

    /**
     * do we have an encryption cipher that we can use?
     *
     * @param  string $fieldOrVar
     *         the item to be checked
     * @return bool
     *         TRUE if the item is a supported encryption cipher
     *         FALSE otherwise
     */
    public function inspect($fieldOrVar)
    {
        // we are just a wrapper around our stateless check
        return static::check($fieldOrVar);
    }

    /**
     * do we have an encryption cipher that we can use?
     *
     * @param  array|Traversable $list
     *         the item to be checked
     * @return bool
     *         TRUE if every item in $list is a supported encryption cipher
     *         FALSE otherwise
     */
    public static function checkList($list) : bool
    {
        // we are just a wrapper around our OO list inspector
        $inspector = new static;
        return $inspector->inspectList($list);
    }
}