<div class="container mt-2">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
          <h5 class="mb-0">Modifier la personne</h5>
        </div>
        <div class="card-body py-2">
          <form wire:submit="save">
            <!-- Nom et Prénom -->
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="nom" class="form-label mb-1">
                    Nom <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                    <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror"
                      wire:model.live="nom" placeholder="Entrez le nom">
                  </div>
                  @error('nom')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="prenom" class="form-label mb-1">
                    Prénom <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-user"></i>
                    </span>
                    <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror"
                      wire:model.live="prenom" placeholder="Entrez le prénom">
                  </div>
                  @error('prenom')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Date de naissance et Téléphone -->
            <div class="row g-2">
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="date_nais" class="form-label mb-1">Date de naissance</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-calendar"></i>
                    </span>
                    <input type="text" wire:model='date_nais' id="date_nais_picker"
                      class='form-control @error("date_nais") is-invalid @enderror' placeholder="JJ/MM/AAAA">
                  </div>
                  @error('date_nais')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="phone" class="form-label mb-1">
                    CIN <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-phone"></i>
                    </span>
                    <input id="phone" type="tel" class="form-control @error('cin') is-invalid @enderror"
                      wire:model.live="cin" placeholder="Ex: AA123456">
                  </div>
                  @error('cin')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="phone" class="form-label mb-1">
                    Téléphone <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-phone"></i>
                    </span>
                    <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                      wire:model.live="phone" placeholder="Ex: 0612345678">
                  </div>
                  @error('phone')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Sexe et Situation familiale -->
            <div class="row g-2">
              <div class="col-md-5">
                <div class="card border-primary">
                  <div class="card-header bg-primary text-white py-1">
                    <h6 class="mb-0">Sexe</h6>
                  </div>
                  <div class="card-body py-1">
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
                </div>
              </div>
              <div class="col-md-7">
                <div class="card border-primary">
                  <div class="card-header bg-primary text-white py-1">
                    <h6 class="mb-0">Situation familiale</h6>
                  </div>
                  <div class="card-body py-1">
                    <div class="d-flex gap-4">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="sit_fam" id="sit_fam_m" value="M"
                          wire:model.live="sit_fam">
                        <label class="form-check-label" for="sit_fam_m">
                          Marié(e)
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="sit_fam" id="sit_fam_c" value="C"
                          wire:model.live="sit_fam">
                        <label class="form-check-label" for="sit_fam_c">
                          Célibataire
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="sit_fam" id="sit_fam_d" value="D"
                          wire:model.live="sit_fam">
                        <label class="form-check-label" for="sit_fam_d">
                          Divorcé(e)
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="sit_fam" id="sit_fam_d" value="D"
                          wire:model.live="sit_fam">
                        <label class="form-check-label" for="sit_fam_d">
                          Voeuf(ve)
                        </label>
                      </div>
                    </div>
                    @error('sit_fam')
                    <div class="invalid-feedback d-block">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Adresse -->
            <div class="row g-2">
              <div class="col-12">
                <div class="form-group mb-2">
                  <label for="adresse" class="form-label mb-1">
                    Adresse <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                      wire:model.live="adresse" placeholder="Entrez l'adresse complète">
                  </div>
                  @error('adresse')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Fonction et Email -->
            <div class="row g-2">
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="fonction" class="form-label mb-1">
                    Fonction <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-briefcase"></i>
                    </span>
                    <input id="fonction" type="text" class="form-control @error('fonction') is-invalid @enderror"
                      wire:model.live="fonction" placeholder="Entrez la fonction">
                  </div>
                  @error('fonction')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="categ" class="form-label mb-1">Catégorie</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-list"></i>
                    </span>
                    <select id="categorie" class="form-select form-select-sm @error('categ') is-invalid @enderror"
                      wire:model.live="categ">
                      <option value="">Sélectionnez une catégorie</option>
                      <option value="categorie1">Catégorie 1</option>
                      <option value="categorie2">Catégorie 2</option>
                      <option value="categorie3">Catégorie 3</option>
                    </select>
                  </div>
                  @error('categ')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-2">
                  <label for="email" class="form-label mb-1">Email</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-envelope"></i>
                    </span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                      wire:model.live="email" placeholder="exemple@email.com">
                  </div>
                  @error('email')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Banque et Numéro de compte -->
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="banque" class="form-label mb-1">
                    Banque <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-university"></i>
                    </span>
                    <input id="banque" type="text" class="form-control @error('banque') is-invalid @enderror"
                      wire:model.live="banque" placeholder="Entrez la banque">
                  </div>
                  @error('banque')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
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

            <!-- Date d'embauche et Salaire de base -->
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="date_embauche" class="form-label mb-1">Date d'embauche <span
                      class="text-danger">*</span></label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-calendar"></i>
                    </span>
                    <input type="text" wire:model='date_embauche' id="datepicker"
                      class='form-control @error("date_embauche") is-invalid @enderror' placeholder="JJ/MM/AAAA">
                  </div>
                  @error('date_embauche')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-2">
                  <label for="salaire_base" class="form-label mb-1">
                    Salaire de base <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">
                      <i class="fas fa-money-bill"></i>
                    </span>
                    <input id="salaire_base" type="number"
                      class="form-control @error('salaire_base') is-invalid @enderror" wire:model.live="salaire_base"
                      step="0.01" min="0" placeholder="Entrez le salaire de base">
                  </div>
                  @error('salaire_base')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group text-center mt-3">
              <button type="submit" class="btn btn-primary px-5">
                <span>
                  <i class="fas fa-save me-1"></i> Enregistrer
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
    flatpickr("#datepicker", {
      dateFormat: "d/m/Y",
      onChange: function(selectedDates, dateStr) {
        $wire.set('date_embauche', dateStr);
      }
    });

    flatpickr("#date_nais_picker", {
      dateFormat: "d/m/Y",
      onChange: function(selectedDates, dateStr) {
        $wire.set('date_nais', dateStr);
      }
    });
  })
</script>
@endscript