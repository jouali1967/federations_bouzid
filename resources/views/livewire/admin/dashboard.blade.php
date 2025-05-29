<div class="container-fluid p-0">
  <div class="position-relative">
      <!-- Image de fond -->
      <img src="{{ asset('assets/images/employers.jpg?v=' . time()) }}" alt="Dashboard" 
           class="w-100" 
           style="height: calc(88vh - 56px); object-fit: cover; filter: brightness(0.85);"
           alt="École avec étudiants">
      
      <!-- Overlay avec le texte -->
      <div class="position-absolute bottom-0 start-0 w-100" 
           style="background: linear-gradient(to top, rgba(13, 110, 253, 0.9), rgba(13, 110, 253, 0.4));">
          <div class="container py-4">
              <div class="text-white">
                  <h1 class="display-5 fw-bold mb-2">Bienvenue dans cette application</h1>
                  <p class="lead mb-0">Gestion des Employés</p>
              </div>
          </div>
      </div>
  </div>
</div>

