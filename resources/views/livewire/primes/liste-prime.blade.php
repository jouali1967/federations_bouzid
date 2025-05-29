<div>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <h3 class="card-title">Liste des Primes</h3>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <input wire:model.live="search" type="text" class="form-control"
              placeholder="Rechercher par nom, prénom, motif ou montant...">
            <span class="input-group-text">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      @if (session()->has('message'))
      <div class="alert alert-success">
        {{ session('message') }}
      </div>
      @endif

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
          <thead>
            <tr>
              <th wire:click="sortBy('id')" style="cursor: pointer;">
                ID
                @if($sortField === 'id')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('personne_id')" style="cursor: pointer;">
                Employé
                @if($sortField === 'personne_id')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('montant')" style="cursor: pointer;">
                Montant
                @if($sortField === 'montant')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('date_prime')" style="cursor: pointer;">
                Date
                @if($sortField === 'date_prime')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th>Motif</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($primes as $prime)
            <tr>
              <td>{{ $prime->id }}</td>
              <td>{{ $prime->personne->nom }} {{ $prime->personne->prenom }}</td>
              <td>{{ number_format($prime->montant, 2,',',' ') }} DH</td>
              <td>{{ $prime->date_prime }}</td>
              <td>{{ $prime->motif_prime }}</td>
              <td>
                <a href="{{ route('primes.edit', $prime) }}" class="btn btn-primary btn-sm rounded-circle">
                  <i class="fas fa-edit"></i>
                </a>
                <button wire:click="delete({{ $prime->id }})" class="btn btn-danger btn-sm rounded-circle"
                  onclick="confirm('Êtes-vous sûr de vouloir supprimer cette sanction ?') || event.stopImmediatePropagation()">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center">Aucune sanction trouvée</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $primes->links() }}
      </div>
    </div>
  </div>
</div>