<?php

namespace App;

class ProductProcessor
{
    private ParserInterface $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function process(string $filePath): \Generator
    {
        foreach ($this->parser->parse($filePath) as $row) {
            yield new Product($row);
        }
    }
}
