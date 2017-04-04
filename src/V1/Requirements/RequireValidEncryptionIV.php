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
 * @package   MessagingMiddleware/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigital\MessagingMiddleware\V1\Requirements;

use GanbaroDigital\Defensive\V1\Interfaces\ListRequirement;
use GanbaroDigital\Defensive\V1\Interfaces\Requirement;
use GanbaroDigital\Defensive\V1\Requirements\InvokeableRequirement;
use GanbaroDigital\Defensive\V1\Requirements\ListableRequirement;
use GanbaroDigital\MessagingMiddleware\V1\Checks\IsValidEncryptionCipher;
use GanbaroDigital\MessagingMiddleware\V1\Checks\IsValidEncryptionIV;
use GanbaroDigital\MessagingMiddleware\V1\Exceptions\InvalidEncryptionIV;
use GanbaroDigital\MessagingMiddleware\V1\Exceptions\UnsupportedEncryptionCipher;

/**
 * make sure that we have a valid encryption initialisation vector
 */
class RequireValidEncryptionIV implements Requirement, ListRequirement
{
    // saves us having to declare ::__invoke() ourselves
    use InvokeableRequirement;

    // saves us having to declare ::toList() ourselves
    use ListableRequirement;

    /**
     * the encryption cipher that we're checking against
     * @var string
     */
    private $encryptionCipher;

    /**
     * create a Requirement that is ready to use
     *
     * @param string $encryptionCipher
     *        the encryption cipher that we're checking against
     */
    public function __construct(string $encryptionCipher)
    {
        // robustness!!
        if (!IsValidEncryptionCipher::check($encryptionCipher)) {
            throw UnsupportedEncryptionCipher::newFromInputParameter($encryptionCipher, '$encryptionCipher');
        }

        // all is well
        $this->encryptionCipher = $encryptionCipher;
    }

    /**
     * create a Requirement that is ready to use
     *
     * @param string $encryptionCipher
     *        the encryption cipher that we're checking against
     * @return Requirement
     */
    public static function apply(string $encryptionCipher) : Requirement
    {
        return new static($encryptionCipher);
    }

    /**
     * make sure that we have a valid encryption cipher
     *
     * @param  string $item
     *         the item to examine
     * @param  string $fieldOrVarName
     *         what do you call $item in your own code?
     * @return void
     *
     * @throws UnsupportedEncryptionIV
     *         if $item isn't a valid initialisation vector for the given
     *         encryption type
     */
    public function to($item, $fieldOrVarName='$item')
    {
        // are we happy?
        if (IsValidEncryptionIV::check($this->encryptionCipher, $item)) {
            // yes we are
            return;
        }

        // if we get here, then no, we are not happy
        throw InvalidEncryptionIV::newFromInputParameter($item, $fieldOrVarName, [
            'cipher' => $this->encryptionCipher
        ]);
    }
}