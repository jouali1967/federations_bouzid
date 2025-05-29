{{-- <div>
  <button onclick="window.open('{{ route('generate.pdf') }}', '_blank')">Générer PDF</button>
</div> --}}
<div class="container mt-2">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
          <h5 class="mb-0">Demande de Virement</h5>
        </div>
        <div class="card-body py-2">
          <form wire:submit="save">
            <!-- date virement et numero de compte -->
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="date_nais" class="form-label mb-1">Date de virement</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-calendar"></i>
                    </span>
                    <input type="text" wire:model='date_virement' id="date_virement"
                      class='form-control @error("date_virement") is-invalid @enderror' placeholder="JJ/MM/AAAA">
                  </div>
                  @error('date_virement')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="num_compte" class="form-label mb-1">
                    Numéro de compte <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-credit-card"></i>
                    </span>
                    <input id="num_compte" type="text" class="form-control @error('num_compte') is-invalid @enderror"
                      wire:model.live="num_compte" placeholder="Entrez le numéro de compte">
                  </div>
                  @error('num_compte')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <!-- Noms des signataire -->
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="nom" class="form-label mb-1">
                    Nom du Signataire N°1 <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                    <input id="nom1" type="text" class="form-control @error('nom1') is-invalid @enderror"
                      wire:model.live="nom1" placeholder="Entrez du signataire">
                  </div>
                  @error('nom1')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="prenom" class="form-label mb-1">
                    Nom du Signataire N°2 <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                    <input id="nom2" type="text" class="form-control @error('nom2') is-invalid @enderror"
                      wire:model.live="nom2" placeholder="Entrez nom signataire">
                  </div>
                  @error('nom2')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="form-group text-center mt-3">
              <button type="button" class="btn btn-primary px-5" wire:click="generatePdfAndOpenWindow">
                <span>
                  <i class="fas fa-save me-1"></i> Imprimer
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@script()
<script>
  $(document).ready(function(){
    flatpickr("#date_virement", {
      dateFormat: "d/m/Y",
      onChange: function(selectedDates, dateStr) {
        $wire.set('date_virement', dateStr);
      }
    });

    window.addEventListener('openPdfWindow', event => {
      // Access the URL from the event detail
      const url = event.detail.url;
      if (url) {
        window.open(url, '_blank');
      } else {
        // Fallback or error handling if URL is not provided, though it should be
        console.error('PDF URL not provided in event detail.');
        // Optionally, open the static route as a fallback if that makes sense
        // window.open('{{ route('generate.pdf') }}', '_blank'); 
      }
    });
  })
</script>
@endscript