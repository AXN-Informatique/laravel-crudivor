<?php

namespace Axn\Crudivor;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Axn\RequestFilters\Filters;

class Section
{
    /**
     *
     * @var string|null
     */
    protected $slug = null;

    /**
     *
     * @var string|Model|null
     */
    protected $model = null;

    /**
     *
     * @var boolean
     */
    public $creatable = false;

    /**
     *
     * @var boolean
     */
    public $editable = false;

    /**
     *
     * @var boolean
     */
    public $contentEditable = false;

    /**
     *
     * @var boolean
     */
    public $activatable = false;

    /**
     *
     * @var boolean
     */
    public $sortable = false;

    /**
     *
     * @var boolean
     */
    public $destroyable = false;

    /**
     *
     * @var array
     */
    protected $createOptions = [];

    /**
     *
     * @var array
     */
    protected $editOptions = [];

    /**
     *
     * @var array
     */
    protected $editContentOptions = [];

    /**
     *
     * @var string
     */
    protected $editContentField= '';

    /**
     *
     * @var string
     */
    protected $activeField = '';

    /**
     *
     * @var string
     */
    protected $sortField = '';

    /**
     *
     * @var array
     */
    protected $routesOptions = [];

    /**
     *
     * @param  string $slug
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     *
     * @param  string|Model $model
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     *
     * @return Model
     */
    public function getModel()
    {
        if (is_string($this->model)) {
            $this->model = new $this->model;
        }

        return $this->model;
    }

    /**
     *
     * @param  boolean $bool
     * @param  array   $options
     * @return Section
     */
    public function creatable($bool = true, array $options = [])
    {
        $this->creatable = $bool;

        if (!empty($options)) {
            $this->createOptions = $options;
        }

        return $this;
    }

    /**
     *
     * @param  boolean $bool
     * @param  array   $options
     * @return Section
     */
    public function editable($bool = true, array $options = [])
    {
        $this->editable = $bool;

        if (!empty($options)) {
            $this->editOptions = $options;
        }

        return $this;
    }

    /**
     *
     * @param  boolean $bool
     * @param  array   $options
     * @return Section
     */
    public function creatableAndEditable($bool = true, array $options = [])
    {
        return $this->creatable($bool, $options)->editable($bool, $options);
    }

    /**
     *
     * @param  boolean $bool
     * @param  string  $field
     * @param  array   $options
     * @return Section
     */
    public function contentEditable($bool = true, $field = '', array $options = [])
    {
        $this->contentEditable = $bool;

        if (!empty($field)) {
            $this->contentField = $field;
        }
        if (!empty($options)) {
            $this->editContentOptions = $options;
        }

        return $this;
    }

    /**
     *
     * @param  boolean $bool
     * @param  string  $field
     * @return Section
     */
    public function activatable($bool = true, $field = '')
    {
        $this->activatable = $bool;

        if (!empty($field)) {
            $this->activeField = $field;
        }

        return $this;
    }

    /**
     *
     * @param  boolean $bool
     * @param  string  $field
     * @return Section
     */
    public function sortable($bool = true, $field = '')
    {
        $this->sortable = $bool;

        if (!empty($field) || !$bool) {
            $this->sortField = $field;
        }

        return $this;
    }

    /**
     *
     * @param  boolean $bool
     * @return Section
     */
    public function destroyable($bool = true)
    {
        $this->destroyable = $bool;

        return $this;
    }

    /**
     *
     * @param  array $options
     * @return Section
     */
    public function routesOptions(array $options)
    {
        $this->routesOptions = $options;

        return $this;
    }

    /**
     *
     * @param  Request $request
     * @return void
     */
    public function filterCreateRequest(Request $request)
    {
        if (!empty($this->createOptions['filters'])) {
            $request->replace(
                Filters::filtering($request->all(), $this->createOptions['filters'])
            );
        }
    }

