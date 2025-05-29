<div>
  <div>
    <form wire:submit.prevent="afficherSalaires">
      <div class="d-flex align-items-end mb-1" style="gap: 8px;">
        <div>
          <label for="date_virement" class="form-label  fw-bold">Période de virement</label>
          <input type="text" class="form-control @error('date_virement') is-invalid @enderror" id="date_virement" wire:model.live="date_virement" 
             style="width: 300px;" placeholder="MM/YYYY">
          @error('date_virement')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <button type="submit" class="btn btn-primary" style="margin-top: 24px;">
            <i class="fas fa-calculator me-2"></i>
            Afficher les Salaires
          </button>
        </div>
      </div>
    </form>
    @if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
      class="alert alert-success mt-4">
      <i class="fas fa-check-circle me-2"></i>
      {{ session('success') }}
    </div>
    @endif
  </div>
  @if(count($salaires))
  <div id="salaire-table-section">
    <div class="d-flex align-items-center justify-content-between mb-1" style="gap: 8px;">
      <div style="flex:1; text-align:left;">
        <button class="btn btn-primary btn-sm no-print" wire:click.prevent="imprimerPDF" @ouvrir-pdf.window="window.open($event.detail, '_blank')">
          Imprimer (PDF)
        </button>
      </div>
      <div class="mx-auto" style="flex:1; text-align:center;">
        <h6 class="fw-bold mb-0" style="font-size:1rem;">Période :
          @if(isset($mois) && isset($annee))
            {{ str_pad($mois, 2, '0', STR_PAD_LEFT) }}/{{ $annee }}
          @endif
        </h6>
      </div>
      <div style="flex:1; text-align:right;">
        <input type="text" class="form-control form-control-sm" style="max-width: 220px; display:inline-block;" placeholder="Rechercher par nom ou prénom..." wire:model.live="search">
      </div>
    </div>

    <table class="table table-bordered table-sm mt-1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Salaire de Base</th>
          <th>Montant des Primes</th>
          <th>Montant des Sanctions</th>
          <th>Salaire Mensuel</th>
        </tr>
      </thead>
      <tbody>
        @foreach($salaires as $salaire)
        <tr>
          <td>{{ $salaire->personne->id }}</td>
          <td>{{ $salaire->personne->nom }} {{ $salaire->personne->prenom }}</td>
          <td style="text-align: right;">{{ $salaire->salaire_base }}</td>
          <td  style="text-align: right;">{{ $salaire->montant_prime }}</td>
          <td  style="text-align: right;">{{ $salaire->montant_sanction }}</td>
          <td  style="text-align: right;">{{ $salaire->montant_vire }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @if(method_exists($salaires, 'links'))
    <div>
      {{ $salaires->links() }}
    </div>
    @endif
  </div>
  @else
  <div class="alert alert-warning mt-4">Aucun résultat trouvé.</div>
  @endif


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
    });
  </script>
  @endscript

</div>