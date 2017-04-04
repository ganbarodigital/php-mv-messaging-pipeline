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

use GanbaroDigital\MessagingMiddleware\V1\Requirements\RequireValidEncryptionCipher;
use GanbaroDigital\MessagingMiddleware\V1\Requirements\RequireValidEncryptionIV;

/**
 * create an encrypted copy of a string
 */
class EncryptString
{
    /**
     * create an encrypted copy of a string
     *
     * @param  string $item
     *         the string that you want to encrypt
     * @param  string $fieldOrVarName
     *         what do you call $item in your own code?
     * @param  string $encryptionType
     *         what kind of encryption cipher do you want to use?
     *         this must be supported by OpenSSL
     * @param  string $encryptionKey
     *         a password that you will share with the person who needs
     *         to decrypt the string
     * @param  string $iv
     *         the initialisation vector to use to encrypt the string
     * @return string
     *         the encrypted copy of $item
     */
    public static function from(string $item, string $fieldOrVarName, string $encryptionType, string $encryptionKey, string $iv) : string
    {
        // robustness!
        RequireValidEncryptionCipher::apply()->to($encryptionType, '$encryptionType');
        RequireValidEncryptionIV::apply($encryptionType)->to($iv, '$iv');

        // if we get to here, then we are confident that this will always
        // succeed!
        return openssl_encrypt(
            $item,
            $encryptionType,
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}