    /**
     *
     * @param  Request $request
     * @return void
     */
    public function filterEditRequest(Request $request)
    {
        if (!empty($this->editOptions['filters'])) {
            $request->replace(
                Filters::filtering($request->all(), $this->editOptions['filters'])
            );
        }
    }

    /**
     *
     * @param  Request $request
     * @return void
     */
    public function filterEditContentRequest(Request $request)
    {
        if (!empty($this->editContentOptions['filters'])) {
            $request->replace(
                Filters::filtering($request->all(), [
                    'content' => $this->editContentOptions['filters']
                ])
            );
        }
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getCreateRules(Request $request)
    {
        if (empty($this->createOptions['rules'])) {
            return [];
        }

        if ($this->createOptions['rules'] instanceof Closure) {
            return call_user_func($this->createOptions['rules'], $request, $this);
        }

        return $this->createOptions['rules'];
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getEditRules(Request $request)
    {
        if (empty($this->editOptions['rules'])) {
            return [];
        }

        if ($this->editOptions['rules'] instanceof Closure) {
            return call_user_func($this->editOptions['rules'], $request, $this);
        }

        return $this->editOptions['rules'];
    }

    /**
     *
     * @return array
     */
    public function getEditContentRules()
    {
        if (empty($this->editContentOptions['rules'])) {
            return [];
        }

        return [
            'content' => $this->editContentOptions['rules']
        ];
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getCreateMessages(Request $request)
    {
        if (empty($this->createOptions['messages'])) {
            return [];
        }

        if ($this->createOptions['messages'] instanceof Closure) {
            return call_user_func($this->createOptions['messages'], $request, $this);
        }

        return $this->createOptions['messages'];
    }

    /**
     *
     * @param  Request $request
     * @return void
     */
    public function getEditMessages(Request $request)
    {
        if (empty($this->editOptions['messages'])) {
            return [];
        }

        if ($this->editOptions['messages'] instanceof Closure) {
            return call_user_func($this->editOptions['messages'], $request, $this);
        }

        return $this->editOptions['messages'];
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getEditContentMessages(Request $request)
    {
        if (empty($this->editContentOptions['messages'])) {
            return [];
        }

        if ($this->editContentOptions['messages'] instanceof Closure) {
            $tmpMessages = call_user_func($this->editContentOptions['messages'], $request, $this);
        } else {
            $tmpMessages = $this->editContentOptions['messages'];
        }

        $messages = [];

        foreach ($tmpMessages as $rule => $message) {
            $messages["content.$rule"] = $message;
        }

        return $messages;
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getCreateData(Request $request)
    {
        if (empty($this->createOptions['data'])) {
            return [];
        }

        return call_user_func($this->createOptions['data'], $request, $this);
    }

    /**
     *
     * @param  Request $request
     * @return array
     */
    public function getEditData(Request $request)
    {
        if (empty($this->editOptions['data'])) {
            return [];
        }

        return call_user_func($this->editOptions['data'], $request, $this);
    }

    /**
     *
     * @return string|null
     */
    public function getContentField()
    {
        return $this->contentField;
    }

    /**
     *
     * @return string|null
     */
    public function getActiveField()
    {
        return $this->activeField;
    }

    /**
     *
     * @return string|null
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     *
     * @return array
     */
    public function getRoutesOptions()
    {
        return $this->routesOptions;
    }

    /**
     *
     * @param  string     $view
     * @return string
     */
    public function getViewName($view)
    {
        $slug = $this->getSlug();

        return view()->exists("crudivor::$slug.$view")
            ? "crudivor::$slug.$view"
            : "crudivor::$view";
    }

    /**
     *
     * @param  string  $id
	 * @param  array   $parameters
	 * @param  string  $domain
	 * @param  string  $locale
	 * @return string
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        $slug = $this->getSlug();

        $id = trans()->has("crudivor::$slug.$id", $locale)
            ? "crudivor::$slug.$id"
            : "crudivor::default.$id";

        return trans($id, $parameters, $domain, $locale);
    }
}
