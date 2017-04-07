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

use GanbaroDigital\MessagingPipeline\V1\Requirements\RequireValidHmacAlgorithm;

/**
 * create a hashed-message authentication code (HMAC) for a message
 */
class CalculateHmac
{
    /**
     * create hashed-message authentication code (HMAC) for a message
     *
     * @param  string $message
     *         the message you want to sign
     * @param  string $hashAlgo
     *         the hash algorithm you want to use
     *         this must be supported by hash_hmac()
     * @param  string $key
     *         the shared password you want to use to calculate the HMAC
     * @return string
     *         the HMAC that we calculated
     *
     * @throws UnsupportedHmacAlgorithm
     *         if $hashAlgo isn't supported by our PHP runtime
     */
    public static function for(string $message, string $hashAlgo, string $key) : string
    {
        // robustness!
        RequireValidHmacAlgorithm::apply()->to($hashAlgo);
        return hash_hmac($hashAlgo, $message, $key);
    }
}