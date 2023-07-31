<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\View;

class QrCodeController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        return view('admin.qrs.qr');
    }

    public function generateQrCode(Request $request)
    {
        $data = $request->all();
        $order_ids = $data['order_ids'];
        $orders = $this->orderRepository->getOrdersByIds($order_ids);
        $pdfs = [];

        foreach ($orders as $order) {
            $url = config('app.url') . '/orders/' . $order->order_code;
            $qrCode = QrCode::size(200)->generate($url);

            $imagePath = public_path($order->order_code . '.png');
            file_put_contents($imagePath, $qrCode);

            $pdfPath = storage_path('app/public/temp/' . $order->id . '.pdf');

            // Make sure the directory exists or create it
            $pdfDirectory = dirname($pdfPath);
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $view = View::make('admin.qrs.qr', [
                'data' => $order,
            ]);
            $html = $view->render();

            $dompdf = PDF::loadHtml($html);

            $dompdf->setOptions([
                'isPhpEnabled' => true, // Enable inline PHP code in the HTML (if needed)
            ]);

            // Optionally, set additional options
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF
            $dompdf->render();

            $pdfContent = $dompdf->output();

            // Try to save the PDF to the specified path
            try {
                file_put_contents($pdfPath, $pdfContent);
                $pdfs[] = $pdfPath; // Store the file path in the array
            } catch (\Exception $e) {
                Log::error("Error while saving PDF: " . $e->getMessage());
                // Handle the error appropriately, such as sending a notification or logging it.
            }
        }

        // Now, you can download the PDFs.
        foreach ($pdfs as $pdfPath) {
            $fileName = basename($pdfPath);
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile($pdfPath);
        }

        // If you want to delete the temporary files after download, you can do it here.
        // Be cautious while deleting files, especially if they are still being downloaded.

        // $this->deleteTemporaryFiles($pdfs);
    }

    // Add a function to delete temporary PDF files if needed
    // private function deleteTemporaryFiles($filePaths)
    // {
    //     foreach ($filePaths as $filePath) {
    //         if (file_exists($filePath)) {
    //             unlink($filePath);
    //         }
    //     }
    // }
}
