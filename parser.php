<?php

require __DIR__ . '/vendor/autoload.php';

use App\CsvParser;
use App\ProductProcessor;
use App\UniqueCombinationWriter;

$options = getopt("", ["file:", "unique-combinations:"]);

if (!isset($options['file']) || !isset($options['unique-combinations'])) {
    exit("Usage: parser.php --file=products_comma_separated.csv --unique-combinations=combination_count.csv\n");
}

$folderPath = "./files";
$filePath = $folderPath.'/'.$options['file'];
$outputPath = $folderPath.'/'.$options['unique-combinations'];

try {
    $parser = new CsvParser();
    $processor = new ProductProcessor($parser);
    $writer = new UniqueCombinationWriter();

    $uniqueCombinations = [];
    $batchSize = 100; // Process 100 rows at a time
    $processedRows = 0;

    foreach ($processor->process($filePath) as $product) {
        echo json_encode($product) . PHP_EOL;

        $key = json_encode([
            'make' => $product->make,
            'model' => $product->model,
            'colour' => $product->colour,
            'capacity' => $product->capacity,
            'network' => $product->network,
            'grade' => $product->grade,
            'condition' => $product->condition,
        ]);

        $uniqueCombinations[$key] = ($uniqueCombinations[$key] ?? 0) + 1;
        $processedRows++;

        // Write to disk in chunks
        if ($processedRows % $batchSize === 0) {
            $writer->write($outputPath, $uniqueCombinations);
            $uniqueCombinations = []; // Clear memory
        }
    }

    // Write remaining combinations
    if (!empty($uniqueCombinations)) {
        $writer->write($outputPath, $uniqueCombinations);
    }
    echo "Unique combinations written to {$outputPath}\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
