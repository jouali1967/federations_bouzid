<?php

namespace App\Livewire\Personnes;

use Livewire\Component;
use App\Models\Personne;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ListePersonne extends Component
{
  use WithPagination;
  use WithoutUrlPagination;
  public $paginationTheme = "bootstrap";
  public $search = '';
  public $sortField = 'nom';
  public $sortDirection = 'asc';
  public $categ = '';

  protected $listeners = ['personne-created' => '$refresh'];

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function sortBy($field)
  {
    if ($this->sortField === $field) {
      $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      $this->sortDirection = 'asc';
    }

    $this->sortField = $field;
  }

  public function updatedCateg($value)
  {
    $this->resetPage();
  }

  public function render()
  {
    return view('livewire.personnes.liste-personne', [
      'personnes' => Personne::query()
        ->when($this->categ, function ($query) {
          $query->where('categ', $this->categ);
        })
        ->where(function ($query) {
          $query->where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('prenom', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('adresse', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(5)
    ]);
  }

  public function delete($id)
  {
    try {
      $personne = Personne::findOrFail($id);
      $personne->delete();

      $this->dispatch('personne-deleted', [
        'title' => 'Succès!',
        'message' => 'La personne a été supprimée avec succès.',
        'type' => 'success'
      ]);
    } catch (\Exception $e) {
      $this->dispatch('personne-deleted', [
        'title' => 'Erreur!',
        'message' => 'Une erreur est survenue lors de la suppression.',
        'type' => 'error'
      ]);
    }
  }
}
