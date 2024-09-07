<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $countries = [
            [
                'id'         => 1,
                'name'       => 'United State',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 2,
                'name'       => 'Cambodia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 3,
                'name'       => 'Finland',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 4,
                'name'       => 'Ireland',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 5,
                'name'       => 'Qatar',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 6,
                'name'       => 'United Kingdom',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 7,
                'name'       => 'China',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 8,
                'name'       => 'Japan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 9,
                'name'       => 'Jamaica',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 10,
                'name'       => 'Italy',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 11,
                'name'       => 'Malaysia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 12,
                'name'       => 'South Korea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 13,
                'name'       => 'Russia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 14,
                'name'       => 'Ukraine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 15,
                'name'       => 'United Arab Emirates',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 16,
                'name'       => 'Myanmar',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('countries')->insert($countries);

        $supplier_business_types = [
            [
                'id'         => 1,
                'name'       => 'Information Technology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 2,
                'name'       => 'Furniture',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 3,
                'name'       => 'Office Supply',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('supplier_business_types')->insert($supplier_business_types);


        $supplier_industries = [
            [
                'id'         => 1,
                'name'       => 'Office Furniture',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 2,
                'name'       => 'Technology and Electronics',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 3,
                'name'       => 'Sationery and Supplies',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 4,
                'name'       => 'Audo-Visual Equipment',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 5,
                'name'       => 'Network Equipment',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 6,
                'name'       => 'Electrical and HVAC',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('supplier_industries')->insert($supplier_industries);

        $supplier_categories = [
            [
                'id'         => 1,
                'name'       => 'Computer',
                'parent_id'  => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 2,
                'name'       => 'Ipad',
                'parent_id'  => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 3,
                'name'       => 'Phone',
                'parent_id'  => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 4,
                'name'       => 'Notebook Computer',
                'parent_id'  => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 5,
                'name'       => 'Apple Computer',
                'parent_id'  => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 6,
                'name'       => 'Dell Computer',
                'parent_id'  => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 7,
                'name'       => 'Apple Ipad',
                'parent_id'  => '2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 8,
                'name'       => 'Xiaomi Pad',
                'parent_id'  => '2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 9,
                'name'       => 'Xiaomi Phone',
                'parent_id'  => '3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 10,
                'name'       => 'Apple Phone',
                'parent_id'  => '3',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('supplier_categories')->insert($supplier_categories);
    }
}
