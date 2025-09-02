<?php

namespace Database\Seeders;

use App\Models\RiskMatrixCell;
use App\Models\RiskConsequence;
use App\Models\RiskLikelihood;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RiskMatrixCellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $likelihoods = RiskLikelihood::all();
        $consequences = RiskConsequence::all();

        foreach ($likelihoods as $l) {
            foreach ($consequences as $c) {
                $score = $l->level * $c->level;

                // Tentukan severity berdasarkan skor
                $severity = match (true) {
                    $score <= 5 => 'Low',
                    $score <= 10 => 'Moderate',
                    $score <= 15 => 'High',
                    default => 'Extreme',
                };

                RiskMatrixCell::updateOrCreate(
                    [
                        'likelihood_id' => $l->id,
                        'risk_consequence_id' => $c->id,
                    ],
                    [
                        'score' => $score,
                        'severity' => $severity,
                        'description' => "Auto generated cell for L{$l->level} × C{$c->level}",
                        'action' => null,
                        'company_id' => null, // bisa diisi jika multi perusahaan
                    ]
                );
            }
        }
    }
}
