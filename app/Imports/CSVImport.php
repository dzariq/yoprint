<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Jobs\Uploadcsv;
use DB;

class CSVImport implements ToCollection {

    protected $batchId;

    public function __construct($batchId = 0) {
        $this->batchId = $batchId;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows) {
        $batch = \App\Models\Batches::find($this->batchId);

        \Log::info("batch: " . $batch->id);

        if (!$batch)
            exit();

        $totalRows = count($rows) - 1;

        DB::beginTransaction();

        try {
            foreach ($rows as $key => $row) {
                if ($key == 0)
                    continue;

                $percentageProgress = round(($key / $totalRows * 100), 2);

                Uploadcsv::incrementProgress($batch->id, $percentageProgress);

                \Log::info("unique key: " . json_encode($row[0]));

                // Specify the data you want to insert or update
                $data = [
                    'batch_id' => $batch->id,
                    'title' => $row[1],
                    'description' => $row[2],
                    'style' => $row[3],
                    'color_name' => $row[14],
                    'size' => $row[18],
                    'piece_price' => $row[21],
                    'sanmar_mainframe_color' => $row[28],
                ];

                $conditions = [
                    'unique_key' => $row[0],
                ];

                \App\Models\BatchDetails::updateOrCreate($conditions, $data);
            }
        } catch (\Exception $e) {
            Uploadcsv::incrementProgress($batch->id, -1);
            DB::rollback();
        }

        Uploadcsv::incrementProgress($batch->id);
        DB::commit();
    }

}
