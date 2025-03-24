<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Gelf\Message;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Formatter\LineFormatter;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Logger;

use function Hyperf\Support\env;

return [
    'default' => [
        'handlers' => [
            [
                'class' => StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level' => Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],

            (env('APP_ENV') === 'dev' ? [
                'class' => GelfHandler::class,
                'constructor' => [
                    'publisher' => new Publisher(new UdpTransport(env('GRAYLOG_IP', '127.0.0.1') . '', (int) env('GRAYLOG_UDP_PORT', 12201))),
                    'level' => Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => GelfMessageFormatter::class,
                    'constructor' => [],
                ],
                'processors' => [
                    function (Message $message) {
                        $message->setAdditional('stream', env('GRAYLOG_STREAM_ID'));
                        return $message;
                    }
                ],
            ] : []),
        ],
    ],
];
