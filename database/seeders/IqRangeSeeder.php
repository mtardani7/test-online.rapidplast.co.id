<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IqRangeSeeder extends Seeder
{
    public function run(): void
    {
        $ranges = [
            ['raw_min' => 185, 'raw_max' => 999, 'iq_score' => 140, 'category' => 'Very Superior'],  
            ['raw_min' => 181, 'raw_max' => 183, 'iq_score' => 140, 'category' => 'Very Superior'],
            ['raw_min' => 178, 'raw_max' => 180, 'iq_score' => 139, 'category' => 'Very Superior'],
            ['raw_min' => 174, 'raw_max' => 177, 'iq_score' => 138, 'category' => 'Very Superior'],
            ['raw_min' => 169, 'raw_max' => 173, 'iq_score' => 137, 'category' => 'Superior'],
            ['raw_min' => 164, 'raw_max' => 168, 'iq_score' => 136, 'category' => 'Superior'],
            ['raw_min' => 162, 'raw_max' => 163, 'iq_score' => 135, 'category' => 'Superior'],
            ['raw_min' => 160, 'raw_max' => 161, 'iq_score' => 134, 'category' => 'Superior'],
            ['raw_min' => 158, 'raw_max' => 159, 'iq_score' => 133, 'category' => 'High Average'],
            ['raw_min' => 156, 'raw_max' => 157, 'iq_score' => 132, 'category' => 'High Average'],
            ['raw_min' => 154, 'raw_max' => 155, 'iq_score' => 131, 'category' => 'High Average'],
            ['raw_min' => 151, 'raw_max' => 153, 'iq_score' => 130, 'category' => 'High Average'],
            ['raw_min' => 148, 'raw_max' => 150, 'iq_score' => 129, 'category' => 'High Average'],
            ['raw_min' => 145, 'raw_max' => 147, 'iq_score' => 128, 'category' => 'Average'],
            ['raw_min' => 142, 'raw_max' => 144, 'iq_score' => 127, 'category' => 'Average'],
            ['raw_min' => 139, 'raw_max' => 141, 'iq_score' => 126, 'category' => 'Average'],
            ['raw_min' => 136, 'raw_max' => 138, 'iq_score' => 125, 'category' => 'Average'],
            ['raw_min' => 133, 'raw_max' => 135, 'iq_score' => 124, 'category' => 'Average'],
            ['raw_min' => 130, 'raw_max' => 132, 'iq_score' => 123, 'category' => 'Average'],
            ['raw_min' => 127, 'raw_max' => 129, 'iq_score' => 122, 'category' => 'Average'],
            ['raw_min' => 124, 'raw_max' => 126, 'iq_score' => 121, 'category' => 'Average'],
            ['raw_min' => 122, 'raw_max' => 123, 'iq_score' => 120, 'category' => 'Average'],
            ['raw_min' => 120, 'raw_max' => 121, 'iq_score' => 119, 'category' => 'Low Average'],
            ['raw_min' => 118, 'raw_max' => 119, 'iq_score' => 118, 'category' => 'Low Average'],
            ['raw_min' => 116, 'raw_max' => 117, 'iq_score' => 117, 'category' => 'Low Average'],
            ['raw_min' => 114, 'raw_max' => 115, 'iq_score' => 116, 'category' => 'Low Average'],
            ['raw_min' => 112, 'raw_max' => 113, 'iq_score' => 115, 'category' => 'Low Average'],
            ['raw_min' => 110, 'raw_max' => 111, 'iq_score' => 114, 'category' => 'Low Average'],
            ['raw_min' => 108, 'raw_max' => 109, 'iq_score' => 113, 'category' => 'Low Average'],
            ['raw_min' => 106, 'raw_max' => 107, 'iq_score' => 112, 'category' => 'Low Average'],
            ['raw_min' => 104, 'raw_max' => 105, 'iq_score' => 111, 'category' => 'Low Average'],


            ['raw_min' => 102, 'raw_max' => 103, 'iq_score' => 110, 'category' => 'Average'],
            ['raw_min' => 100, 'raw_max' => 101, 'iq_score' => 109, 'category' => 'Average'],
            ['raw_min' => 98, 'raw_max' => 99, 'iq_score' => 108, 'category' => 'Average'],
            ['raw_min' => 96, 'raw_max' => 97, 'iq_score' => 107, 'category' => 'Average'],
            ['raw_min' => 94, 'raw_max' => 95, 'iq_score' => 106, 'category' => 'Average'],
            ['raw_min' => 91, 'raw_max' => 93, 'iq_score' => 105, 'category' => 'Average'],
            ['raw_min' => 88, 'raw_max' => 90, 'iq_score' => 104, 'category' => 'Average'],
            ['raw_min' => 85, 'raw_max' => 87, 'iq_score' => 103, 'category' => 'Average'],
            ['raw_min' => 82, 'raw_max' => 84, 'iq_score' => 102, 'category' => 'Average'],
            ['raw_min' => 79, 'raw_max' => 81, 'iq_score' => 101, 'category' => 'Low Average'],
            ['raw_min' => 77, 'raw_max' => 78, 'iq_score' => 100, 'category' => 'Low Average'],
            ['raw_min' => 75, 'raw_max' => 76, 'iq_score' => 99, 'category' => 'Low Average'],
            ['raw_min' => 73, 'raw_max' => 74, 'iq_score' => 98, 'category' => 'Low Average'],
            ['raw_min' => 71, 'raw_max' => 72, 'iq_score' => 97, 'category' => 'Low Average'],
            ['raw_min' => 69, 'raw_max' => 70, 'iq_score' => 96, 'category' => 'Low Average'],
            ['raw_min' => 66, 'raw_max' => 68, 'iq_score' => 95, 'category' => 'Low'],
            ['raw_min' => 63, 'raw_max' => 65, 'iq_score' => 94, 'category' => 'Low'],
            ['raw_min' => 60, 'raw_max' => 62, 'iq_score' => 93, 'category' => 'Low'],
            ['raw_min' => 57, 'raw_max' => 59, 'iq_score' => 92, 'category' => 'Low'],
            ['raw_min' => 54, 'raw_max' => 56, 'iq_score' => 91, 'category' => 'Low'],
            ['raw_min' => 44, 'raw_max' => 53, 'iq_score' => 90, 'category' => 'Low'],
            ['raw_min' => 0,  'raw_max' => 43, 'iq_score' => 89, 'category' => 'Borderline'],  
        ];

        foreach ($ranges as $r) {
            DB::table('iq_ranges')->insert([
                'test_type' => 'TPA',
                'raw_min' => $r['raw_min'],
                'raw_max' => $r['raw_max'],
                'iq_score' => $r['iq_score'],
                'category' => $r['category'],
            ]);
        }
    }
}
