<?php

/*
 * Définition des informations par défaut :
 * =========================================================================
 */
Crudivor::getDefault()
    ->creatableAndEditable(true, [
        'filters' => [
            'libelle' => 'trim|stripped'
        ],
        'rules' => [
            'libelle' => 'required|max:100'
        ],
        'data' => function($request, $section) {
            return [
                'libelle' => $request->input('libelle')
            ];
        }
    ])
    ->contentEditable(true, 'libelle', [
        'filters'  => 'trim|stripped',
        'rules'    => 'required|max:100',
        'messages' => function($request, $section) {
            return [
                'required' => "Vous ne pouvez saisir un texte vide.",
                'max'      => "Vous ne pouvez saisir plus de 100 caractères."
            ];
        }
    ])
    ->activatable(true, 'actif')
    ->sortable(true, 'ordre')
    ->destroyable(true)
    ->routesOptions([
        'middleware' => 'auth'
    ]);

/*
 * Enregistrement des différentes sections :
 * =========================================================================
 */
Crudivor::register('examples', 'App\Models\Example')
    ->sortable(false);
