<?php

namespace Database\Seeders;

use App\Models\Admin\PrePopulatedData;
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

        // $countries = [
        //     [
        //         'id'         => 1,
        //         'name'       => 'United State',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 2,
        //         'name'       => 'Cambodia',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 3,
        //         'name'       => 'Finland',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 4,
        //         'name'       => 'Ireland',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 5,
        //         'name'       => 'Qatar',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 6,
        //         'name'       => 'United Kingdom',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 7,
        //         'name'       => 'China',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 8,
        //         'name'       => 'Japan',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 9,
        //         'name'       => 'Jamaica',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 10,
        //         'name'       => 'Italy',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 11,
        //         'name'       => 'Malaysia',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 12,
        //         'name'       => 'South Korea',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 13,
        //         'name'       => 'Russia',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 14,
        //         'name'       => 'Ukraine',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 15,
        //         'name'       => 'United Arab Emirates',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        //     [
        //         'id'         => 16,
        //         'name'       => 'Myanmar',
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ],
        // ];
        // DB::table('countries')->insert($countries);

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

        // Pre Populated Data for Supplier Register
        $business_types = [
            [
                'type' => 'business_type',
                'data' => [
                    'label' => 'Information Technology',
                    'description' => 'Information Technology',
                ]
            ],
            [
                'type' => 'business_type',
                'data' => [
                    'label' => 'Furniture',
                    'description' => 'Furniture',
                ]
            ],
            [
                'type' => 'business_type',
                'data' => [
                    'label' => 'Office Supply',
                    'description' => 'Office Supply',
                ]
            ],
        ];

        foreach ($business_types as $type) {
            PrePopulatedData::create($type);
        }

        $supplier_industries = [
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Office Furniture',
                    'description' => 'Office Furniture',
                ]
            ],
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Technology and Electronics',
                    'description' => 'Technology and Electronics',
                ]
            ],
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Sationery and Supplies',
                    'description' => 'Sationery and Supplies',
                ]
            ],
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Audo-Visual Equipment',
                    'description' => 'Audo-Visual Equipment',
                ]
            ],
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Network Equipment',
                    'description' => 'Network Equipment',
                ]
            ],
            [
                'type' => 'supplier_industry',
                'data' => [
                    'label' => 'Electrical and HVAC',
                    'description' => 'Electrical and HVAC',
                ]
            ],
        ];

        foreach ($supplier_industries as $type) {
            PrePopulatedData::create($type);
        }
    }
}
