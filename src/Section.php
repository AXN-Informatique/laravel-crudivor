<?php

namespace Axn\Crudivor;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Axn\RequestFilters\Filters;

class Section
{
    /**
     * Slug permettant d'identifier la section.
     *
     * @var string|null
     */
    protected $slug = null;

    /**
     * Instance du modèle du paramètre à gérer dans la section.
     *
     * @var string|Model|null
     */
    protected $model = null;

    /**
     * La section permet-elle l'ajout d'enregistrement ?
     *
     * @var boolean
     */
    public $creatable = false;

    /**
     * La section permet-elle l'édition des enregistrements ?
     *
     * @var boolean
     */
    public $editable = false;

    /**
     * La section permet-elle l'édition à la volée des libellés des enregistrements ?
     *
     * @var boolean
     */
    public $contentEditable = false;

    /**
     * La section permet-elle d'activier/désactiver les enregistrements ?
     *
     * @var boolean
     */
    public $activatable = false;

    /**
     * La section permet-elle de trier manuellement les enregistrements ?
     *
     * @var boolean
     */
    public $sortable = false;

    /**
     * La section permet-elle de supprimer les enregistrements ?
     *
     * @var boolean
     */
    public $destroyable = false;

    /**
     * Options pour l'ajout d'un enregistrement.
     *
     * @var array
     */
    protected $createOptions = [];

    /**
     * Options pour l'édition d'un enregistrement.
     *
     * @var array
     */
    protected $editOptions = [];

    /**
     * Options pour l'édition à la volée du libellé d'un enregistrement.
     *
     * @var array
     */
    protected $editContentOptions = [];

    /**
     * Nom du champ (libellé) pouvant être édité à la volée dans la liste.
     *
     * @var string
     */
    protected $editContentField= '';

    /**
     * Nom du champ définissant l'enregistrement comme actif ou inactif.
     *
     * @var string
     */
    protected $activeField = '';

    /**
     * Nom du champ définissant l'ordre de l'enregistrement.
     *
     * @var string
     */
    protected $sortField = '';

    /**
     * Options des routes de la section.
     *
     * @var array
     */
    protected $routesOptions = [];

    /**
     * Modifie le slug de la section.
     *
     * @param  string $slug
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Retourne le slug de la section.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Modifie le modèle.
     *
     * @param  string|Model $model
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Instancie et retourne le modèle.
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
     * Indique si cette section permet d'ajouter des enregistrements.
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
     * Indique si cette section permet de modifier des enregistrements.
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
     * Alias à creatable() + editable().
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
     * Indique si la section permet l'édition à la volée du libellé d'un enregistrement.
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
     * Indique si la section permet d'activer/désactiver un enregistrement.
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
     * Indique si la section permet d'ordonner manuellement les enregistrements.
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
     * Indique si la section permet de supprimer des enregistrements.
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
     * Options à appliquer à l'ensemble des routes de la section.
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
     * Filtre/nettoie les paramètres de la requête pour la création d'un enregistrement.
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
     * Filtre/nettoie les paramètres de la requête pour la modification d'un enregistrement.
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
     * Filtre/nettoie les paramètres de la requête pour la modification à la volée
     * du libellé d'un enregistrement.
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
     * Retourne les règles de validation pour la création d'un enregistrement.
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
     * Retourne les règles de validation pour la création d'un enregistrement.
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
     * Retourne les règles de validation pour la modification d'un enregistrement.
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
     * Retourne les messages d'erreur de validation à la création d'un enregistrement.
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
     * Retourne les messages d'erreur de validation à la modification d'un enregistrement.
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
     * Retourne les messages d'erreur de validation à la modification à la volée
     * du libellé d'un enregistrement.
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
     * Retourne les données pour la création d'un enregistrement.
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
     * Retourne les données pour la modification d'un enregistrement.
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
     * Retourne le nom du champ pouvant être édité à la volée.
     *
     * @return string|null
     */
    public function getContentField()
    {
        return $this->contentField;
    }

    /**
     * Retourne le nom du champ définissant l'état actif/inactif des enregistrements.
     *
     * @return string|null
     */
    public function getActiveField()
    {
        return $this->activeField;
    }

    /**
     * Retourne le nom du champ définissant l'ordre les enregistrements.
     *
     * @return string|null
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     * Retourne les options des routes.
     *
     * @return array
     */
    public function getRoutesOptions()
    {
        return $this->routesOptions;
    }

    /**
     * Retourne le nom complet d'une vue.
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
     * Retourne le texte correspondant à la clé.
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
