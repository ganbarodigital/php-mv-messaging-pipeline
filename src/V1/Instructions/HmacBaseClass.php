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
 * @package   MessagingMiddleware/Instructions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigital\MessagingMiddleware\V1\Instructions;

use GanbaroDigital\MessagingMiddleware\V1\InstructionTypes;
use GanbaroDigital\MessagingMiddleware\V1\NextInstruction;
use GanbaroDigital\MessagingMiddleware\V1\Operations;
use GanbaroDigital\MessagingMiddleware\V1\Requirements;

/**
 * base class for MessagingPipelineInstructions that work with HMACs
 */
abstract class HmacBaseClass implements InstructionTypes\StringInMixedOut
{
    /**
     * which HMAC algorithm are we using?
     * @var string
     */
    protected $hmacType;

    /**
     * what shared secret key are we using for calculating the HMAC?
     * @var string
     */
    protected $hmacKey;

    /**
     * creates a MessagePipelineInstruction that's ready to use
     *
     * expects the following config:
     * - $config['type'] - a HMAC algorithm
     * - $config['key'] - a shared secret key
     *
     * @param array $config
     *        the config that we require
     */
    public function __construct(array $config)
    {
        // robustness
        Requirements\RequireConfigHasKey::apply('type')->to($config);
        Requirements\RequireConfigHasKey::apply('key')->to($config);

        $this->hmacType = $config['type'];
        $this->hmacKey = $config['key'];
    }

    /**
     * executes whatever work this messaging pipeline instruction
     * is here to do
     *
     * @param  NextInstruction $next
     *         the instruction to call when our work is done
     * @param  string $payload
     *         the data that we're processing
     * @return mixed
     *         whatever $next() returns when we call it
     */
    abstract public function __invoke(NextInstruction $next, string $payload);
}