<?php

use Maximaster\Tools\Events\Listener;

$eventListener = new Listener();
$eventListener->addNamespace(
    '\\Maximaster\\Tools\\Finder\\EventHandlers',
    __DIR__ . '/src/EventHandlers'
);
$eventListener->register();

