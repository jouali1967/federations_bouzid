<?php

namespace App\Livewire\Salaires;

use App\Models\Salaire;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

class SalaireImpression extends Component
{
    use WithPagination;
    public $date_virement;
    public $dateVirement;
    public $mois;
    public $annee;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
      'date_virement' => 'required|regex:/^\d{2}\/\d{4}$/',
    ];

    protected $messages = [
      'date_virement.required' => 'La date de virement est requise.',
      'date_virement.regex' => 'Le format de la date doit être MM/YYYY (ex: 03/2024).',
    ];

    public function afficherSalaires()
    {
        $this->validate();
        $this->dateVirement = Carbon::createFromFormat('m/Y', $this->date_virement);
        $this->mois = $this->dateVirement->format('m');
        $this->annee = $this->dateVirement->format('Y');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function imprimerPDF()
    {
        $mois = $this->mois;
        $annee = $this->annee;
        $this->dispatch('ouvrir-pdf', route('salaires.impression.pdf', [
          'mois' => $mois,
          'annee' => $annee,
        ]));
    }

    public function render()
    {
        $salaires = [];
        if ($this->mois && $this->annee) {
            $salaires = Salaire::with('personne')
                ->whereMonth('date_virement', $this->mois)
                ->whereYear('date_virement', $this->annee)
                ->when($this->search, function ($query) {
                    $query->whereHas('personne', function ($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%')
                          ->orWhere('prenom', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderByDesc('id')
                ->paginate(10);
        }
        return view('livewire.salaires.salaire-impression', [
            'salaires' => $salaires
        ]);
    }
}
