<div class="container ">
  <div class="row ">
    <div class="">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
          <h4 class="mb-0" style="font-size: 1.2rem;">
            <i class="fas fa-calculator me-2"></i>
            Liste des employés déclarés
          </h4>
        </div>
        <div class="card-body">
          <form wire:submit.prevent="affcherPersonnesDeclares">
            <div class="d-flex align-items-end mb-4">
              <div class="form-group me-3 mb-0">
                <label for="date_declaration" class="form-label fw-bold">Période de virement</label>
                <input type="text" class="form-control" id="date_declaration" wire:model="date_declaration" required
                  style="width: 200px;" placeholder="MM/YYYY">
                @error('date_declaration')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary mb-1">
                <i class="fas fa-calculator me-2"></i>
                Afficher les employés
              </button>
            </div>
          </form>
          @if($employes && count($employes) > 0)
          <div class="d-flex justify-content-end mt-4 mb-2">
            <button type="button" class="btn btn-success" wire:click="generatePdf">
              <i class="fas fa-print me-1"></i>Imprimer
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow-sm rounded">
              <thead class="table-primary">
                <tr>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>N° CNSS</th>
                  <th>Enfants</th>
                  <th>Situation familiale</th>
                  <th>Salaire de base</th>
                  <th>Montant déclaré</th>
                </tr>
              </thead>
              <tbody>
                @foreach($employes as $employe)
                <tr>
                  <td>{{ $employe['nom'] }}</td>
                  <td>{{ $employe['prenom'] }}</td>
                  <td>{{ $employe['num_cnss'] ?? '-' }}</td>
                  <td>
                    <span class="badge bg-info text-dark">{{ $employe['nombre_enfants'] }}</span>
                  </td>
                  <td>{{ $employe['situation_famille'] }}</td>
                  <td><span class="fw-bold text-success">{{ number_format($employe['salaire_base'], 0, ',', ' ') }}
                      Dhs</span></td>
                  <td><span class="fw-bold text-primary">{{ number_format($employe['montant_dec'], 0, ',', ' ') }}
                      Dhs</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-center mt-3">
            <nav>
              <ul class="pagination">
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                  <button class="page-link" wire:click="goToPage({{ $currentPage - 1 }})">&laquo;</button>
                </li>
                @for ($page = 1; $page <= $totalPages; $page++) <li
                  class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                  <button class="page-link" wire:click="goToPage({{ $page }})">{{ $page }}</button>
                  </li>
                  @endfor
                  <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                    <button class="page-link" wire:click="goToPage({{ $currentPage + 1 }})">&raquo;</button>
                  </li>
              </ul>
            </nav>
          </div>
          @elseif($employes)
          <div class="alert alert-warning mt-4">
            Aucun employé déclaré pour cette période.
          </div>
          @endif

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
    
    flatpickr("#date_declaration", {
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
        $wire.set('date_declaration', dateStr);
      }
    });

    window.addEventListener('openDecWindow', event => {
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