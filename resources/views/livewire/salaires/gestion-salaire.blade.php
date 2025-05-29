<div class="container min-vh-100 d-flex align-items-center">
  <div class="row justify-content-center w-100">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">
            <i class="fas fa-calculator me-2"></i>
            Calcul des Salaires
          </h4>
        </div>
        <div class="card-body">
          <p class="text-muted mb-4">
            Sélectionnez le mois et l'année pour calculer les salaires de tous les employés,
            en prenant en compte les primes et les sanctions.
          </p>

          <form wire:submit.prevent="calculerSalaire">
            <div class="form-group mb-4">
              <label for="date_virement" class="form-label fw-bold">Période de virement</label>
              <input type="text" class="form-control" id="date_virement" wire:model="date_virement" required
                style="width: 200px;" placeholder="MM/YYYY">
              @error('date_virement')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-calculator me-2"></i>
              Calculer les Salaires
            </button>
          </form>

          @if (session()->has('success'))
          <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="alert alert-success mt-4">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@script()
<script>
  $(document).ready(function(){
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    
    flatpickr("#date_virement", {
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
        $wire.set('date_virement', dateStr);
      }
    });
  })
</script>
@endscript