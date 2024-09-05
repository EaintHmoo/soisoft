<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Admin\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Information and Communication Technology (ICT)' => [
                'Software Development & Licenses',
                'Hardware Supply & Maintenance',
                'Networking Equipment',
                'IT Consultancy Services',
                'Cloud Services',
                'IT Security Solutions',
                'Data Management & Analytics',
                'Telecommunications',
            ],

            'Construction & Civil Engineering' => [
                'Building Construction',
                'Infrastructure Development',
                'Renovation & Interior Fit-Out',
                'Roadworks & Paving',
                'Structural Engineering Services',
                'Electrical & Mechanical Works',
            ],

            'Healthcare & Medical Supplies' => [
                'Medical Equipment & Devices',
                'Pharmaceuticals',
                'Laboratory Supplies',
                'Medical Consumables',
                'Healthcare Services',
                'Ambulance & Emergency Vehicles',
            ],

            'Office Supplies & Services' => [
                'Stationery & Office Equipment',
                'Printing & Binding Services',
                'Office Furniture',
                'Facility Management',
                'Cleaning & Janitorial Services',
                'Office Renovation & Fit-Out',
            ],

            'Professional Services' => [
                'Legal Services',
                'Audit & Financial Services',
                'Management Consultancy',
                'Training & Development Services',
                'HR & Recruitment Services',
                'Marketing & Public Relations',
            ],

            'Transportation & Logistics' => [
                'Vehicle Purchase & Leasing',
                'Freight & Shipping Services',
                'Courier & Delivery Services',
                'Warehousing & Storage Solutions',
                'Fleet Management & Maintenance',
            ],

            'Security & Safety' => [
                'Security Equipment & Systems',
                'Surveillance & CCTV Systems',
                'Fire Safety Equipment',
                'Access Control Systems',
                'Personal Protective Equipment (PPE)',
            ],

            'Energy & Utilities' => [
                'Electricity Supply & Distribution',
                'Renewable Energy Solutions',
                'Water Treatment & Supply',
                'Waste Management',
                'Environmental Consultancy',
            ],

            'Catering & Hospitality' => [
                'Catering Services for Events',
                'Food & Beverage Supply',
                'Hospitality Management Services',
                'Event Planning & Coordination',
            ],

            'Marketing & Communications' => [
                'Advertising & Media Services',
                'Graphic Design & Printing',
                'Public Relations & Communications',
                'Digital Marketing Services',
                'Others',
            ]
        ];

        foreach($categories as $parent => $child) {
            $parent_ctg = Category::create([
                'name' => $parent,
                'slug' => Str::slug($parent)
            ]);

            foreach($child as $child_ctg) {
                Category::create([
                    'name' => $child_ctg,
                    'slug' => Str::slug($child_ctg),
                    'parent_id' => $parent_ctg->id
                ]);
            }
        }
    }
}