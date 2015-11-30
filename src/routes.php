<?php

$controller = 'Axn\Crudivor\Controller';

foreach (app('crudivor')->all() as $slug => $section)
{
    Route::group($section->getRoutesOptions(), function() use ($section, $slug, $controller)
    {
        get($slug, [
            'as'   => "crudivor.$slug.index",
            'uses' => "$controller@index"
        ]);

        if ($section->creatable) {
            post($slug, [
                'as'   => "crudivor.$slug.store",
                'uses' => "$controller@store"
            ]);
        }

        if ($section->editable) {
            get($slug.'/{id}/edit', [
                'as'   => "crudivor.$slug.edit",
                'uses' => "$controller@edit"
            ]);
            put($slug.'/{id}', [
                'as'   => "crudivor.$slug.update",
                'uses' => "$controller@update"
            ]);
        }

        if ($section->contentEditable) {
            post($slug.'/{id}/content', [
                'as'   => "crudivor.$slug.update-content",
                'uses' => "$controller@updateContent"
            ]);
        }

        if ($section->activatable) {
            post($slug.'/{id}/enable', [
                'as'   => "crudivor.$slug.enable",
                'uses' => "$controller@enable"
            ]);
            post($slug.'/{id}/disable', [
                'as'   => "crudivor.$slug.disable",
                'uses' => "$controller@disable"
            ]);
        }

        if ($section->sortable) {
            post($slug.'/sort', [
                'as'   => "crudivor.$slug.sort",
                'uses' => "$controller@sort"
            ]);
        }

        if ($section->destroyable) {
            delete($slug.'/{id}', [
                'as'   => "crudivor.$slug.destroy",
                'uses' => "$controller@destroy"
            ]);
        }
    });
}
