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
 * @package   MessagingPipeline/Instructions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-pipeline
 */

namespace GanbaroDigital\MessagingPipeline\V1\Instructions;

use GanbaroDigital\MessagingPipeline\V1\InstructionTypes;
use GanbaroDigital\MessagingPipeline\V1\NextInstruction;
use GanbaroDigital\MessagingPipeline\V1\Operations;
use GanbaroDigital\MessagingPipeline\V1\Requirements;

/**
 * base class for all MessagingPipelineInstructions that work with
 * encryption
 */
abstract class EncryptionBaseClass implements InstructionTypes\StringInMixedOut
{
    /**
     * what encryption cipher are we using?
     *
     * @var string
     */
    protected $encryptionType;

    /**
     * a shared secret password, used for encrypting and decrypting the data
     *
     * @var string
     */
    protected $encryptionKey;

    /**
     * the initialisation vector, which we are calling a 'shared secret'
     *
     * @var string
     */
    protected $encryptionSecret;

    /**
     * creates a MessagingPipelineInstruction that's ready to use
     *
     * expects the following items in $config
     * - $config['type'] - the encryption cipher
     * - $config['key'] - a shared password
     * - $config['secret'] - the initialisation vector
     *
     * @param array $config
     *        the config we need to do our work
     */
    public function __construct(array $config)
    {
        // robustness!!
        Requirements\RequireConfigHasKey::apply('type')->to($config);
        Requirements\RequireConfigHasKey::apply('key')->to($config);
        Requirements\RequireConfigHasKey::apply('secret')->to($config);

        $this->encryptionType = $config['type'];
        $this->encryptionKey = $config['key'];
        $this->encryptionSecret = $config['secret'];
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