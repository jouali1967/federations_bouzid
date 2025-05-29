<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title mb-0">Créer une nouvelle sanction</h3>
  </div>

  <div class="card-body">
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
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" wire:model.live="search" class="form-control @error('personne_id') is-invalid @enderror"
              placeholder="Rechercher une personne...">
            @if($selectedPersonne)
            <button type="button" wire:click="clearPersonne" class="btn btn-outline-secondary">
              <i class="fas fa-times"></i>
            </button>
            @endif
          </div>

          @if(count($personnes) > 0 && !$selectedPersonne)
          <div class="position-absolute bg-white shadow-lg rounded-bottom border" style="width: 300px; z-index: 1000;">
            @foreach($personnes as $personne)
            <div wire:click="selectPersonne({{ $personne->id }})"
              class="p-2 border-bottom hover-bg-light cursor-pointer d-flex align-items-center"
              style="transition: all 0.2s ease-in-out;">
              <div class="me-2">
                <i class="fas fa-user-circle text-primary"></i>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold">{{ $personne->nom }} {{ $personne->prenom }}</span>
                  <span class="text-muted ms-2">
                    <i class="fas fa-phone-alt me-1"></i>
                    {{ $personne->phone }}
                  </span>
                </div>
              </div>
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
            <input type="text" wire:model.live="montant" wire:keydown.debounce.500ms="formatMontant"
              class="form-control @error('montant') is-invalid @enderror" placeholder="0.00" pattern="^\d*\.?\d{0,2}$"
              style="font-family: monospace;">
            <span class="input-group-text">DH</span>
          </div>
          @error('montant')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-8">
          <label class="form-label">Date de la sanction</label>
          <div class="input-group">
            <input id="datepicker" wire:model="date_sanction"
              class="form-control @error('date_sanction') is-invalid @enderror" placeholder="JJ/MM/AAAA">
            <span class="input-group-text">
              <i class="fas fa-calendar"></i>
            </span>
          </div>
          @error('date_sanction')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Motif de la sanction</label>
        <textarea wire:model="motif_sanction" class="form-control @error('motif_sanction') is-invalid @enderror"
          rows="3" placeholder="Entrez le motif de la sanction"></textarea>
        @error('motif_sanction')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex justify-content-end">
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
    flatpickr("#datepicker", {
      dateFormat: "d/m/Y",
      onChange: function(selectedDates, dateStr) {
        $wire.set('date_sanction', dateStr);
      }
    });
  })
</script>
@endscript