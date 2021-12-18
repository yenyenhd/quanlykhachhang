<?php

namespace App\Exports;

use App\Models\Slider;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSlider implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Slider::all();
    }
}
