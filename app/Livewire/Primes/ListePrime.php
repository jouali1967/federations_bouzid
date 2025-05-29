<?php

namespace App\Livewire\Primes;

use App\Models\Prime;
use Livewire\Component;
use Livewire\WithPagination;
class ListePrime extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'date_prime';
    public $sortDirection = 'desc';

    protected $listeners = ['sanctionAdded' => '$refresh'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        $prime = Prime::find($id);
        if ($prime) {
            $prime->delete();
            session()->flash('message', 'Sanction supprimée avec succès.');
        }
    }

    public function render()
    {
        return view('livewire.primes.liste-prime', [
            'primes' => Prime::query()
                ->with('personne')
                ->when($this->search, function($query) {
                    $query->where('motif_prime', 'like', '%' . $this->search . '%')
                        ->orWhere('montant', 'like', '%' . $this->search . '%')
                        ->orWhereHas('personne', function($q) {
                            $q->where('nom', 'like', '%' . $this->search . '%')
                              ->orWhere('prenom', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10)
        ]);
    }

}