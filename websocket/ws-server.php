<?php

namespace WebSocket;

require __DIR__ . '/vendor/autoload.php';

try {
    $server = new Server([
        'port'          => 8081,
        'timeout'       => 200,
        'filter'        => ['text', 'close'],
    ]);
} catch (ConnectionException $e) {
    echo "> ERROR: {$e->getMessage()}\n";
    die();
}

echo "> Listening to port {$server->getPort()}\n";

while (true) {
    while ($server->accept()) {
        echo "> Accepted on port {$server->getPort()}\n";
        while (true) {
            $message = $server->receive();
            $opcode = $server->getLastOpcode();
            if ($message === null) {
                echo "> Closing connection\n";
                continue 2;
            }
            echo "> Got '$message' [opcode: {$opcode}]\n";

            if ($message) {
                $server->text($message);
            }
        }
    }
}
