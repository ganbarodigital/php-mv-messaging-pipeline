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

namespace GanbaroDigitalTest\MessagingPipeline\V1\Instructions;

use GanbaroDigital\MessagingPipeline\V1\Instructions\DecodePayloadFromJson;
use GanbaroDigital\MessagingPipeline\V1\NextInstruction;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass GanbaroDigital\MessagingPipeline\V1\Instructions\DecodePayloadFromJson
 */
class DecodePayloadFromJsonTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_can_instantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DecodePayloadFromJson;

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DecodePayloadFromJson::class, $unit);
    }

    /**
     * @covers ::__invoke
     */
    public function test_will_decode_json_data()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = ["this is a string"];
        $input = json_encode($expectedResult);

        $unit = new DecodePayloadFromJson;
        $nextInstruction = new NextInstruction([$unit]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $nextInstruction($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideNonStrings
     * @expectedException TypeError
     */
    public function test_rejects_non_strings_in_strict_typing($input)
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DecodePayloadFromJson;
        $nextInstruction = new NextInstruction([$unit]);

        // ----------------------------------------------------------------
        // perform the change

        $nextInstruction($input);

        // ----------------------------------------------------------------
        // test the results
        //
        // by this point, the PHP engine should have thrown a TypeError
    }

    public function provideNonStrings()
    {
        // I've commented out the values that PHP happily coerces into
        // strings

        return [
            'null' => [ null ],
            'array(empty)' => [ [] ],
            'array(simple)' => [ [ 1, 2, 3, 4 ] ],
            // 'bool(true)' => [ true ],
            // 'bool(false)' => [ false ],
            'callable(returns string)' => [ function() { return ''; } ],
            // 'double(0)' => [ 0.0 ],
            // 'double(negative)' => [ -100.01 ],
            // 'double(positive)' => [ 3.1415927 ],
            // 'int(0)' => [ 0 ],
            // 'int(negative)' => [ -100 ],
            // 'int(positive)' => [ 100 ],
            'object(empty)' => [ (object)[] ],
            'resource' => [STDIN],
        ];
    }
}