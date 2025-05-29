<div>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-6">
          <h3 class="card-title">Liste des Sanctions</h3>
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
          <thead class="table-light">
            <tr>
              <th wire:click="sortBy('id')" style="cursor: pointer; padding: 0.25rem 0.5rem;">
                ID
                @if($sortField === 'id')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('personne_id')" style="cursor: pointer; padding: 0.25rem 0.5rem;">
                Employé
                @if($sortField === 'personne_id')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('montant')" style="cursor: pointer; padding: 0.25rem 0.5rem;">
                Montant
                @if($sortField === 'montant')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th wire:click="sortBy('date_sanction')" style="cursor: pointer; padding: 0.25rem 0.5rem;">
                Date
                @if($sortField === 'date_sanction')
                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                @endif
              </th>
              <th style="padding: 0.25rem 0.5rem;">Motif</th>
              <th class="text-center" style="width: 100px; padding: 0.25rem 0.5rem;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sanctions as $sanction)
            <tr>
              <td>{{ $sanction->id }}</td>
              <td>{{ $sanction->personne->nom }} {{ $sanction->personne->prenom }}</td>
              <td>{{number_format($sanction->montant, 2, ',', ' ') }} DH</td>
              <td>{{ $sanction->date_sanction }}</td>
              <td>{{ $sanction->motif_sanction }}</td>
              <td class="text-center" >
                <div class="btn-group btn-group-sm">
                  <a href="{{ route('sanctions.edit', $sanction) }}" 
                     class="btn btn-primary btn-sm rounded-circle" 
                     title="Modifier">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button wire:click="delete({{ $sanction->id }})" 
                          class="btn btn-danger btn-sm rounded-circle"
                          onclick="confirm('Êtes-vous sûr de vouloir supprimer cette sanction ?') || event.stopImmediatePropagation()"
                          title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
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
        {{ $sanctions->links() }}
      </div>
    </div>
  </div>
</div>