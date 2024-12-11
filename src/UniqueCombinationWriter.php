<?php

namespace App;

class UniqueCombinationWriter
{
    public function write(string $filePath, array $combinations): void
    {
        $fileExists = file_exists($filePath);
        $handle = fopen($filePath, ($fileExists) ? 'a' : 'w'); // Append mode or write mode

        // Write the header row
        if (!$fileExists) {
            fputcsv($handle, ['make', 'model', 'colour', 'capacity', 'network', 'grade', 'condition', 'count']);
        }
        
        foreach ($combinations as $combination => $count) {
            $data = json_decode($combination, true);
            $data['count'] = $count;
            fputcsv($handle, $data);
        }

        fclose($handle);
    }
}
