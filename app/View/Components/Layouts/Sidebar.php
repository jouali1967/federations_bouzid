<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; // Important for checking routes

class Sidebar extends Component
{
    public array $accessibleMenu = [];

    // Your menu configuration
    private array $definedMenuConfig = [
        'Employés' => [
            'permissions' => ['personnes.index', 'personnes.create', 'personnes.edit'],
            'icon' => 'bi bi-speedometer',
            'route_group_pattern' => 'personnes.*',
            'items' => [
                'personnes.create' => ['title' => 'Ajouter Employé'],
                'personnes.index' => ['title' => 'Liste Employés'],
            ],
            'order' => 1,
        ],
        'Primes' => [
            'permissions' => ['primes.index', 'primes.create', 'primes.edit'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['primes.*'],
            'items' => [
                'primes.create' => ['title' => 'Ajouter Prime'],
                'primes.index' => ['title' => 'Liste Primes'],
            ],
            'order' => 2,
        ],
        'Sanctions' => [
            'permissions' => ['sanctions.index', 'sanctions.create', 'sanctions.edit','sanctions.pdf'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['sanctions.*'],
            'items' => [
                'sanctions.create' => ['title' => 'Ajouter Sanction'],
                'sanctions.index' => ['title' => 'Liste Sanctions'],
            ],
            'order' => 3,
        ],
        'Gestion Salaires' => [
            // Assuming 'salaires.impression' not 'sanctions.impression'
            'permissions' => ['salaires.gestion', 'salaires.impression'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['salaires.*'],
            'items' => [
                'salaires.gestion' => ['title' => 'Salaires'], // Corrected key
                'salaires.impression' => ['title' => 'Impression Salaires'], // Corrected key
            ],
            'order' => 4,
        ],
        'Gestion Enfants' => [
            'permissions' => ['enfants.create'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['enfants.*'],
            'items' => [
                'enfants.create' => ['title' => 'Gestion des enfants'],
            ],
            'order' => 4, // Note: Same order as 'Gestion Salaires'
        ],
        'Gestion Cnss' => [
            'permissions' => ['cnss.create'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['cnss.*'],
            'items' => [
                'cnss.create' => ['title' => 'Gestion cnss'],
            ],
            'order' => 5,
        ],
        'Augmentations' => [
            'permissions' => ['augmentations.create'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['augmentations.*'],
            'items' => [
                'augmentations.create' => ['title' => 'Augmentations'],
            ],
            'order' => 6,
        ],
        'Declarations' => [
            // Assuming a general permission for the section
            'permissions' => ['declarations.create'],
            'icon' => 'bi bi-box-seam-fill',
            'route_group_pattern' => ['declarations.*'],
            'items' => [
                // Assuming 'declarations.montants' or similar based on title
                'declarations.montants' => ['title' => 'Montants Declarés'],
            ],
            'order' => 7,
        ],
        'Editions' => [
            'permissions' => ['editions.employes', 'editions.pdf','editions.declares'],
            'icon' => 'bi bi-person-lines-fill',
            'route_group_pattern' => 'editions.*',
            'items' => [
                'editions.employes' => ['title' => 'Ajouter Etudiant'],
                'editions.pdf' => ['title' => 'Liste Etudiants'],
                // Consider unique titles or keys for 'editions.declares' if it's different
                'editions.declares' => ['title' => 'Liste Etudiants'],
            ],
            'order' => 8,
        ],
    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::user();
        $filteredMenu = [];

        if ($user) {
            $menuConfig = $this->definedMenuConfig;

            // Sort the menu by 'order' before filtering
            uasort($menuConfig, function ($a, $b) {
                return ($a['order'] ?? PHP_INT_MAX) <=> ($b['order'] ?? PHP_INT_MAX);
            });

            foreach ($menuConfig as $menuTitle => $menuData) {
                $hasTopLevelPermission = false;
                // Check permissions for the main menu item
                if (isset($menuData['permissions']) && is_array($menuData['permissions'])) {
                    if ($user->hasAnyPermission($menuData['permissions'])) {
                        $hasTopLevelPermission = true;
                    }
                } elseif (!isset($menuData['permissions'])) {
                    // If no 'permissions' key, assume public top-level access
                    $hasTopLevelPermission = true;
                }

                if ($hasTopLevelPermission) {
                    $accessibleItems = [];
                    // Check permissions for sub-items
                    if (isset($menuData['items']) && is_array($menuData['items'])) {
                        foreach ($menuData['items'] as $itemRoute => $itemDetails) {
                            // For sub-items, check if the route (as permission) is grantable
                            // AND the route actually exists.
                            if (Route::has($itemRoute) && $user->can($itemRoute)) {
                                $accessibleItems[$itemRoute] = $itemDetails;
                            }
                        }
                    }

                    // Add the menu group if it's public/permitted AND
                    // (it has no sub-items OR it has accessible sub-items)
                    if (empty($menuData['items']) || !empty($accessibleItems)) {
                        $menuData['items'] = $accessibleItems; // Update with filtered items
                        $filteredMenu[$menuTitle] = $menuData;
                    }
                }
            }
        }
        $this->accessibleMenu = $filteredMenu;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // This will load resources/views/components/layouts/sidebar.blade.php
        return view('components.layouts.sidebar');
    }
} 