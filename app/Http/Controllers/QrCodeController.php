<?php

namespace App\Http\Controllers;

use App\Jobs\CreateQrCodeJob;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

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
    $orders = $this->orderRepository->getOrdersByIds($data['order_ids']);
    $imageUrl = public_path('images/tcp_logo.jpg');
    $logoImageData = base64_encode(file_get_contents($imageUrl));
    
    $view = view('admin.qrs.multiple', compact('orders', 'logoImageData'));
    $html = $view->render();
    $pdf = FacadePdf::loadHTML($html)->setPaper('a4', 'portrait');
    
    return $pdf->stream('orders.pdf');
  }
}
