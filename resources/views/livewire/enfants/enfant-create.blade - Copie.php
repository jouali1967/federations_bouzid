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

      {{-- <div class="row mb-3">
        <div class="col-4">
          <label class="form-label">Nom Enfant</label>
          <div class="input-group">
            <input type="text" wire:model.live="nom" class="form-control @error('nom') is-invalid @enderror">
          </div>
          @error('nom')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-4">
          <label class="form-label">Prenom Enfant</label>
          <div class="input-group">
            <input type="text" wire:model.live="prenom" class="form-control @error('prenom') is-invalid @enderror">
          </div>
          @error('prenom')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>
      <div class="row mb-3">
        <div class="col-4">
          <label class="form-label">Sexe</label>
          <div class="border p-2 rounded">
            <div class="d-flex gap-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sexe" id="sexe_m" value="M" wire:model.live="sexe">
                <label class="form-check-label" for="sexe_m">
                  Masculin
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sexe" id="sexe_f" value="F" wire:model.live="sexe">
                <label class="form-check-label" for="sexe_f">
                  Féminin
                </label>
              </div>
            </div>
            @error('sexe')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>

        <div class="col-4">
          <label class="form-label">Date Naissance</label>
          <div class="input-group">
            <input id="datepicker" wire:model="date_nais" class="form-control @error('date_nais') is-invalid @enderror"
              placeholder="JJ/MM/AAAA">
            <span class="input-group-text">
              <i class="fas fa-calendar"></i>
            </span>
          </div>
          @error('date_nais')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div> --}}

      <table class="table table-bordered table-sm">
        <thead>
          <th>Nom</th>
          <th>Prenom</th>
          <th>Sexe</th>
          <th>date_nais</th>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" wire:model.live="nom" class="form-control @error('nom') is-invalid @enderror"></td>
            <td><input type="text" wire:model.live="prenom" class="form-control @error('prenom') is-invalid @enderror">
            </td>
            <td>
              <div class="border p-2 rounded">
                <div class="d-flex gap-4">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexe" id="sexe_m" value="M"
                      wire:model.live="sexe">
                    <label class="form-check-label" for="sexe_m">
                      Masculin
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexe" id="sexe_f" value="F"
                      wire:model.live="sexe">
                    <label class="form-check-label" for="sexe_f">
                      Féminin
                    </label>
                  </div>
                </div>
                @error('sexe')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </td>
            <td>
              <div class="input-group">
                <input id="datepicker" wire:model="date_nais"
                  class="form-control @error('date_nais') is-invalid @enderror" placeholder="JJ/MM/AAAA">
                <span class="input-group-text">
                  <i class="fas fa-calendar"></i>
                </span>
              </div>
              @error('date_nais')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </td>
          </tr>
        </tbody>
      </table>
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
        $wire.set('date_nais', dateStr);
      }
    });
  })
</script>
@endscript