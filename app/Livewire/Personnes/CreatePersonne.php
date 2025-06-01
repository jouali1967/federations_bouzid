<?php

namespace App\Livewire\Personnes;

use Livewire\Component;
use App\Models\Personne;
use Illuminate\Support\Facades\DB;

class CreatePersonne extends Component
{
  public $nom;
  public $prenom;
  public $phone;
  public $adresse;
  public $date_embauche;
  public $date_nais;
  public $sexe;
  public $sit_fam;
  public $email;
  public $fonction;
  public $banque;
  public $num_compte;
  public $salaire_base;
  public $cin;
  public $categ;

  protected $rules = [
    'nom' => 'required',
    'prenom' => 'required',
    'phone' => 'required|regex:/^[0-9]{10}$/',
    'adresse' => 'required',
    'date_embauche' => 'required|date_format:d/m/Y',
    'date_nais' => 'nullable|date_format:d/m/Y',
    'sexe' => 'required|in:M,F',
    'sit_fam' => 'required|in:M,C,D',
    'email' => 'nullable|email',
    'fonction' => 'required',
    'banque' => 'required',
    'num_compte' => 'required',
    'salaire_base' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
    'cin' => 'required|regex:/^[A-Z0-9]$/',
    'categ' => 'required|in:categorie1,categorie2,categorie3'
  ];

  protected $messages = [
    'nom.required' => 'Le nom est obligatoire',
    'prenom.required' => 'Le prénom est obligatoire',
    'phone.required' => 'Le téléphone est obligatoire',
    'phone.regex' => 'Le numéro de téléphone doit contenir 10 chiffres',
    'adresse.required' => 'L\'adresse est obligatoire',
    'date_embauche.required' => 'La date d\'embauche est obligatoire',
    'date_embauche.date_format' => 'La date d\'embauche doit être au format JJ/MM/AAAA',
    'date_nais.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA',
    'sexe.required' => 'Le sexe est obligatoire',
    'sexe.in' => 'Le sexe doit être M ou F',
    'sit_fam.required' => 'La situation familiale est obligatoire',
    'sit_fam.in' => 'La situation familiale doit être M, C ou D',
    'email.email' => 'L\'email doit être une adresse email valide',
    'fonction.required' => 'La fonction est obligatoire',
    'banque.required' => 'La banque est obligatoire',
    'num_compte.required' => 'Le numéro de compte est obligatoire',
    'salaire_base.required' => 'Le salaire de base est obligatoire',
    'salaire_base.numeric' => 'Le salaire de base doit être un nombre',
    'salaire_base.regex' => 'Le salaire de base doit avoir au maximum 2 chiffres après la virgule',
    'cin.required' => 'Le CIN est obligatoire',
    'cin.regex' => 'Le CIN doit être alphanumérique et ne pas contenir de caractères spéciaux',
    'categ.required' => 'La catégorie est obligatoire',
    'categ.in' => 'La catégorie doit être l\'une des valeurs suivantes : categorie1, categorie2, categorie3'
  ];

  public function updated($propertyName)
  {
    $this->validateOnly($propertyName);
  }

  public function render()
  {
/*$personnes = Personne::select('id','nom', 'prenom') // Manque l'ID !
    ->with('inscriptions:id,personne_id,num_cnss')
    ->withCount('enfants')
    ->get();
    foreach ($personnes as $personne) {
    $numCnss = $personne->inscriptions->first()->num_cnss ?? 'Non inscrit';
    echo $personne->nom . ' ' . $personne->prenom . ' - CNSS: ' . $numCnss . ' - Enfants: ' . $personne->enfants_count . PHP_EOL;
}*/
/*$personnes = Personne::select('id','nom', 'prenom')
    ->with('inscriptions:id,personne_id,num_cnss')
    ->with(['declarations' => function ($query) {
        $query->select('id', 'personne_id', 'mont_dec', 'date_dec')
            ->whereRaw('YEAR(date_dec) = (SELECT YEAR(MAX(date_dec)) FROM declarations)')
            ->whereRaw('MONTH(date_dec) = (SELECT MONTH(MAX(date_dec)) FROM declarations)')
            ->orderBy('date_dec', 'desc');
            // Retiré le limit(1) temporairement
    }])
    ->withCount('enfants')
    ->get();
    dd($personnes);*/

    return view('livewire.personnes.create-personne');
  }
  public function save()
  {
    try {
      $validatedData = $this->validate();

      Personne::create($validatedData);

      $this->reset();
      $this->dispatch('personne-created', [
        'title' => 'Succès!',
        'message' => 'La personne a été créée avec succès.',
        'type' => 'success'
      ]);
    } catch (\Exception $e) {
      if ($e instanceof \Illuminate\Validation\ValidationException) {
        throw $e;
      }
      $this->dispatch('personne-created', [
        'title' => 'Erreur!',
        'message' => 'Une erreur est survenue lors de la création : ' . $e->getMessage(),
        'type' => 'error'
      ]);
    }
  }
}
