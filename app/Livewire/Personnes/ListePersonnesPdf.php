<?php

namespace App\Livewire\Personnes;

use Livewire\Component;

class ListePersonnesPdf extends Component
{
  public $nom1;
  public $nom2;
  public $date_virement;
  public $num_compte;
    protected $rules = [
        'nom1' => 'required',
        'nom2' => 'required',
        'date_virement' => 'required|date_format:d/m/Y',
        'num_compte' => 'required|numeric|digits:24',
    ];

    protected $messages = [
        'nom1.required' => 'Le nom est obligatoire',
        'nom2.required' => 'Le nom est obligatoire',
        'date_virement.required' => 'La date d\'embauche est obligatoire',
        'date_virement.date_format' => 'La date d\'embauche doit être au format JJ/MM/AAAA',
        'num_compte.required' => 'Le numéro de compte est obligatoire',
        'num_compte.numeric' => 'Le numéro de compte doit être numérique.',
        'num_compte.digits' => 'Le numéro de compte doit comporter exactement 24 chiffres.',
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

  public function generatePdfAndOpenWindow()
  {
    $validatedData = $this->validate();

    // Construct the URL with query parameters
    $url = route('generate.pdf', [
        'nom1' => $validatedData['nom1'],
        'nom2' => $validatedData['nom2'],
        'date_virement' => $validatedData['date_virement'],
        'num_compte' => $validatedData['num_compte'],
    ]);

    $this->dispatch('openPdfWindow', url: $url);
  }

  public function render()
  {
    return view('livewire.personnes.liste-personnes-pdf');
  }
}
