<?php

declare(strict_types=1);

use GanbaroDigital\MessagingPipeline\V1\BuildMessagingPipelines;
use GanbaroDigital\MessagingPipeline\V1\InstructionBuilders;

require __DIR__ . "/../vendor/autoload.php";

// you would normally get this from your DI container or framework
$config = [
    'encryption' => [
        "type" => "AES-256-CBC",
        "key" => "1234567890ABCDEF",
        "secret" => "FEDCBA0987654321"
    ],
    'hmac' => [
        'type' => 'sha256',
        'key' => 'the messaging bus lives!'
    ]
];

// a list of the instructions that make up our MessagingPipeline, and
// the config that each one consumes
$instructionsList = [
    InstructionBuilders\JsonPayloadSupport::class => [],
    InstructionBuilders\EncryptedPayloadSupport::class => $config['encryption'],
    InstructionBuilders\SignedPayloadSupport::class => $config['hmac'],
    InstructionBuilders\AsciiSafePayloadSupport::class => []
];

// build our two pipelines
$pipelines = BuildMessagingPipelines::from($instructionsList);

// what data do we want to send?
$origData = (object)[
    'hello' => 'world'
];
var_dump($origData);

// let's create a message, suitable for sending over Amazon SQS
$message = $pipelines[BuildMessagingPipelines::TRANSMIT_BUS]($origData);
var_dump($message);

// now, let's turn that message back into the data, as if we have received
// the message from Amazon SQS
$receivedData = $pipelines[BuildMessagingPipelines::RECEIVE_BUS]($message);
var_dump($receivedData);