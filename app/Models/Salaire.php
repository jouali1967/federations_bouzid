<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salaire extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'personne_id',
        'montant_vire',
        'date_virement',
        'montant_sanction',
        'montant_prime',
        'salaire_base',
    ];

    protected $casts = [
        'date_virement' => 'date'
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

    protected function dateVirement(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                try {
                    return $value ? Carbon::parse($value)->format('d/m/Y') : null;
                } catch (\Exception $e) {
                    return null;
                }
            },
            set: function ($value) {
                try {
                    if (!$value) return null;
                    
                    if (strpos($value, '/') !== false) {
                        return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                    }
                    
                    return Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            }
        );
    }

    public function getMonthWithLeadingZero()
    {
        return Carbon::parse($this->date_virement)->format('m');
    }
}
