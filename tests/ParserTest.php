<?php

use PHPUnit\Framework\TestCase;
use App\CsvParser;
use App\ProductProcessor;
use App\UniqueCombinationWriter;

class ParserTest extends TestCase
{
    private $csvParserMock;
    private $productProcessorMock;
    private $uniqueCombinationWriterMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->csvParserMock = $this->createMock(CsvParser::class);
        $this->productProcessorMock = $this->createMock(ProductProcessor::class);
        $this->uniqueCombinationWriterMock = $this->getMockBuilder(UniqueCombinationWriter::class)
                                                  ->disableOriginalConstructor()
                                                  ->getMock();
    }

    public function testParserProcessesFileAndWritesUniqueCombinations()
    {
        $filePath = './files/test_input.csv';
        $outputPath = './files/test_output.csv';

        // Mock product data
        $mockProductData = (function () {
            yield (object)[
                'make' => 'BrandA',
                'model' => 'ModelX',
                'colour' => 'Red',
                'capacity' => '64GB',
                'network' => 'Unlocked',
                'grade' => 'A',
                'condition' => 'New',
            ];
            yield (object)[
                'make' => 'BrandA',
                'model' => 'ModelX',
                'colour' => 'Red',
                'capacity' => '64GB',
                'network' => 'Unlocked',
                'grade' => 'A',
                'condition' => 'New',
            ];
            yield (object)[
                'make' => 'BrandB',
                'model' => 'ModelY',
                'colour' => 'Blue',
                'capacity' => '128GB',
                'network' => 'Locked',
                'grade' => 'B',
                'condition' => 'Used',
            ];
        })();

        $this->productProcessorMock->method('process')
            ->with($filePath)
            ->willReturn($mockProductData);

        $this->uniqueCombinationWriterMock->expects($this->exactly(2))
            ->method('write')
            ->willReturnCallback(function ($outputPath, $data) {
                static $callCount = 0;
                
                if ($callCount === 0) {
                    $expectedData = [
                        json_encode([
                            'make' => 'BrandA',
                            'model' => 'ModelX',
                            'colour' => 'Red',
                            'capacity' => '64GB',
                            'network' => 'Unlocked',
                            'grade' => 'A',
                            'condition' => 'New',
                        ]) => 2,
                    ];
                    $this->assertEquals('./files/test_output.csv', $outputPath);
                    $this->assertEquals($expectedData, $data);
                } elseif ($callCount === 1) {
                    $expectedData = [
                        json_encode([
                            'make' => 'BrandB',
                            'model' => 'ModelY',
                            'colour' => 'Blue',
                            'capacity' => '128GB',
                            'network' => 'Locked',
                            'grade' => 'B',
                            'condition' => 'Used',
                        ]) => 1,
                    ];
                    $this->assertEquals('./files/test_output.csv', $outputPath);
                    $this->assertEquals($expectedData, $data);
                }
        
                $callCount++;
            });

        // Instantiate and run the parser script logic
        $parser = new CsvParser();
        $processor = new ProductProcessor($parser);
        $uniqueCombinations = [];
        $batchSize = 2; // Process 2 rows at a time
        $processedRows = 0;

        foreach ($processor->process($filePath) as $product) {
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

            if ($processedRows % $batchSize === 0) {
                $this->uniqueCombinationWriterMock->write($outputPath, $uniqueCombinations);
                $uniqueCombinations = [];
            }
        }

        if (!empty($uniqueCombinations)) {
            $this->uniqueCombinationWriterMock->write($outputPath, $uniqueCombinations);
        }
    }
}
