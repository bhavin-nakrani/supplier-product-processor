<?php

namespace App;

class CsvParser implements ParserInterface
{
    public function parse(string $filePath): \Generator
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);

        if (!$headers) {
            throw new \Exception("Failed to read headers from file.");
        }
        
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row) || array_filter($row) === []) {
              continue;
            }
            
            yield array_combine($headers, $row); // Process one row at a time
        }

        fclose($handle);
    }
}
