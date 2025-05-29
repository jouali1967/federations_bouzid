<div class="container mt-2">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Liste des personnes</h5>
          <a href="{{ route('personnes.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Nouvelle personne
          </a>
        </div>

        <div class="card-body">
          <div class="mb-3">
            <div class="input-group" style="max-width: 600px;">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" wire:model.live="search" placeholder="Rechercher...">
              <select id="categorie" class="form-select form-select-sm ms-2" wire:model.live="categ">
                <option value="">Sélectionnez une catégorie</option>
                <option value="categorie1">Catégorie 1</option>
                <option value="categorie2">Catégorie 2</option>
                <option value="categorie3">Catégorie 3</option>
              </select>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm table-striped">
              <thead>
                <tr>
                  <th wire:click="sortBy('nom')" style="cursor: pointer;">
                    Nom
                    @if($sortField === 'nom')
                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                    @else
                    <i class="fas fa-sort ms-1 text-muted"></i>
                    @endif
                  </th>
                  <th wire:click="sortBy('prenom')" style="cursor: pointer;">
                    Prénom
                    @if($sortField === 'prenom')
                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                    @else
                    <i class="fas fa-sort ms-1 text-muted"></i>
                    @endif
                  </th>
                  <th>Téléphone</th>
                  <th wire:click="sortBy('date_embauche')" style="cursor: pointer;">
                    Date d'embauche
                    @if($sortField === 'date_embauche')
                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                    @else
                    <i class="fas fa-sort ms-1 text-muted"></i>
                    @endif
                  </th>
                  <th wire:click="sortBy('date_nais')" style="cursor: pointer;">
                    Date de Naissance
                    @if($sortField === 'date_nais')
                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                    @else
                    <i class="fas fa-sort ms-1 text-muted"></i>
                    @endif
                  </th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($personnes as $personne)
                <tr>
                  <td>{{ $personne->nom }}</td>
                  <td>{{ $personne->prenom }}</td>
                  <td>{{ $personne->phone }}</td>
                  <td>{{ $personne->date_embauche }}</td>
                  <td>{{ $personne->date_nais }}</td>
                  <td class="text-center">
                    <a href="{{ route('personnes.edit', $personne->id) }}" class="btn btn-sm btn-info me-1"
                      title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button wire:click="delete({{ $personne->id }})" class="btn btn-sm btn-danger" title="Supprimer"
                      onclick="confirm('Êtes-vous sûr de vouloir supprimer cette personne ?') || event.stopImmediatePropagation()">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center">Aucune personne trouvée</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-3">
            {{ $personnes->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@script()
<script>
  // Gestion des notifications de suppression
    Livewire.on('personne-deleted', (data) => {
        Swal.fire({
            title: data.title,
            text: data.message,
            icon: data.type,
            confirmButtonText: 'OK'
        });
    });
</script>
@endscript