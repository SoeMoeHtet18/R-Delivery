<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\ItemType;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\Township;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;
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

    private function convertData($string)
    {
        $string = trim($string);
        $string = str_replace(' ', '', $string);
        $string = strtolower($string);
        return $string;
    }

    public function model(array $row)
    {
        Log::debug('reach');
        ++$this->rows;
        $shop = trim($row['shop']);
        $shop_id = Shop::where('name', $shop)->first()->id;

        $rider_id = null;
        if ($row['rider']) {
            $rider = trim($row['rider']);
            $rider_id = Rider::where('name', $rider)->first()->id;
        }

        $city = trim($row['city']);
        $city_id = City::where('name', $city)->first()->id;

        $township = trim($row['township']);
        $township_id = Township::where('name', $township)->first()->id;

        $item_type = trim($row['item_type']);
        $item_type = ItemType::where('name', $item_type)->first()->name;

        $payment_flag = $this->convertData($row['payment_flag']);
        $payment_flag = ($payment_flag === 'paid') ? 1 : 0;

        if ($row['schedule_date']) {
            $date = Carbon::parse($row['schedule_date']);
        } else {
            $date = Carbon::tomorrow();
        }

        $formattedDate = $date->format('Y-m-d H:i:s');

        return Order::create([
            'order_code' => Helper::nomenclature('orders', 'OD', 'id', $shop_id),
            'customer_name' => $row['customer_name'],
            'customer_phone_number' => $row['customer_phone_number'],
            'city_id' =>  $city_id,
            'township_id' => $township_id,
            'rider_id' => $rider_id,
            'shop_id' => $shop_id,
            'total_amount' => $row['total_amount'],
            'delivery_fees' => $row['delivery_fees'],
            'markup_delivery_fees' =>  $row['markup_delivery_fees'],
            'remark' => $row['remark'],
            'status' => $this->convertData($row['status']),
            'item_type' => $item_type,
            'full_address' => $row['full_address'],
            'schedule_date' => $formattedDate,
            'type' => $this->convertData($row['type']),
            'collection_method' => $this->convertData($row['collection_method']),
            'payment_flag' => $payment_flag
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required',
            'customer_phone_number' => 'required',
            'city_id' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $city = City::where('name', trim($value))->first();
                    if ($city == null) {
                        $onFailure("City is invalid.");
                    }
                } else {
                    $onFailure("City is required");
                }
            },
            'township_id' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $township = Township::where('name', trim($value))->first();
                    if ($township == null) {
                        $onFailure("Township is invalid.");
                    }
                } else {
                    $onFailure("Township is required");
                }
            },
            'shop_id' => function ($attribute, $value, $onFailure) {
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
            'delivery_fees'         => 'required',
            'item_type' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $item_type = ItemType::where('name', trim($value))->first();
                    if ($item_type == null) {
                        $onFailure("Item Type is invalid.");
                    }
                } else {
                    $onFailure("Item Type is required");
                }
            },
            'type' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $types = array_keys(config('data.type'));
                    if (!in_array($this->convertData($value), $types)) {
                        $onFailure("Type is invalid.");
                    }
                } else {
                    $onFailure("Type is required");
                }
            },
            'collection_method' => function ($attribute, $value, $onFailure) {
                if ($value) {
                    $collection_methods = array_keys(config('data.collection_method'));
                    if (!in_array($this->convertData($value), $collection_methods)) {
                        $onFailure("Collection Method is invalid.");
                    }
                } else {
                    $onFailure("Collection Method is required");
                }
            },
            'full_address' => 'required',
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
            'full_address.required' => 'Full Address is required.'
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
