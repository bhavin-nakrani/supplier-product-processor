<?php

namespace App;

class UniqueCombinationWriter
{
    public function write(string $filePath, array $combinations): void
    {
        $handle = fopen($filePath, 'a'); // Append mode

        // Write the header row
        fputcsv($handle, ['make', 'model', 'colour', 'capacity', 'network', 'grade', 'condition', 'count']);

        foreach ($combinations as $combination => $count) {
            $data = json_decode($combination, true);
            $data['count'] = $count;
            fputcsv($handle, $data);
        }

        fclose($handle);
    }
}
