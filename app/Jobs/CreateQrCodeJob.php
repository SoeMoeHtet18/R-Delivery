<?php

namespace App\Jobs;

use Mpdf\Mpdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 14400;
    protected $Qrdata;

    /**
     * Create a new job instance.
     */
    public function __construct($Qrdata)
    {
        $this->Qrdata = $Qrdata;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $datas = $this->Qrdata;
        Log::info($datas);
        $mpdf = new Mpdf();

        foreach ($datas as $data) {
            Log::info("Processing data with ID: " . $data['id']); // Add this line to log each iteration
        
            $html = view('admin.qrs.qr', [
                'data' => $data,
            ])->render();
        
            $mpdf->WriteHTML($html);
        }        

        // After the loop, download the combined PDF as a single file
        $mpdf->Output('qrs.pdf', 'D');
    }
}
