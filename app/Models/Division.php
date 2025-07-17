<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    protected $table = 'divisions';
    protected $fillable = [
        'dept_by_business_unit_id',
        'company_id',
        'section_id'
    ];
    public function DeptByBU()
    {
        return $this->belongsTo(DeptByBU::class, 'dept_by_business_unit_id');
    }
    public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function Section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function Division()
    {
        return $this->belongsToMany(Division::class, 'class_hierarchies');
    }

    public function scopeSearchParent($q, $t)
    {
        $q->when(
            $t ?? false,
            fn($q, $t) => $q->whereHas('DeptByBU', function ($q) use ($t) {
                $q->whereHas('BusinesUnit', function ($q) use ($t) {
                    $q->whereHas('Company', function ($q) use ($t) {
                        $q->whereHas('CompanyCategory', function ($q) use ($t) {
                            $q->where('id', 'LIKE', $t);
                        });
                    });
                });
            })
        );
    }
    public function scopeSearchBU($q, $t)
    {
        $q->when(
            $t ?? false,
            fn($q, $t) => $q->whereHas('DeptByBU', function ($q) use ($t) {
                $q->whereHas('BusinesUnit', function ($q) use ($t) {
                    $q->whereHas('Company', function ($q) use ($t) {

                        $q->where('id', 'LIKE', $t);
                    });
                });
            })
        );
    }

    public function scopeSearchDeptCom($q, $t)
    {
        $q->when(
            $t ?? false,
            fn($q, $t) => $q->whereHas('DeptByBU', function ($q) use ($t) {
                $q->whereHas('Department', function ($q) use ($t) {
                    $q->where('department_name', 'like', '%' . $t . '%');
                });
            })->orwhereHas('Company', function ($q) use ($t) {
                $q->where('name_company', 'like', '%' . $t . '%');
            })->orWhereHas('DeptByBU', function ($q) use ($t) {
                $q->whereHas('BusinesUnit', function ($q) use ($t) {
                    $q->whereHas('Company', function ($q) use ($t) {
                        $q->where('name_company', 'LIKE', '%' . $t . '%');
                    });
                });
            })->orWhereHas('Section', function ($q) use ($t) {
                $q->where('name', 'LIKE', "%$t%");
            })
        );
    }

    public function scopeSearchComp($q, $t)
    {
        $q->when(
            $t ?? false,
            fn($q, $t) => $q->where('company_id', $t)
        );
    }
    public function scopeSearchDept($q, $t)
    {
        $q->when(
            $t ?? false,
            fn($q, $t) => $q->whereHas('DeptByBU', function ($q) use ($t) {
                $q->where('id', 'LIKE', $t);
            })
        );
    }
    public function formatWorkgroupName(): string
    {
        $companyFromBU = optional($this->DeptByBU->BusinesUnit->Company)->name_company;
        $department     = optional($this->DeptByBU->Department)->department_name;
        $ownCompany     = optional($this->Company)->name_company;
        $section        = optional($this->Section)->name;

        $parts = array_filter([
            $companyFromBU,
            $department,
            $ownCompany,
            $section
        ]);

        return implode('-', $parts) ?: 'N/A';
    }
}
