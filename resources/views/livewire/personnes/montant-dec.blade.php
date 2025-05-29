<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title mb-0">Montant Declaré</h3>
  </div>

  <div class="card-body">
    @if (session()->has('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('failed') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form wire:submit="save">
      <div class="mb-3">
        <label class="form-label">Personne</label>
        @if($selectedPersonne)
        <div class="alert alert-info mb-2">
          <div class="row">
            <div class="col-md-6">
              <strong>Nom :</strong> {{ $selectedPersonne->nom }} {{ $selectedPersonne->prenom }}
            </div>
            <div class="col-md-6">
              <strong>Téléphone :</strong> {{ $selectedPersonne->phone }}
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-6">
              <strong>Adresse :</strong> {{ $selectedPersonne->adresse }}
            </div>
            <div class="col-md-6">
              <strong>Date d'embauche :</strong> {{ $selectedPersonne->date_embauche }}
            </div>
          </div>
        </div>
        @endif
        <div class="position-relative">
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" wire:model.live="search" class="form-control @error('personne_id') is-invalid @enderror"
              placeholder="Rechercher une personne..." style="max-width: 300px;">
            @if($selectedPersonne)
            <button type="button" wire:click="clearPersonne" class="btn btn-outline-secondary">
              <i class="fas fa-times"></i>
            </button>
            @endif
          </div>

          @if(count($personnes) > 0 && !$selectedPersonne)
          <div class="position-absolute w-100 bg-white shadow-sm rounded-bottom border"
            style="max-width: 300px; z-index: 1000;">
            @foreach($personnes as $personne)
            <div wire:click="selectPersonne({{ $personne->id }})" class="p-2 hover-bg-light cursor-pointer">
              {{ $personne->nom }} {{ $personne->prenom }}
            </div>
            @endforeach
          </div>
          @endif
        </div>
        @error('personne_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row mb-3">
        <div class="col-4">
          <label class="form-label">Montant</label>
          <div class="input-group">
            <input type="text" wire:model.live="mont_dec" {{-- wire:keydown.debounce.500ms="formatMontant" --}}
              class="form-control @error('mont_dec') is-invalid @enderror" placeholder="0.00" pattern="^\d*\.?\d{0,2}$"
              style="font-family: monospace;">
            <span class="input-group-text">DH</span>
          </div>
          @error('mont_dec')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-8">
          <div>
            <label for="date_dec" class="form-label  fw-bold">Période de declaration</label>
            <input type="text" class="form-control @error('date_dec') is-invalid @enderror" id="date_dec"
              wire:model.live="date_dec" style="width: 300px;" placeholder="MM/YYYY">
            @error('date_dec')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

        </div>
      </div>


<div class="d-flex justify-content-end gap-3">
  {{-- Bouton "Tout" : valider uniquement date_dec --}}
  <button type="button" wire:click="montant_dec" class="btn btn-primary">
    <i class="fas fa-save me-2"></i>Tout
  </button>

  {{-- Bouton "Enregistrer" : valider tout le formulaire --}}
  <button type="submit" class="btn btn-primary">
    <i class="fas fa-save me-2"></i>Enregistrer
  </button>
</div>
    </form>
  </div>
</div>

  @script()
  <script>
    $(document).ready(function(){
      const currentDate = new Date();
      const currentYear = currentDate.getFullYear();
      
      flatpickr("#date_dec", {
        dateFormat: "m/Y",
        locale: {
          firstDayOfWeek: 1,
          weekdays: {
            shorthand: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
            longhand: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
          },
          months: {
            shorthand: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Aoû", "Sep", "Oct", "Nov", "Déc"],
            longhand: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
          }
        },
        plugins: [
          new monthSelectPlugin({
            shorthand: true,
            dateFormat: "m/Y",
            altFormat: "F Y",
            theme: "light",
            yearRange: [currentYear - 2, currentYear + 2]
          })
        ],
        onChange: function(selectedDates, dateStr) {
          $wire.set('date_dec', dateStr);
        }
      });
    });
  </script>
  @endscript
