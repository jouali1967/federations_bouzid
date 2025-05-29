<?php

namespace App\Livewire\Declarations;

use Livewire\Component;
use App\Models\Declaration;
use Illuminate\Support\Facades\Response;

class PersonnesDeclares extends Component
{
  public $date_declaration;
  public $employes = [];
  public $perPage = 10;
  public $currentPage = 1;
  public $totalPages = 1;

  protected $rules = [
    'date_declaration' => 'required|regex:/^\d{2}\/\d{4}$/',
  ];

  protected $messages = [
    'date_declaration.required' => 'La date declaration est requise.',
    'date_declaration.regex' => 'Le format de la date doit être MM/YYYY (ex: 03/2024).',
  ];

  public function render()
  {
    return view('livewire.declarations.personnes-declares');
  }
  public function affcherPersonnesDeclares()
  {
    $this->validate();
    // Extraire le mois et l'année
    [$mois, $annee] = explode('/', $this->date_declaration);
    // Requête pour récupérer les employés déclarés avec les infos demandées
    $query = Declaration::with(['personne', 'personne.inscriptions'])
      ->whereMonth('date_dec', $mois)
      ->whereYear('date_dec', $annee);
    $total = $query->count();
    $this->totalPages = max(1, ceil($total / $this->perPage));
    $declarations = $query->skip(($this->currentPage - 1) * $this->perPage)
      ->take($this->perPage)
      ->get();
    $this->employes = $declarations->map(function ($declaration) {
      $personne = $declaration->personne;
      $cnss = $personne->inscriptions->first();
      return [
        'nom' => $personne->nom,
        'prenom' => $personne->prenom,
        'num_cnss' => $cnss ? $cnss->num_cnss : null,
        'nombre_enfants' => $personne->enfants ? $personne->enfants->count() : 0,
        'situation_famille' => $personne->sit_fam,
        'salaire_base' => $personne->salaire_base,
        'montant_dec' => $declaration->mont_dec,
      ];
    })->toArray();
  }

  public function goToPage($page)
  {
    if ($page >= 1 && $page <= $this->totalPages) {
      $this->currentPage = $page;
      $this->affcherPersonnesDeclares();
    }
  }

  public function generatePdf()
  {
    // Vérifier que la date est bien définie et au bon format
    if (!$this->date_declaration || !str_contains($this->date_declaration, '/')) {
      session()->flash('success', 'Veuillez d’abord sélectionner une période valide.');
      return redirect()->back();
    }
    $parms = route('etat_declares.pdf',[
      'date_declaration' => $this->date_declaration,
    ]);
    $this->dispatch('openDecWindow', url: $parms);

    //return redirect()->route('etat_declares.pdf', ['date_declaration' => $this->date_declaration]);
  }
}
