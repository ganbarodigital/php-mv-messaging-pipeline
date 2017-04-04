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
 * do we have an encryption initialisation vector that we can use?
 *
 * the initialisation vector (IV for short) is often used in configs
 * as a 'shared secret'
 */
class IsValidEncryptionIV implements Check, ListCheck
{
    // saves us having to implement inspectList() ourselves
    use ListCheckHelper;

    /**
     * what kind of encryption are we using?
     * @var string
     */
    private $encryptionType;

    /**
     * constructor
     *
     * @param string $encryptionType
     *        the OpenSSL encryption cipher that we are using
     */
    public function __construct(string $encryptionType)
    {
        $this->encryptionType = $encryptionType;
    }

    /**
     * do we have an encryption initialisation vector that we can use?
     *
     * the initialisation vector (IV for short) is often used in configs
     * as a 'shared secret'
     *
     * @param  string $encryptionType
     *         what kind of encryption are we using?
     * @param  string $iv
     *         what initialisation vector are we checking?
     * @return bool
     *         TRUE if $iv is a valid initialisation vector for the given
     *         $encryptionType
     *         FALSE otherwise
     */
    public static function check(string $encryptionType, string $iv) : bool
    {
        // deal with a bad cipher
        $errorMessage = null;
        set_error_handler(function ($errno, $errstr) use (&$errorMessage) {
            $errorMessage = $errstr;
        });
        $requiredLen = openssl_cipher_iv_length($encryptionType);
        restore_error_handler();

        // was there a problem?
        if ($errorMessage || $requiredLen === false) {
            return false;
        }

        // if we get here, then we can check that we have enough bytes
        return (strlen($iv) === $requiredLen);
    }

    /**
     * do we have an encryption type that we can use?
     *
     * @param  string $iv
     *         what initialisation vector are we checking?
     * @return bool
     *         TRUE if $iv is a valid initialisation vector for the given
     *         $encryptionType
     *         FALSE otherwise
     */
    public function inspect($iv)
    {
        // we are just a wrapper around our stateless check
        return static::check($this->encryptionType, $iv);
    }

    /**
     * do we have an encryption type that we can use?
     *
     * @param  string $encryptionType
     *         what kind of encryption are we using?
     * @param  array|Traversable $list
     *         the list of initialisation vectors to examine
     * @return bool
     *         TRUE if all the items in $list are valid initialisation vectors
     *         for the given $encryptionType
     *         FALSE otherwise
     */
    public static function checkList(string $encryptionType, $list) : bool
    {
        // we are just a wrapper around our OO list inspector
        $inspector = new static($encryptionType);
        return $inspector->inspectList($list);
    }
}