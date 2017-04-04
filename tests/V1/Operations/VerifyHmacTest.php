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

namespace GanbaroDigitalTest\MessagingMiddleware\V1\Operations;

use GanbaroDigital\MessagingMiddleware\V1\Operations\VerifyHmac;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass GanbaroDigital\MessagingMiddleware\V1\Operations\VerifyHmac
 */
class VerifyHmacTest extends TestCase
{
    /**
     * @covers ::for
     */
    public function test_will_verify_string_against_expected_HMAC()
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = "this is a string";
        $hashType = "sha256";
        $hashKey = "this is an insecure key";
        $expectedHmac = hash_hmac($hashType, $input, $hashKey);

        // ----------------------------------------------------------------
        // perform the change

        VerifyHmac::for($input, $expectedHmac, $hashType, $hashKey);

        // ----------------------------------------------------------------
        // test the results
        //
        // if VerifyHmac::for() fails, it throws an exception
        // so if we get here, then we think the HMAC has been verified

        $this->assertTrue(true);
    }

    /**
     * @covers ::for
     * @expectedException GanbaroDigital\MessagingMiddleware\V1\Exceptions\HmacVerificationFailed
     */
    public function test_throws_HmacVerificationFailed_if_incorrect_hmac()
    {
        // ----------------------------------------------------------------
        // setup your test

        $input = "this is a string";
        $hashType = "sha256";
        $hashKey = "this is an insecure key";
        $expectedHmac = "not the correct HMAC at all!";

        // ----------------------------------------------------------------
        // perform the change

        VerifyHmac::for($input, $expectedHmac, $hashType, $hashKey);

        // ----------------------------------------------------------------
        // test the results
        //
        // by this point, VerifyHmac::for() should have thrown an exception
    }

    /**
     * @covers ::for
     * @dataProvider provideNonStrings
     * @expectedException TypeError
     */
    public function test_rejects_non_strings_in_strict_typing($input)
    {
        // ----------------------------------------------------------------
        // setup your test

        $hashType = "sha256";
        $hashKey = "this is an insecure key";
        $expectedHmac = 'not a real HMAC';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = VerifyHmac::for($input, $expectedHmac, $hashType, $hashKey);

        // ----------------------------------------------------------------
        // test the results
        //
        // at this point, the PHP engine should have thrown a TypeError
    }

    /**
     * @covers ::for
     * @expectedException GanbaroDigital\MessagingMiddleware\V1\Exceptions\UnsupportedHmacAlgorithm
     */
    public function test_throws_UnsupportedHmacAlgorithm_if_hashType_is_invalid()
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        VerifyHmac::for('', 'not a HMAC', 'not a hash algo', '12345');

        // ----------------------------------------------------------------
        // test the results
        //
        // at this point, VerifyHmac::to() should have thrown an exception
    }


    public function provideNonStrings()
    {
        return [
            'null' => [ null ],
            'array(empty)' => [ [] ],
            'array(simple)' => [ [ 1, 2, 3, 4 ] ],
            'bool(true)' => [ true ],
            'bool(false)' => [ false ],
            'callable(returns string)' => [ function() { return ''; } ],
            'double(0)' => [ 0.0 ],
            'double(negative)' => [ -100.01 ],
            'double(positive)' => [ 3.1415927 ],
            'int(0)' => [ 0 ],
            'int(negative)' => [ -100 ],
            'int(positive)' => [ 100 ],
            'object(empty)' => [ (object)[] ],
            'resource' => [STDIN],
        ];
    }
}