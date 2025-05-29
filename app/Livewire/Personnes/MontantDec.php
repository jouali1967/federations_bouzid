<?php

namespace App\Livewire\Personnes;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Personne;
use App\Models\Declaration;
use Livewire\Attributes\Rule;

class MontantDec extends Component
{
  #[Rule('required', message: 'La personne est requise')]
  public $personne_id;

  #[Rule('required|numeric|min:0', message: 'Le montant est requis et doit être positif')]
  public $mont_dec;
  public $date_dec;
  protected $rules = [
    'date_dec' => 'required|regex:/^\d{2}\/\d{4}$/',
  ];
  public $results = [];
  protected $messages = [
    'date_dec.required' => 'La date de virement est requise.',
    'date_dec.regex' => 'Le format de la date doit être MM/YYYY (ex: 03/2024).',
  ];

  public $search = '';
  public $personnes = [];
  public $selectedPersonne = null;



  public function render()
  {
    return view('livewire.personnes.montant-dec');
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

      // Fetch the latest declaration for this person
      $latestDeclaration = Declaration::where('personne_id', $this->personne_id)
        ->orderByDesc('date_dec')
        ->first();

      if ($latestDeclaration) {
        $this->mont_dec = $latestDeclaration->mont_dec;
        $this->date_dec = Carbon::parse($latestDeclaration->date_dec)->format('m/Y');
      } else {
        $this->mont_dec = null;
        $this->date_dec = null; // Or keep existing date_dec if user typed it before selecting person? For now, clearing.
      }
    }
  }
  public function clearPersonne()
  {
    $this->search = '';
    $this->selectedPersonne = null;
    $this->personne_id = null;
    $this->personnes = [];
    $this->mont_dec = null;
    $this->date_dec = null; // Clear date_dec when person is cleared
  }

  public function updatedDateDec($value)
  {
    if ($this->personne_id && !empty($value)) {
      try {
        $dateVirement = Carbon::createFromFormat('m/Y', $value);
        $declaration = Declaration::where('personne_id', $this->personne_id)
          ->whereMonth('date_dec', $dateVirement->format('m'))
          ->whereYear('date_dec', $dateVirement->format('Y'))
          ->first();

        if ($declaration) {
          $this->mont_dec = $declaration->mont_dec;
        } // If no declaration, do not change mont_dec, preserving user input
      } catch (\Exception $e) {
        // Handle potential date parsing errors, though validation should prevent most
        // Do not change mont_dec on error either, to preserve user input
      }
    } elseif (empty($value)) {
      // If date_dec is cleared, clear mont_dec
      $this->mont_dec = null;
    }
  }

  public function save()
  {
    $validatedData = $this->validate();

    // Prepare data for saving, using validated personne_id and mont_dec
    $saveData = [
      'personne_id' => $validatedData['personne_id'],
      'mont_dec'    => $validatedData['mont_dec'],
    ];

    // $this->date_dec is the MM/YYYY string from the form, validated
    $formDatePeriod = Carbon::createFromFormat('m/Y', $this->date_dec)->startOfMonth();

    $declaration = Declaration::where('personne_id', $saveData['personne_id'])
      ->whereMonth('date_dec', $formDatePeriod->month)
      ->whereYear('date_dec', $formDatePeriod->year)
      ->orderByDesc('date_dec') // If multiple in month, target the latest one
      ->first();

    if ($declaration) {
      // Update existing declaration: Preserve its original day
      //$saveData['date_dec'] = Carbon::parse($declaration->date_dec)->format('Y-m-d');
      $saveData['date_dec'] = $declaration->date_dec;
      $declaration->update($saveData);
      session()->flash('success', 'Déclaration mise à jour avec succès !');
    } else {
      // Create new declaration: Use 1st of the month from the form's period
      $saveData['date_dec'] = $formDatePeriod->format('Y-m-d');
      //dd($saveData);
      Declaration::create($saveData);
      session()->flash('success', 'Déclaration enregistrée avec succès !');
    }
    // Optionally reset form fields or redirect
    $this->reset(['mont_dec', 'date_dec', 'personne_id', 'search', 'selectedPersonne', 'personnes']);
  }
  public function montant_dec()
  {
    $this->validateOnly('date_dec', [
      'date_dec' => ['required', 'regex:/^\d{2}\/\d{4}$/'],
    ], [
      'date_dec.required' => 'La date de virement est requise.',
      'date_dec.regex' => 'Le format de la date doit être MM/YYYY (ex: 03/2024).',
    ]);
    $dateDec = Carbon::createFromFormat('m/Y', $this->date_dec);
    //$date_tr=$dateDec->copy()->subMonth();
    $mois = $dateDec->format('m');
    $annee = $dateDec->format('Y');
    $decExistant = Declaration::whereMonth('date_dec', $dateDec->format('m'))
      ->whereYear('date_dec', $dateDec->format('Y'))
      ->get();
    if (count($decExistant)) {
      session()->flash('success', 'Déclaration deja existe !');
    } else {
      $results_prec = Declaration::whereMonth('date_dec', $dateDec->format('m') - 1)
        ->whereYear('date_dec', $dateDec->format('Y'))
        ->get();
      foreach ($results_prec as $result) {
        Declaration::create([
          'personne_id' => $result->personne_id,
          'date_dec'    => $result->date_dec->copy()->addMonth()->format('Y-m-d'),
          'mont_dec'    => $result->mont_dec,
        ]);
      }
      $this->reset(['mont_dec', 'date_dec', 'personne_id', 'search', 'selectedPersonne', 'personnes']);

      session()->flash('success', 'Déclaration enregistrée avec succès !');
    }
  }
}
