<?php

namespace Axn\Crudivor;

use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Model;

class Repository
{
    /**
     *
     * @var Router
     */
    protected $router;

    /**
     *
     * @var Section
     */
    protected $default;

    /**
     *
     * @var array[Section]
     */
    protected $sections = [];

    /**
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->default = new Section;
    }

    /**
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
     *
     * @return Section
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     *
     * @return Section
     */
    public function getCurrent()
    {
        list(, $slug) = explode('.', $this->router->current()->getName());

        return $this->get($slug);
    }

    /**
     *
     * @param  string
     * @return Section
     */
    public function get($slug)
    {
        return $this->sections[$slug];
    }

    /**
     *
     * @return array[Section]
     */
    public function all()
    {
        return $this->sections;
    }
}
