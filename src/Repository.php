<?php

namespace Axn\Crudivor;

use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Model;

class Repository
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * Instance de section avec les informations par défaut des autres sections.
     *
     * @var Section
     */
    protected $default;

    /**
     * Liste des sections enregistrées.
     *
     * @var array[Section]
     */
    protected $sections = [];

    /**
     * Constructeur.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->default = new Section;
    }

    /**
     * Enregistre une nouvelle section.
     *
     * @param  string       $slug
     * @param  string|Model $model
     * @return Section
     */
    public function register($slug, $model)
    {
        $section = clone $this->default;
        $section->setSlug($slug);
        $section->setModel($model);

        $this->sections[$slug] = $section;

        return $section;
    }

    /**
     * Retourne l'instance de section par défaut.
     *
     * @return Section
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Retourne la section correspondant à la route courante.
     *
     * @return Section
     */
    public function getCurrent()
    {
        list(, $slug) = explode('.', $this->router->current()->getName());

        return $this->get($slug);
    }

    /**
     * Retourne une section via la slug.
     *
     * @param  string
     * @return Section
     */
    public function get($slug)
    {
        return $this->sections[$slug];
    }

    /**
     * Retourne la liste de toutes les sections.
     *
     * @return array[Section]
     */
    public function all()
    {
        return $this->sections;
    }
}
