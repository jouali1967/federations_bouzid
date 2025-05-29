<?php

namespace App\Livewire\Salaires;

use App\Models\Prime;
use App\Models\Salaire;
use Livewire\Component;
use App\Models\Personne;
use App\Models\Sanction;
use Illuminate\Support\Carbon;

class GestionSalaire extends Component
{
  public $date_virement;

  protected $rules = [
    'date_virement' => 'required|regex:/^\d{2}\/\d{4}$/',
  ];

  protected $messages = [
    'date_virement.required' => 'La date de virement est requise.',
    'date_virement.regex' => 'Le format de la date doit être MM/YYYY (ex: 03/2024).',
  ];

  public function calculerSalaire()
  {
    $this->validate();
    // Convertir la date du format m/Y en Carbon
    $dateVirement = Carbon::createFromFormat('m/Y', $this->date_virement);
    // Récupérer les salaires existants pour le mois et l'année spécifiés
    $salairesExistants = Salaire::whereMonth('date_virement', $dateVirement->format('m'))
      ->whereYear('date_virement', $dateVirement->year)
      ->get();
      
    // Récupérer toutes les personnes
    $personnes = Personne::all();
    foreach ($personnes as $personne) {
      // Calculer les sanctions et les primes
      $montantSanction = Sanction::where('personne_id', $personne->id)
        ->whereMonth('date_sanction', $dateVirement->format('m'))
        ->whereYear('date_sanction', $dateVirement->year)
        ->sum('montant');
      $montantPrime = Prime::where('personne_id', $personne->id)
        ->whereMonth('date_prime', $dateVirement->format('m'))
        ->whereYear('date_prime', $dateVirement->year)
        ->sum('montant');
      // Calculer le salaire total
      $salaireTotal = $personne->salaire_base + $montantPrime - $montantSanction;
      // Vérifier si un salaire pour cette personne existe déjà
      $salaireExist = $salairesExistants->firstWhere('personne_id', $personne->id);
      if ($salaireExist) {
        $salaireExist->update([
          'montant_vire' => $salaireTotal,
          'date_virement' => $dateVirement->format('Y-m-d'),
          'montant_sanction' => $montantSanction,
          'montant_prime' => $montantPrime,
          'salaire_base' => $personne->salaire_base,
        ]);
      } else {
        Salaire::create([
          'personne_id' => $personne->id,
          'montant_vire' => $salaireTotal,
          'date_virement' => $dateVirement->format('Y-m-d'),
          'montant_sanction' => $montantSanction,
          'montant_prime' => $montantPrime,
          'salaire_base' => $personne->salaire_base,
        ]);
      }
    }

    session()->flash('success', 'Salaires calculés et enregistrés avec succès !');
  }

  public function render()
  {
    return view('livewire.salaires.gestion-salaire');
  }
}
