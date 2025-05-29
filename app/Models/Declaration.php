<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Declaration extends Model
{
    use HasFactory;
        protected $fillable=['personne_id','mont_dec','date_dec'];
    protected $casts = [
    'date_dec' => 'date:d/m/Y'
    ];
        public function personne()
    {
        return $this->belongsTo(Personne::class);
    }


}
