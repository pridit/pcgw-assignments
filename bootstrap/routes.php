<?php

$regex = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../routes')), "/\/*.php$/i");

foreach ($regex as $item) {
    include $item->getPathname();
}
