<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enfant extends Model
{
  use HasFactory;
  protected $fillable = ['personne_id', 'nom', 'prenom', 'date_nais', 'sexe'];
  protected $casts = [
    'date_nais' => 'date:d/m/Y'
  ];
  protected function dateNais(): Attribute
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
  public function personne(){
    return $this->belongsTo(Personne::class);
  }
  public function enfant(){
    return $this->belongsTo(Personne::class);
  }
}
