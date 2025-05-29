<?php

namespace App\Livewire\Augmentations;

use Livewire\Component;
use App\Models\Personne;
use App\Models\Augmentation;
use Livewire\Attributes\Rule;

class CreateAugmentation extends Component
{
  #[Rule('required', message: 'La personne est requise')]
  public $personne_id;
  #[Rule('required|date_format:d/m/Y', message: 'La date de inscription est requise')]
  public $date_aug;

  #[Rule('required|integer', message: 'Le n° CNSS est obligatoire et doit être un nombre entier.')]
  public $valeur;
  #[Rule('nullable', message: 'le motif est obligatoire.')]
  public $motif;
  #[Rule('required|in:fixe,pourcentage', message: 'le type est obligatoire.')]
  public $type = 'fixe';
  public $search = '';
  public $personnes = [];
  public $selectedPersonne = null;
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
    }
  }

  public function clearPersonne()
  {
    $this->search = '';
    $this->selectedPersonne = null;
    $this->personne_id = null;
    $this->personnes = [];
    $this->reset();
  }

    public function save()
    {
        $this->validate();
        $personne = Personne::find($this->personne_id);
        $ancien = $personne->salaire_base;

        $nouveau = $this->type === 'fixe'
            ? $ancien + $this->valeur
            : $ancien * (1 + $this->valeur / 100);

        Augmentation::create([
            'personne_id' => $personne->id,
            'type' => $this->type,
            'valeur' => $this->valeur,
            'ancien_salaire' => $ancien,
            'nouveau_salaire' => $nouveau,
            'date_aug' => $this->date_aug,
            'motif' => $this->motif,
        ]);

        $personne->update(['salaire_base' => $nouveau]);

        session()->flash('message', '✅ Augmentation enregistrée avec succès !');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.augmentations.create-augmentation');
    }
}
