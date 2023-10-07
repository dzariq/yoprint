<?php

namespace App\Http\Controllers;

use App\Jobs\Uploadcsv;
use App\Models\Batches;
use App\Models\BatchDetails;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVImport;
use Illuminate\Support\Facades\Bus;

class ExportController extends Controller {

    public function uploadCSV() {
        return view('upload_excel', array(
        ));
    }

    public function updateTable() {
        $batches = Batches::orderby('id', 'DESC')->get();
        
        foreach($batches as &$batch)
        {
            $batch->since = $this->time_since(strtotime($batch->created_at));
        }

        $html = '';
        foreach ($batches as $batch) {
            $html .= '<tr>';
            $html .= '<td>';
            $html .= date('F jS Y H:i A', strtotime($batch->created_at));
            $html .= '<br/>';
            $html .= '(' . $batch->since . ')';
            $html .= '</td>';
            $html .= '<td>' . $batch->filename . '</td>';
            $html .= '<td>';
            if ($batch->status == 'failed') {
                $html .= '<button class="btn btn-danger">' . $batch->status . '</button>';
            } elseif ($batch->status == 'completed') {
                $html .= '<button class="btn btn-success">' . $batch->status . '</button>';
            } elseif ($batch->status == 'pending') {
                $html .= '<button class="btn btn-warning">' . $batch->status . '</button>';
            } elseif ($batch->status == 'processing') {
                $html .= '<button class="btn btn-primary">' . $batch->status . '</button>';
                $html .= '<br/>';
                $html .= '<div class="progress" style="margin-top: 10px">';
                $html .= '<div class="progress-bar" role="progressbar" aria-valuenow="0"';
                $html .= 'aria-valuemin="0" aria-valuemax="100" style="width:' . $batch->progress . '%">';
                $html .= $batch->progress . '%';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }

        echo json_encode($html);
    }

    private function time_since($since) {
        $time_difference = time() - $since;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }
        $condition = array(12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

    public function exportCSV(Request $request) {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:50048', // Adjust file validation rules as needed
        ]);

        if ($request->file('file')->isValid()) {

            // Get the original filename
            $originalFilename = $request->file('file')->getClientOriginalName();

            // Store the file in a directory while preserving the original filename
            $path = $request->file('file')->storeAs('uploads', $originalFilename);

            // Specify the data you want to insert or update
            $data = [
                'progress' => 0,
                'status' => 'PENDING',
            ];

            $conditions = [
                'filename' => $originalFilename,
            ];

            $batch = Batches::updateOrCreate($conditions, $data);

            $job = Uploadcsv::dispatch($originalFilename, $batch->id);

            return back()->with('success', 'File uploaded successfully.');
        }


        return back()->with('error', 'File upload failed.');
    }

}
