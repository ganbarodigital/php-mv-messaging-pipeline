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
 * @package   MessagingPipeline
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-pipeline
 */

namespace GanbaroDigitalTest\MessagingPipeline\V1;

use GanbaroDigital\MessagingPipeline\V1\BuildMessagingPipelines;
use GanbaroDigital\MessagingPipeline\V1\InstructionBuilders;
use GanbaroDigital\MessagingPipeline\V1\Instructions;
use GanbaroDigital\MessagingPipeline\V1\NextInstruction;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass GanbaroDigital\MessagingPipeline\V1\BuildMessagingPipelines
 */
class BuildMessagingPipelineTest extends TestCase
{
    /**
     * @covers ::from
     */
    public function test_can_build_pipelines()
    {
        // ----------------------------------------------------------------
        // setup your test

        $config = [
            'encryption' => [
                "type" => "AES-256-CBC",
                "key" => "1234567890ABCDEF",
                "secret" => "FEDCBA0987654321"
            ],
            'hmac' => [
                'type' => 'sha256',
                'key' => 'the messaging pipeline lives!'
            ]
        ];

        $instructionsList = [
            InstructionBuilders\JsonPayloadSupport::class => [],
            InstructionBuilders\EncryptedPayloadSupport::class => $config['encryption'],
            InstructionBuilders\SignedPayloadSupport::class => $config['hmac'],
            InstructionBuilders\AsciiSafePayloadSupport::class => []
        ];

        $expectedPipelines = [
            BuildMessagingPipelines::TRANSMIT_PIPELINE => new NextInstruction([
                new Instructions\EncodePayloadToJson,
                new Instructions\EncryptPayload($config['encryption']),
                new Instructions\SignPayload($config['hmac']),
                new Instructions\EncodePayloadToAsciiSafeString,
            ]),
            BuildMessagingPipelines::RECEIVE_PIPELINE => new NextInstruction([
                new Instructions\DecodePayloadFromAsciiSafeString,
                new Instructions\VerifyPayloadSignature($config['hmac']),
                new Instructions\DecryptPayload($config['encryption']),
                new Instructions\DecodePayloadFromJson
            ]),
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualPipelines = BuildMessagingPipelines::from($instructionsList);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedPipelines, $actualPipelines);
    }

}