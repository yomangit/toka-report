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

        $now = Carbon::now();

        // Kalau tanggal lebih besar dari hari ini → salah
        if ($dateObj->format('Y-m-d') > $now->format('Y-m-d')) {
            return false;
        }

        // Kalau tanggal sama dengan hari ini tapi jam melebihi sekarang → salah
        if ($dateObj->format('Y-m-d') === $now->format('Y-m-d') && $dateObj > $now) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Tanggal dan jam tidak boleh melebihi waktu saat ini.';
    }
}
