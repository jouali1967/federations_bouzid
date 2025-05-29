<?php

namespace App\Livewire\Sanctions;

use Livewire\Component;
use App\Models\Sanction;
use Livewire\WithPagination;

class ListSanction extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'date_sanction';
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
        $sanction = Sanction::find($id);
        if ($sanction) {
            $sanction->delete();
            session()->flash('message', 'Sanction supprimée avec succès.');
        }
    }

    public function render()
    {
        return view('livewire.sanctions.list-sanction', [
            'sanctions' => Sanction::query()
                ->with('personne')
                ->when($this->search, function($query) {
                    $query->where('motif_sanction', 'like', '%' . $this->search . '%')
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