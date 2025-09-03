<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime;
use Carbon\Carbon;

class DateBeforeOrEqualToday implements Rule
{
    public function passes($attribute, $value)
    {
        $dateObj = DateTime::createFromFormat('d-m-Y : H:i', $value);

        if (!$dateObj) {
            return false; // format salah
        }

        // cek apakah tidak melebihi hari ini
        return $dateObj <= Carbon::now();
    }

    public function message()
    {
        return 'Tanggal tidak boleh melebihi hari ini.';
    }
}
