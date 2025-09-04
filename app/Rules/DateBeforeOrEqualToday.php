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
        dd($value);
        // coba parsing dengan kedua format
        $dateObj = DateTime::createFromFormat('d-m-Y : H:i', $value)
                 ?: DateTime::createFromFormat('d-m-Y H:i', $value);

        if (!$dateObj) {
            return false; // format salah
        }

        $now = Carbon::now();

        // tanggal lebih besar dari hari ini → salah
        if ($dateObj->format('Y-m-d') > $now->format('Y-m-d')) {
            return false;
        }

        // tanggal sama tapi jam lebih besar dari sekarang → salah
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
