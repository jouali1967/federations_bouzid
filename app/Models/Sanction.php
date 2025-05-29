<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'personne_id',
        'montant',
        'date_sanction',
        'motif_sanction',
    ];

  protected $casts = [
    'date_embauche' => 'date:d/m/Y'
  ];


    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

    protected function dateSanction(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?
                Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y')
                : null,
            set: fn($value) => $value
                ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d')
                : null,
        );
    }
}
