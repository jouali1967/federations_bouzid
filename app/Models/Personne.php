<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personne extends Model
{
  use HasFactory;
  protected $fillable = [
    'nom',
    'prenom',
    'phone',
    'adresse',
    'date_embauche',
    'date_nais',
    'sexe',
    'sit_fam',
    'email','fonction','banque','num_compte','salaire_base'
  ];
  protected $casts = [
    'date_embauche' => 'date:d/m/Y',
    'date_nais' => 'date:d/m/Y',
  ];
  protected function dateEmbauche(): Attribute
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
  protected $appends=['full_name'];
  public function getFullNameAttribute(){
    return strtoupper($this->nom.'  '.$this->prenom);
  }
  public function declarations(){
    return $this->hasMany(Declaration::class);
  }
  public function sanctionns(){
    return $this->hasMany(Sanction::class);
  }
  public function primes(){
    return $this->hasMany(Prime::class);
  }
  public function inscriptions(){
    return $this->hasMany(Cnss::class);
  }
  public function enfants(){
    return $this->hasMany(Enfant::class);
  }
}
