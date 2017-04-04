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
 * @package   MessagingMiddleware
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigital\MessagingMiddleware\V1;

use GanbaroDigital\InstructionPipeline\V1\Interfaces\InstructionPipeline;
use GanbaroDigital\InstructionPipeline\V1\PipelineBuilders\BuildInstructionPipeline;

/**
 * factory class - assemble a uni-directional or bi-directional messaging
 * bus from the list of instructions that you supply
 */
class BuildMessagingPipelines extends BuildInstructionPipeline
{
    /**
     * this is the bus that encrypts and signs messages
     *
     * added for readability
     */
    const TRANSMIT_BUS = InstructionPipeline::DI_FORWARD;

    /**
     * this is the bus that verifies signatures and decrypts messages
     *
     * added for readability
     */
    const RECEIVE_BUS = InstructionPipeline::DI_REVERSE;

    /**
     * assemble a pipeline of instructions to execute
     *
     * @param  array $definition
     *         a list of the required instruction builders, and the configs
     *         for each builder
     * @param  int $directions
     *         which pipelines do we want to build? (bitwise mask)
     * @return NextInstruction[]
     *         the assembled pipelines
     */
    public static function from($definition, $directions = InstructionPipeline::DI_FORWARD|InstructionPipeline::DI_REVERSE, $wrapperClass = NextInstruction::class)
    {
        return parent::from($definition, $directions, $wrapperClass);
    }
}