<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime;
use Carbon\Carbon;

class DateBeforeOrEqualToday implements Rule
{
    public function passes($attribute, $value)
    {
        // hilangkan spasi ganda
        $value = preg_replace('/\s+/', ' ', trim($value));
       
        // parsing dengan format yang mungkin
        $dateObj = DateTime::createFromFormat('d-m-Y : H:i', $value)
                 ?: DateTime::createFromFormat('d-m-Y H:i', $value);

        if (!$dateObj) {
            return false; // format salah
        }

        // bandingkan hanya tanggal (Y-m-d)
        $inputDate = $dateObj->format('Y-m-d');
        $today     = Carbon::now()->format('Y-m-d');

        return $inputDate <= $today;
    }

    public function message()
    {
        return 'Tanggal tidak boleh melebihi hari ini.';
    }
}
