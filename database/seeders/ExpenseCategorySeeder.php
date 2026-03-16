<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
                        ['name' => 'الصيانة', 'description' => 'صيانة المعدات والبنية التحتية'],
                        ['name' => 'الوقود', 'description' => 'تكاليف وقود المولدات'],
                        ['name' => 'الرواتب', 'description' => 'رواتب وأجور الموظفين'],
                        ['name' => 'لوازم مكتبية', 'description' => 'مواد ولوازم المكتب'],
                        ['name' => 'المرافق', 'description' => 'فواتير المياه والإنترنت والهاتف'],
                        ['name' => 'أخرى', 'description' => 'مصروفات متنوعة'],
        ];

        foreach ($categories as $cat) {
            ExpenseCategory::create($cat);
        }
    }
}
