<?php

namespace App;

interface ParserInterface
{
    public function parse(string $filePath): \Generator;
}
