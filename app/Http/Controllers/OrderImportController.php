<?php

namespace App\Http\Controllers;

use App\Imports\OrderImport;
use App\Jobs\OrderImportJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class OrderImportController extends Controller
{
    public function index()
    {
        return view('admin.order_import.browse');
    }

    public function upload(Request $request)
    {
        try {
            $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        
        if (!$receiver->isUploaded()) {
            throw new UploadMissingFileException();
        }
       
        $save = $receiver->receive();
       
        if ($save->isFinished()) {
            $encodedRes = $this->saveFile($save->getFile());
            $decodedRes = json_decode($encodedRes->getContent());
            $file_path = $decodedRes->path . $decodedRes->name;

            $file = $save->getFile();
            $extension = $file->getClientOriginalExtension();
            $filename = str_replace("." . $extension, "", $file->getClientOriginalName()) . "." . $extension;

            $file = storage_path($file_path);

            $import = new OrderImport();

            OrderImportJob::dispatch($import, $file);

            Log::debug('Row count: ' . $import->getRowCount());

            $error_msg = $this->processFailures($import);
            Log::debug($error_msg);

            $handler = $save->handler();
            $showFinalResponse = $handler->getPercentageDone() == 100 && $request->resumableFileNumber == 'Last';

            return response()->json([
                "done" => $handler->getPercentageDone(),
                "showFinalResponse" => $showFinalResponse,
                'status' => true
            ]);
        }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Please check your file again.');
        }
        
    }

    protected function saveFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName()) . "." . $extension;

        $mime = str_replace('/', '-', $file->getMimeType());
        $dateFolder = date("Y-m-d");
        $filePath = "upload/{$dateFolder}/";
        $finalPath = public_path($filePath);

        if (!file_exists(storage_path('app/public/upload'))) {
            mkdir(storage_path('app/public/upload'), 0777, true);
        }
        $pathToZip = 'app/public/upload/';

        $file->move(storage_path($pathToZip), $filename);

        return response()->json([
            'path' => $pathToZip,
            'name' => $filename,
            'mime_type' => $mime,
            'file_name_without_ext' => str_replace("." . $extension, "", $file->getClientOriginalName()),
            'file_extension' => $extension
        ]);
    }

    private function processFailures($import)
    {
        $failures = $import->failures();
        $error_msg = [];

        foreach ($failures as $failure) {
            $failure->row();
            $failure->attribute();
            $failure->errors();
            $failure->values();

            $errmsg = implode('', $failure->errors());
            $data = [
                'header' => $failure->attribute(),
                'row' => $failure->row(),
                'errmsg' => $errmsg,
            ];
            $error_msg[] = $data;
        }

        return $error_msg;
    }

    private function sendNotifications($successCount)
    {
        // Send notifications to users and perform necessary actions
        // Use Laravel's notification mechanisms
    }
}
