<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title mb-0">Ajouter les enfants</h3>
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
        <label class="form-label">Employer</label>
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

      <table class="table table-bordered table-sm">
        <thead>
          <th>Nom</th>
          <th>Prenom</th>
          <th>Sexe</th>
          <th>date_nais</th>
          <th>Action</th>
        </thead>
        <tbody>
          @foreach($enfants as $index => $enfant)
          <tr key="enfant-{{ $index }}">
            <td>
              <input type="text" wire:model.live="enfants.{{ $index }}.nom" class="form-control @error(sprintf('enfants.%s.nom', $index)) is-invalid @enderror">
              @error(sprintf('enfants.%s.nom', $index)) <div class="invalid-feedback">{{ $message }}</div> @enderror
            </td>
            <td>
              <input type="text" wire:model.live="enfants.{{ $index }}.prenom" class="form-control @error(sprintf('enfants.%s.prenom', $index)) is-invalid @enderror">
              @error(sprintf('enfants.%s.prenom', $index)) <div class="invalid-feedback">{{ $message }}</div> @enderror
            </td>
            <td>
              <div class="border p-2 rounded">
                <div class="d-flex gap-4">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="enfants[{{ $index }}][sexe]" id="sexe_m_{{ $index }}" value="M" wire:model.live="enfants.{{ $index }}.sexe">
                    <label class="form-check-label" for="sexe_m_{{ $index }}">
                      Masculin
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="enfants[{{ $index }}][sexe]" id="sexe_f_{{ $index }}" value="F" wire:model.live="enfants.{{ $index }}.sexe">
                    <label class="form-check-label" for="sexe_f_{{ $index }}">
                      Féminin
                    </label>
                  </div>
                </div>
                @error(sprintf('enfants.%s.sexe', $index))
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </td>
            <td>
              <div class="input-group">
                <input id="datepicker_{{ $index }}" wire:model="enfants.{{ $index }}.date_nais" class="form-control @error(sprintf('enfants.%s.date_nais', $index)) is-invalid @enderror" placeholder="JJ/MM/AAAA">
                <span class="input-group-text">
                  <i class="fas fa-calendar"></i>
                </span>
              </div>
              @error(sprintf('enfants.%s.date_nais', $index))
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </td>
            <td>
              @if ($loop->count > 1)
              <button type="button" wire:click="removeChild({{ $index }})" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
              </button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="d-flex justify-content-between mb-3">
          <button type="button" wire:click="addChild" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Ajouter un enfant
          </button>
        <div>
          <button type="button" wire:click="resetForm" class="btn btn-secondary me-2">
            <i class="fas fa-undo me-2"></i>Réinitialiser
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Enregistrer
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@script()
<script>
  window.initializeDatepicker = function(element, index, component) {
    // Ensure the element is valid and not already initialized
    if (element && !element._flatpickr) {
      flatpickr(element, {
        dateFormat: "d/m/Y",
        monthSelectorType: 'static',
        onChange: function(selectedDates, dateStr, instance) {
          component.set(`enfants.${index}.date_nais`, dateStr);
        }
      });
    }
  }

  function setupAllDatepickers(component) {
    component.el.querySelectorAll('input[id^="datepicker_"]').forEach(inputEl => {
      if (inputEl && !inputEl._flatpickr) {
        const index = inputEl.id.split('_')[1];
        window.initializeDatepicker(inputEl, index, component);
      }
    });
  }

  document.addEventListener('livewire:initialized', () => {
    const component = @this;
    setupAllDatepickers(component);

    // Listen for the custom event dispatched from the backend when a row is added
    window.addEventListener('enfant-row-added', event => {
      setTimeout(() => {
        const allDatepickers = component.el.querySelectorAll('input[id^="datepicker_"]');
        if (allDatepickers.length > 0) {
          const lastDatepicker = allDatepickers[allDatepickers.length - 1];
          const lastIndex = lastDatepicker.id.split('_')[1];
          window.initializeDatepicker(lastDatepicker, lastIndex, component);
        }
      }, 50);
    });

    // Listen for when existing enfants are loaded for a selected personne
    window.addEventListener('enfants-loaded', event => {
      setTimeout(() => { // Delay to ensure DOM is updated by Livewire
        setupAllDatepickers(component);
      }, 50);
    });

    // Listen for when enfants list is cleared
    window.addEventListener('enfants-cleared', event => {
      setTimeout(() => { // Delay to ensure DOM is updated by Livewire
        setupAllDatepickers(component);
      }, 50);
    });
  });

  // Optional: Re-run setup on general Livewire updates as a fallback
  Livewire.hook('message.processed', (message, component) => {
    setupAllDatepickers(component);
  });
</script>
@endscript