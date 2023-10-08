<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVImport;
use League\Csv\Reader;
use DB;

class Uploadcsv implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $fileName = '';
    protected $batchId = 0;

    /**
     * Create a new job instance.
     */
    public function __construct($fileName = '', $batchId = 0) {
        $this->fileName = $fileName;
        $this->batchId = $batchId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        \Log::info('processing: ' . 'app/uploads/' . $this->fileName);
        $csvFilePath = storage_path('app/uploads/' . $this->fileName);

        Uploadcsv::incrementProgress($this->batchId);
        // Create a CSV reader
        $csv = Reader::createFromPath($csvFilePath, 'r');
        $csv->setHeaderOffset(0); // Skip the header row
        // Process the CSV data in chunks
        $chunkSize = 100;
        $totalRows = $csv->count();
        \Log::info("total rows: " . $totalRows);

        $batch = \App\Models\Batches::find($this->batchId);

        if (!$batch)
            exit();

        $onChunkProcessed = function ($rowCount, $done = 0) use ($batch, $totalRows) {
            \Log::info("processed rows: : " . ($rowCount));

            if ($done == 1)
                Uploadcsv::incrementProgress($batch->id, 100);
            else {
                Uploadcsv::incrementProgress($batch->id, round(($rowCount / $totalRows) * 100, 2));
            }
        };

        $processedRows = 0;

        $csv->each(function ($row)use (&$processedRows, $chunkSize, $onChunkProcessed, $batch) {

            $data = [
                'title' => $row['PRODUCT_TITLE'],
                'description' => $row['PRODUCT_DESCRIPTION'],
                'style' => $row['STYLE#'],
                'color_name' => $row['COLOR_NAME'],
                'size' => $row['SIZE'],
                'piece_price' => $row['PIECE_PRICE'],
                'sanmar_mainframe_color' => $row['SANMAR_MAINFRAME_COLOR'],
            ];

            $conditions = [
                'unique_key' => $row['UNIQUE_KEY'],
            ];
//
            \App\Models\BatchDetails::updateOrCreate($conditions, $data);
            $processedRows++;

            // Check if the current row count equals the chunk size
            if ($processedRows % $chunkSize === 0) {
                $onChunkProcessed($processedRows);
            }
        }, $chunkSize);

        if ($processedRows % $chunkSize !== 0) {
            $onChunkProcessed($processedRows, 1);
        }
    }

    public static function incrementProgress($batchId, $percent = 0) {
        $batches = \App\Models\Batches::find($batchId);

        if (!$batches)
            exit();

        if ($batches->progress == 0 && $batches->status == 'pending') {
            $batches->status = 'processing';
            $batches->save();
        } else if ($batches->progress < 100) {
            $batches->progress = $percent;
            $batches->save();
        } else if ($batches->progress == 100) {
            $batches->status = 'completed';
            $batches->save();
        } else if ($percent == -1) {
            $batches->status = 'failed';
            $batches->save();
        }

        if ($percent == 100) {
            $batches->status = 'completed';
            $batches->save();
        }
    }

}
