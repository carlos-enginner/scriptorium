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
require_once __DIR__ . '/vendor/autoload.php';

use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Monolog\Handler\GelfHandler;
use Monolog\Logger;

// Configuração do Publisher para enviar para o Graylog
$publisher = new Publisher(
    new UdpTransport('graylog', 12201),  // Substitua pelo IP e a porta do seu Graylog
);

// Criação do Handler com o GELF
$gelfHandler = new GelfHandler($publisher, Logger::DEBUG);

// Criação do Logger
$logger = new Logger('app.scriptorium');
$logger->pushHandler($gelfHandler);

// Envia um log de teste para o Graylog
$logger->info('Este é um teste simples de log enviado para o Graylog no stream app.scriptorium!', ['context' => 'teste']);

// Saída para confirmar que o script foi executado
echo 'Log de teste enviado para o Graylog no stream app.scriptorium!';
