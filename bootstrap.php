<?php

use Maximaster\Tools\Events\Listener;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    return false;
}

$eventListener = new Listener();
$eventListener->addNamespace(
    '\\Maximaster\\Tools\\Finder\\EventHandlers',
    __DIR__ . '/src/EventHandlers'
);
$eventListener->register();

