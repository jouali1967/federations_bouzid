<?php

namespace App\Livewire\Primes;

use App\Models\Prime;
use App\Models\Personne;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CreatePrime extends Component
{
    #[Rule('required', message: 'La personne est requise')]
    public $personne_id;
    
    #[Rule('required|numeric|min:0', message: 'Le montant est requis et doit être positif')]
    public $montant;
    
    #[Rule('required|date_format:d/m/Y', message: 'La date de sanction est requise')]
    public $date_prime;
    
    #[Rule('required|min:3', message: 'Le motif de la sanction est requis et doit contenir au moins 3 caractères')]
    public $motif_prime;
    
    public $search = '';
    public $personnes = [];
    public $selectedPersonne = null;

    public function formatMontant()
    {
        if ($this->montant === '') {
            return;
        }

        // Supprimer tous les caractères non numériques sauf le point
        $value = preg_replace('/[^0-9.]/', '', $this->montant);
        
        // S'assurer qu'il n'y a qu'un seul point
        $parts = explode('.', $value);
        if (count($parts) > 2) {
            $value = $parts[0] . '.' . $parts[1];
        }
        
        // Limiter à 2 décimales
        if (count($parts) > 1) {
            $value = $parts[0] . '.' . substr($parts[1], 0, 2);
        }

        $this->montant = $value;
    }

    public function updatedSearch()
    {
        // Si la recherche est différente de la personne sélectionnée, réinitialiser la sélection
        if ($this->selectedPersonne && $this->search !== $this->selectedPersonne->nom . ' ' . $this->selectedPersonne->prenom) {
            $this->selectedPersonne = null;
            $this->personne_id = null;
        }

        if (strlen($this->search) >= 2) {
            $this->personnes = Personne::where(function($query) {
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
    }

    public function save()
    {
        $validated = $this->validate();
        Prime::create($validated);
        session()->flash('success', 'Sanction créée avec succès.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.primes.create-prime');
    }
} 