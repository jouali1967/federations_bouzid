<?php

namespace App\Livewire\Personnes;

use App\Models\Personne;
use Livewire\Component;
use Livewire\WithPagination;

class EtatEmployes extends Component
{
    use WithPagination;

    public $nom1 = '';
    public $nom2 = '';
    public $titre = 'État des Employés';
    public $entreprise = 'Fédération des Associations Mly Abdellah';
    public $search = '';
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'bootstrap';

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
        $this->resetPage();
    }

    public function generatePdf()
    {
        $params = route('etat.employes.pdf',[
            'nom1' => $this->nom1,
            'nom2' => $this->nom2,
            'titre' => $this->titre,
            'entreprise' => $this->entreprise
        ]);
    $this->dispatch('openEtatWindow', url: $params);

       // return redirect()->route('etat.employes.pdf', $params);
    }

    public function downloadPdf()
    {
        $params = [
            'nom1' => $this->nom1,
            'nom2' => $this->nom2,
            'titre' => $this->titre,
            'entreprise' => $this->entreprise
        ];

        return redirect()->route('etat.employes.download', $params);
    }

    public function render()
    {
        $employes = Personne::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%')
                      ->orWhere('fonction', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $totalEmployes = Personne::count();
        $totalSalaires = Personne::sum('salaire_base') ?: 0;
        $moyenneSalaire = $totalEmployes > 0 ? $totalSalaires / $totalEmployes : 0;

        return view('livewire.personnes.etat-employes', [
            'employes' => $employes,
            'totalEmployes' => $totalEmployes,
            'totalSalaires' => $totalSalaires,
            'moyenneSalaire' => $moyenneSalaire
        ]);
    }
}
