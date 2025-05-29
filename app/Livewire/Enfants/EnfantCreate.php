<?php

namespace App\Livewire\Enfants;

use App\Models\Enfant;
use Livewire\Component;
use App\Models\Personne;
use Livewire\Attributes\Rule;

class EnfantCreate extends Component
{
  public $enfants = [];
  public $enfantsToDelete = [];
  #[Rule('required', message: 'La personne est requise')]
  public $personne_id;

  public $search = '';
  public $personnes = [];
  public $selectedPersonne = null;

  public function mount()
  {
    $this->initializeEnfants();
  }

  public function initializeEnfants()
  {
    $this->enfants = [
      ['id' => null, 'nom' => '', 'prenom' => '', 'sexe' => '', 'date_nais' => '']
    ];
  }

  public function addChild()
  {
    $this->enfants[] = ['id' => null, 'nom' => '', 'prenom' => '', 'sexe' => '', 'date_nais' => ''];
    $this->dispatch('enfant-row-added');
  }

  public function removeChild($index)
  {
    if (isset($this->enfants[$index]['id']) && $this->enfants[$index]['id']) {
      $this->enfantsToDelete[] = $this->enfants[$index]['id'];
    }
    unset($this->enfants[$index]);
    $this->enfants = array_values($this->enfants); // Re-index the array
  }

  public function updatedSearch()
  {
    // Si la recherche est différente de la personne sélectionnée, réinitialiser la sélection
    if ($this->selectedPersonne && $this->search !== $this->selectedPersonne->nom . ' ' . $this->selectedPersonne->prenom) {
      $this->selectedPersonne = null;
      $this->personne_id = null;
    }

    if (strlen($this->search) >= 2) {
      $this->personnes = Personne::where(function ($query) {
        $query->where('nom', 'like', '%' . $this->search . '%')
          ->orWhere('prenom', 'like', '%' . $this->search . '%');
      })
        ->limit(5)
        ->get();
    } else {
      $this->personnes = [];
    }
  }

  public function selectPersonne($id)
  {
    $personne = Personne::find($id);
    if ($personne) {
      $this->personne_id = $id;
      $this->selectedPersonne = $personne;
      $this->search = $personne->nom . ' ' . $personne->prenom;
      $this->personnes = [];
      $this->enfantsToDelete = [];

      $existingEnfants = Enfant::where('personne_id', $this->personne_id)->get();
      if ($existingEnfants->isNotEmpty()) {
        $this->enfants = [];
        foreach ($existingEnfants as $enfant) {
          $this->enfants[] = [
            'id' => $enfant->id,
            'nom' => $enfant->nom,
            'prenom' => $enfant->prenom,
            'sexe' => $enfant->sexe,
            'date_nais' => $enfant->date_nais ? (is_string($enfant->date_nais) ? $enfant->date_nais : (new \Carbon\Carbon($enfant->date_nais))->format('d/m/Y')) : ''
          ];
        }
      } else {
        $this->initializeEnfants();
      }
      $this->dispatch('enfants-loaded');
    }
  }

  public function clearPersonne()
  {
    $this->search = '';
    $this->selectedPersonne = null;
    $this->personne_id = null;
    $this->personnes = [];
    $this->enfantsToDelete = [];
    $this->initializeEnfants(); // Reset enfants to one empty row
    // Dispatch an event to re-initialize datepickers after clearing
    $this->dispatch('enfants-cleared');
  }

  public function render()
  {
    return view('livewire.enfants.enfant-create');
  }

  protected function rules()
  {
    return [
      'personne_id' => 'required',
      'enfants.*.nom' => 'required',
      'enfants.*.prenom' => 'required',
      'enfants.*.sexe' => 'required|in:M,F',
      'enfants.*.date_nais' => 'required|date_format:d/m/Y',
    ];
  }

  protected function messages()
  {
    return [
      'personne_id.required' => 'La personne est requise.',
      'enfants.*.nom.required' => 'Le nom est requis.',
      'enfants.*.prenom.required' => 'Le prénom est requis.',
      'enfants.*.sexe.required' => 'Le sexe est requis.',
      'enfants.*.sexe.in' => 'Le sexe doit être M ou F.',
      'enfants.*.date_nais.required' => 'La date de naissance est requise.',
      'enfants.*.date_nais.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA.',
    ];
  }

  public function save()
  {
    $this->validate();

    // 1. Process deletions
    if (!empty($this->enfantsToDelete)) {
      Enfant::destroy($this->enfantsToDelete);
      $this->enfantsToDelete = [];
    }

    // 2. Process updates and creations
    foreach ($this->enfants as $enfantData) {
      $dataToSave = [
        'nom' => $enfantData['nom'],
        'prenom' => $enfantData['prenom'],
        'sexe' => $enfantData['sexe'],
        'date_nais' => $enfantData['date_nais'],
        'personne_id' => $this->personne_id,
      ];

      if (isset($enfantData['id']) && $enfantData['id']) {
        // Existing child, update it
        Enfant::updateOrCreate(['id' => $enfantData['id']], $dataToSave);
      } else {
        // New child, create it
        Enfant::create($dataToSave);
      }
    }

    session()->flash('success', 'Enfants enregistrés avec succès.');
    $this->resetForm();
  }

  public function resetForm()
  {
    $this->clearPersonne();
    $this->initializeEnfants();
    $this->enfantsToDelete = [];
    $this->dispatch('form-was-reset');
  }
}
