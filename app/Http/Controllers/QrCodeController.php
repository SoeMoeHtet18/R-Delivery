<?php

namespace App\Http\Controllers;

use App\Jobs\CreateQrCodeJob;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
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
    $dataArray = [];

    foreach ($orders as $order) {
      // Create a new array for each order
      $orderData = [];
      $orderData['id'] = $order->id;
      $orderData['order_code'] = $order->order_code;
      $orderData['customer_name'] = $order->customer_name;
      $orderData['customer_phone_number'] = $order->customer_phone_number;
      $orderData['township'] = $order->township->name;
      $orderData['delivery_fees'] = ($order->delivery_fees + $order->extra_charges) - $order->discount;
      $orderData['item_amount'] = $order->total_amount;
      $orderData['cash_to_collect'] = 0;

      if ($order->payment_method == 'cash_on_delivery') {
        $orderData['cash_to_collect'] = $order->total_amount + $orderData['delivery_fees'];
      } elseif ($order->payment_method == 'item_prepaid') {
        $orderData['cash_to_collect'] = $orderData['delivery_fees'];
      }


      // Append the order data to the main dataArray
      $dataArray[] = $orderData;
    }

    $pdfs = [];

    foreach ($dataArray as $key => $value) {
      $url = config('app.url') . '/orders/' . $value['order_code'];
      $qrCode = QrCode::size(200)->generate($url);

      $imagePath = public_path($value['order_code'] . '.png');
      file_put_contents($imagePath, $qrCode);

      $pdfPath = storage_path('app/public/temp/' . $value['id'] . '.pdf');

      // Make sure the directory exists or create it
      $pdfDirectory = dirname($pdfPath);
      if (!file_exists($pdfDirectory)) {
        mkdir($pdfDirectory, 0755, true);
      }

      $view = View::make('admin.qrs.qr', [
        'data' => $value,
      ]);
      $html = $view->render();

      $dompdf = FacadePdf::loadHtml($html);

      $dompdf->setOption([
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
      } catch (\Mpdf\MpdfException $e) {
        Log::error("Error while saving PDF: " . $e->getMessage());
        // Handle the error appropriately, such as sending a notification or logging it.
      }

      // // Check if the file was successfully created before adding it to the array
      // if (file_exists($pdfPath)) {
      //   $pdfs[] = $pdfPath; // Store the file path in the array
      // } else {
      //   Log::error("PDF file not found at path: $pdfPath");
      //   // Handle the error appropriately, such as sending a notification or logging it.
      // }
    }

    foreach ($pdfs as $pdfPath) {
      $fileName = basename($pdfPath);
      header('Content-Type: application/pdf');
      header('Content-Disposition: attachment; filename="' . $fileName . '"');
      readfile($pdfPath);
    }

    // $mpdf = new Mpdf();
    // $mpdf->WriteHTML(view('admin.qrs.qr'));
    // $mpdf->Output('qrs.pdf', 'D');



    // foreach ($dataArray as $data) {
    //   Log::info("Processing data with ID: " . $data['id']); // Add this line to log each iteration

    //   $html = view('admin.qrs.qr', [
    //     'data' => $data,
    //   ])->render();

    //   $mpdf->WriteHTML($html);
    // }

    // After the loop, download the combined PDF as a single file

    // CreateQrCodeJob::dispatch($dataArray);
  }
}
