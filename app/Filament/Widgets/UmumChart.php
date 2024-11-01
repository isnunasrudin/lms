<?php

namespace App\Filament\Widgets;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Rombel;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UmumChart extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Peserta Didik", Student::count()),
            Stat::make("Rombongan Belajar", Rombel::count()),
            Stat::make("Mata Pelajaran", Exam::count()),
        ];
    }
}
