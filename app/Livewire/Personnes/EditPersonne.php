<?php

namespace App\Livewire\Personnes;

use Livewire\Component;
use App\Models\Personne;
use Illuminate\Validation\ValidationException;

class EditPersonne extends Component
{
  public $personne;
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
    'email' => 'required|email',
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
    'phone.required' => 'Le numéro de téléphone est obligatoire',
    'phone.regex' => 'Le numéro de téléphone doit contenir 10 chiffres',
    'adresse.required' => 'L\'adresse est obligatoire',
    'date_embauche.required' => 'La date d\'embauche est obligatoire',
    'date_embauche.date_format' => 'La date d\'embauche doit être au format JJ/MM/AAAA',
    'date_nais.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA',
    'sexe.required' => 'Le sexe est obligatoire',
    'sexe.in' => 'Le sexe doit être M ou F',
    'sit_fam.required' => 'La situation familiale est obligatoire',
    'sit_fam.in' => 'La situation familiale doit être M, C ou D',
    'email.required' => 'L\'email est obligatoire',
    'email.email' => 'L\'email doit être une adresse email valide',
    'fonction.required' => 'La fonction est obligatoire',
    'banque.required' => 'La banque est obligatoire',
    'num_compte.required' => 'Le numéro de compte est obligatoire',
    'salaire_base.required' => 'Le salaire de base est obligatoire',
    'salaire_base.numeric' => 'Le salaire de base doit être un nombre',
    'salaire_base.regex' => 'Le salaire de base doit avoir au maximum 2 chiffres après la virgule'
  ];

  public function mount($id)
  {
    $this->personne = Personne::findOrFail($id);
    $this->nom = $this->personne->nom;
    $this->prenom = $this->personne->prenom;
    $this->phone = $this->personne->phone;
    $this->adresse = $this->personne->adresse;
    $this->date_embauche = $this->personne->date_embauche;
    $this->date_nais = $this->personne->date_nais;
    $this->sexe = $this->personne->sexe;
    $this->sit_fam = $this->personne->sit_fam;
    $this->email = $this->personne->email;
    $this->fonction = $this->personne->fonction;
    $this->banque = $this->personne->banque;
    $this->num_compte = $this->personne->num_compte;
    $this->salaire_base = $this->personne->salaire_base;
  }


  public function save()
  {
    $validatedData = $this->validate();
    $this->personne->update($validatedData);
    $this->redirect(route('personnes.index'), navigate: true);
  }

  public function render()
  {
    return view('livewire.personnes.edit-personne');
  }
}
