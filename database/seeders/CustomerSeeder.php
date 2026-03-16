<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Meter;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Ahmed Hassan', 'phone' => '0501234567', 'national_id' => '1001', 'address' => 'Block A, Street 1', 'opening_reading' => 1000],
            ['name' => 'Mohammed Ali', 'phone' => '0507654321', 'national_id' => '1002', 'address' => 'Block B, Street 2', 'opening_reading' => 500],
            ['name' => 'Sara Abdullah', 'phone' => '0509876543', 'national_id' => '1003', 'address' => 'Block C, Street 3', 'opening_reading' => 750],
            ['name' => 'Fatima Omar', 'phone' => '0501112233', 'national_id' => '1004', 'address' => 'Block D, Street 4', 'opening_reading' => 200],
            ['name' => 'Khalid Ibrahim', 'phone' => '0504455667', 'national_id' => '1005', 'address' => 'Block E, Street 5', 'opening_reading' => 1500],
        ];

        foreach ($customers as $i => $data) {
            $customer = Customer::create($data);
            Meter::create([
                'meter_number' => 'MTR-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'status' => 'active',
                'meter_type' => 'digital',
                'location' => $data['address'],
            ]);
        }
    }
}
