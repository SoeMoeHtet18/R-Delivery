<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\DeliveryType;
use App\Models\ItemType;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\Township;
use App\Services\OrderService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class OrderImport implements ToModel, WithHeadingRow, WithValidation, WithEvents, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, RegistersEventListeners, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $rows = 0;
    use Importable, RegistersEventListeners, SkipsFailures;

    protected $orderService;

    private function convertData($string)
    {
        $string = trim($string);
        $string = str_replace(' ', '', $string);
        $string = strtolower($string);
        return $string;
    }

    public function model(array $row)
    {
        ++$this->rows;
        
        $shop = trim($row['shop']);
        $shop = Shop::where('name', $shop)->first();
        $shop_id = $shop ? $shop->id : null;

        $rider_id = null;
        if ($row['rider']) {
            $rider = trim($row['rider']);
            $rider = Rider::where('name', $rider)->first();
        }
        
        if($rider) {
            $rider_id = $rider->id;
        }

        $city = trim($row['city']);
        $city = City::where('name', $city)->first();
        $city_id = $city ? $city->id : null;

        $township = trim($row['township']);
        
        $township = Township::where('name', $township)->first();
        $township_id = $township ? $township->id : null;
        
        $delivery_fees = 0.00;
        if($township_id) {
            $delivery_fees = $township->delivery_fees;
        }

        // $item_type = trim($row['item_type']);
        // $item_type = ItemType::where('name', $item_type)->first();
        // $item_type_id = $item_type ? $item_type->id : null;

        $delivery_type = trim($row['delivery_type']);
        $delivery_type = DeliveryType::where('name', $delivery_type)->first();
        $delivery_type_id = $delivery_type ? $delivery_type->id : null;

        // $payment_flag = $this->convertData($row['payment_flag']);
        // $payment_flag = ($payment_flag === 'paid') ? 1 : 0;

        if ($row['schedule_date'] != null) {
            $date = Carbon::parse($row['schedule_date']);
        } else {
            $date = Carbon::tomorrow();
        }
        Log::debug($row['schedule_date']);
        // Log::debug($date);

        if($row['payment_method'] == 'Cash On Delivery') {
            $payment_method = 'cash_on_delivery';
        } else if($row['payment_method'] == 'All Prepaid') {
            $payment_method = 'all_prepaid';
        } else {
            $payment_method = 'item_prepaid';
        }

        $pay_later = $this->convertData($row['pay_later']);
        $pay_later = $pay_later == 'yes' ? 1 : 0;

        $formattedDate = $date->format('Y-m-d H:i:s');

        $order = Order::create([
            'order_code' => Helper::nomenclature('orders', 'TCP', 'id', $shop_id, 'S'),
            'customer_name' => $row['customer_name'],
            'customer_phone_number' => $row['customer_phone_number'],
            'city_id' =>  $city_id,
            'township_id' => $township_id,
            'rider_id' => $rider_id ?? null,
            'shop_id' => $shop_id,
            'total_amount' => $row['total_amount'],
            'delivery_fees' => $delivery_fees,
            'markup_delivery_fees' =>  $row['markup_delivery_fees'] ?? 0.00,
            'remark' => $row['remark'] ?? null,
            'status' => 'pending',
            'item_type_id' => $item_type_id ?? null,
            'full_address' => $row['full_address'] ?? null,
            'schedule_date' => $formattedDate ?? null,
            'delivery_type_id' => $delivery_type_id,
            'collection_method' => 'pickup',
            'payment_flag' => 0,
            'is_confirm' => false,
            'is_payment_channel_confirm' => false,
            'payable_or_not' => 'pending',
            'branch_id' => auth()->user()->branch_id,
            'pay_later' => $pay_later ?? 0,
            'payment_method' => $payment_method,
            'extra_charges' => $row['extra_charges'] ?? null
        ]);

        if ($township_id && $rider_id) {
            $notification = Notification::create([
                'title' => 'create',
                'message' => 'You have got a new order.'
            ]);
            $rider = Rider::find($rider_id);
            $rider->notifications()->attach($notification->id);
        }

        return $order;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required',
            'customer_phone_number' => 'required',
            'city' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $city = City::where('name', trim($value))->first();
                    if ($city == null) {
                        $onFailure("City is invalid.");
                    }
                } else {
                    $onFailure("City is required");
                }
            },
            'township' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $township = Township::where('name', trim($value))->first();
                    if ($township == null) {
                        $onFailure("Township is invalid.");
                    }
                } else {
                    $onFailure("Township is required");
                }
            },
            'shop' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $shop = Shop::where('name', trim($value))->first();
                    if ($shop == null) {
                        $onFailure("Shop is invalid.");
                    }
                } else {
                    $onFailure("Shop is required");
                }
            },
            'total_amount'          => 'required',
            // 'item_type_id' => function ($attribute, $value, $onFailure) {
            //     if ($value) {
            //         $item_type = ItemType::where('name', trim($value))->first();
            //         if ($item_type == null) {
            //             $onFailure("Item Type is invalid.");
            //         }
            //     }
            // },
            'delivery_type' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $type = DeliveryType::where('name', trim($value))->first();
                    if ($type == null) {
                        $onFailure("Delivery type is invalid.");
                    }
                } else {
                    $onFailure("Delivery Type is required");
                }
            },
            // 'collection_method' => function ($attribute, $value, $onFailure) {
            //     if ($value) {
            //         $collection_methods = array_keys(config('data.collection_method'));
            //         if (!in_array($this->convertData($value), $collection_methods)) {
            //             $onFailure("Collection Method is invalid.");
            //         }
            //     } else {
            //         $onFailure("Collection Method is required");
            //     }
            // },
            // 'markup_delivery_fees' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'customer_name.required' => 'Customer Name is required.',
            'customer_phone_number.required' => 'Customer Phone Number is required.',
            'total_amount.required' => 'Total Amount is required.',
            'delivery_fees.required' => 'Delivery Fees is required.',
        ];
    }

    /**
     * @param BeforeImport $event
     */
    public static function beforeImport(BeforeImport $event)
    {
        $worksheet = $event->reader->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10

        if ($highestRow < 2) {
            $error = \Illuminate\Validation\ValidationException::withMessages([]);
            $failure = new Failure(1, 'rows', [0 => 'File can not be empty']);
            $failures = [0 => $failure];
            throw new ValidationException($error, $failures);
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
