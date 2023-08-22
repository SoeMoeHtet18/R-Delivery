<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $import;
    protected $file;
    protected $errorMessages = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import, $file)
    {
        $this->import = $import;
        $this->file = $file;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Your import code here
        Excel::import($this->import, $this->file);
        $failures = $this->import->failures();
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
            $this->errorMessages[] = $data;
        }
        
        $this->handleError();
    }

    public function handleError()
    {
        $errorMsg = $this->errorMessages;
        // Group error messages by row
        $errorGroups = [];
        foreach ($errorMsg as $error) {
            $row = $error['row'];
            if (!isset($errorGroups[$row])) {
                $errorGroups[$row] = [];
            }
            $errorGroups[$row][] = $error;
        }

        // Create notifications for each group
        foreach ($errorGroups as $row => $errors) {
            $notificationMessage = '';
            foreach ($errors as $error) {
                $notificationMessage .= ',' . $error['errmsg'];
            }

            $notification = Notification::create([
                'title' => 'csv',
                'message' => 'At Row No.' . $row . $notificationMessage
            ]);
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $user->notifications()->attach($notification->id);
        }
    }
}
