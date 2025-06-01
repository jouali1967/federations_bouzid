<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DynamicSidebar extends Component
{
    public array $sidebarMenu = [];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $currentUser = Auth::user();
            $definedMenuConfig = [
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
                    'permissions' => ['sanctions.index', 'sanctions.create', 'sanctions.edit', 'sanctions.pdf'],
                    'icon' => 'bi bi-box-seam-fill',
                    'route_group_pattern' => ['sanctions.*'],
                    'items' => [
                        'sanctions.create' => ['title' => 'Ajouter Sanction'],
                        'sanctions.index' => ['title' => 'Liste Sanctions'],
                    ],
                    'order' => 3,
                ],
                'Gestion Salaires' => [
                    'permissions' => ['salaires.gestion', 'salaires.impression'],
                    'icon' => 'bi bi-cash-coin',
                    'route_group_pattern' => ['salaires.*'],
                    'items' => [
                        'salaires.gestion' => ['title' => 'Salaires'],
                        'salaires.impression' => ['title' => 'Impression Salaires'],
                    ],
                    'order' => 4,
                ],
                'Gestion Enfants' => [
                    'permissions' => ['enfants.create'],
                    'icon' => 'bi bi-people-fill',
                    'route_group_pattern' => ['enfants.*'],
                    'items' => [
                        'enfants.create' => ['title' => 'Gestion des enfants'],
                    ],
                    'order' => 4,
                ],
                'Gestion Cnss' => [
                    'permissions' => ['cnss.create'],
                    'icon' => 'bi bi-building-fill-check',
                    'route_group_pattern' => ['cnss.*'],
                    'items' => [
                        'cnss.create' => ['title' => 'Gestion cnss'],
                    ],
                    'order' => 5,
                ],
                'Augmentations' => [
                    'permissions' => ['augmentations.create'],
                    'icon' => 'bi bi-graph-up-arrow',
                    'route_group_pattern' => ['augmentations.*'],
                    'items' => [
                        'augmentations.create' => ['title' => 'Augmentations'],
                    ],
                    'order' => 6,
                ],
                'Declarations' => [
                    'permissions' => ['declarations.create'],
                    'icon' => 'bi bi-file-earmark-text-fill',
                    'route_group_pattern' => ['declarations.*'],
                    'items' => [
                        'declarations.montants' => ['title' => 'Montants Declarés'],
                    ],
                    'order' => 7,
                ],
                'Editions' => [
                    'permissions' => ['editions.employes', 'editions.pdf', 'editions.declares'],
                    'icon' => 'bi bi-printer-fill',
                    'route_group_pattern' => 'editions.*',
                    'items' => [
                        'editions.employes' => ['title' => 'Employés (Edition)'],
                        'editions.pdf' => ['title' => 'PDF (Edition)'],
                        'editions.declares' => ['title' => 'Déclarés (Edition)'],
                    ],
                    'order' => 8,
                ],
            ];

            uasort($definedMenuConfig, function ($a, $b) {
                return ($a['order'] ?? PHP_INT_MAX) <=> ($b['order'] ?? PHP_INT_MAX);
            });

            $builtMenu = [];
            foreach ($definedMenuConfig as $menuTitle => $menuData) {
                $hasTopLevelPermission = false;
                if (isset($menuData['permissions']) && is_array($menuData['permissions'])) {
                    if ($currentUser->hasAnyPermission($menuData['permissions'])) {
                        $hasTopLevelPermission = true;
                    }
                } elseif (!isset($menuData['permissions'])) {
                    $hasTopLevelPermission = true;
                }

                if ($hasTopLevelPermission) {
                    $accessibleSubmenu = [];
                    if (isset($menuData['items']) && is_array($menuData['items'])) {
                        foreach ($menuData['items'] as $itemRouteKey => $itemDetails) {
                            if (Route::has($itemRouteKey) && $currentUser->can($itemRouteKey)) {
                                $accessibleSubmenu[] = [
                                    'route' => $itemRouteKey,
                                    'title' => $itemDetails['title'],
                                ];
                            }
                        }
                    }

                    if (!empty($accessibleSubmenu) || empty($menuData['items'])) {
                        $builtMenu[] = [
                            'title' => $menuTitle,
                            'icon' => $menuData['icon'] ?? 'bi bi-circle',
                            'route_group' => is_array($menuData['route_group_pattern']) ? $menuData['route_group_pattern'] : [$menuData['route_group_pattern']],
                            'submenu' => $accessibleSubmenu,
                            // Add a main route if the item itself should be clickable when it has no submenu or if that's a desired feature
                            // 'route' => $menuData['main_route'] ?? null 
                        ];
                    }
                }
            }
            $this->sidebarMenu = $builtMenu;
        } else {
            $this->sidebarMenu = []; // Ensure it's an empty array if no user is authenticated
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.dynamic-sidebar');
    }
}
