@extends('app')

@section('content')
    <h1>{{ $section->trans(!empty($record) ? 'edit_title' : 'list_title') }}</h1>

    <div class="row">
        <div class="col-xs-12 {!! $section->creatable || !empty($record) ? 'col-md-8' : 'col-md-12' !!}">

            @if (count($list) < 1)
                <div class="well">
                    <p>{{ $section->trans('empty') }}</p>
                </div>
            @else
                <ul class="list-group {!! $section->sortable && empty($record) ? 'sortable' : '' !!}">

                    @foreach ($list as $item)
                        <li class="list-group-item clearfix"
                            data-sort="{{ $item->id }}"
                            {!! !empty($record) && $record->id == $item->id ? 'style="background:gray"' : '' !!}>

                            <div class="pull-left">
                                @if ($section->sortable && empty($record))
                                    <i class="fa fa-arrows-v"></i>&nbsp;
                                @endif

                                @if ($section->contentEditable && empty($record))
                                    <span contenteditable="true" data-key="{{ $item->id }}">{{ $item->libelle }}</span>
                                @else
                                    <span>{{ $item->libelle }}</span>
                                @endif
                            </div>

                            @if (empty($record))
                                <div class="pull-right">
                                    @if ($section->activatable)
                                        @if ($item->actif)
                                            {!! Form::open(['url' => route("crudivor.$slug.disable", ['id' => $item->id]), 'class' => 'form-inline', 'style' => 'display:inline']) !!}
                                                {!! Form::button('<i class="fa fa-check"></i><span class="hidden-xs hidden-sm"> Actif</span>', [
                                                    'type'        => 'submit',
                                                    'class'       => 'btn btn-success btn-xs',
                                                    'title'       => $section->trans('disable_tooltip', ['name' => $item->libelle]),
                                                    'data-toggle' => 'tooltip'
                                                ]) !!}
                                            {!! Form::close() !!}
                                        @else
                                            {!! Form::open(['url' => route("crudivor.$slug.enable", ['id' => $item->id]), 'class' => 'form-inline', 'style' => 'display:inline']) !!}
                                                {!! Form::button('<i class="fa fa-close"></i><span class="hidden-xs hidden-sm"> Inactif</span>', [
                                                    'type'        => 'submit',
                                                    'class'       => 'btn btn-warning btn-xs',
                                                    'title'       => $section->trans('enable_tooltip', ['name' => $item->libelle]),
                                                    'data-toggle' => 'tooltip'
                                                ]) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    @endif

                                    @if ($section->editable)
                                        <a href="{!! route('crudivor.'.$slug.'.edit', ['id' => $item->id] + (!$section->sortable ? ['page' => Input::get('page')] : [])) !!}"
                                           class="btn btn-info btn-xs"
                                           title="{{ $section->trans('edit_tooltip', ['name' => $item->libelle]) }}"
                                           data-toggle="tooltip">

                                            <i class="fa fa-pencil"></i><span class="hidden-xs hidden-sm"> Modifier</span>
                                        </a>
                                    @endif

                                    @if ($section->destroyable)
                                        {!! Form::open(['route' => ["crudivor.$slug.destroy", $item->id], 'method' => 'DELETE', 'class' => 'form-inline', 'style' => 'display:inline']) !!}
                                            {!! Form::button('<i class="fa fa-trash"></i><span class="hidden-xs hidden-sm"> Supprimer</span>', [
                                                'type'         => 'submit',
                                                'class'        => 'btn btn-danger btn-xs',
                                                'title'        => $section->trans('destroy_tooltip', ['name' => $item->libelle]),
                                                'data-confirm' => $section->trans('destroy_confirm', ['name' => $item->libelle]),
                                                'data-toggle'  => 'tooltip'
                                            ]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach

                </ul>

                @if (!$section->sortable)
                    <div class="pull-right">
                        {!! $list->render() !!}
                    </div>
                @endif
            @endif
        </div>

        @if (!empty($record))

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $section->trans('edit_form_title') }}</h3>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['route' => ["crudivor.$slug.update", $record->id], 'method' => 'PUT']) !!}
                            @include($formView)

                            <div class="form-group">
                                {!! Form::button('<i class="fa fa-check"></i> Mettre à jour', ['type' => 'submit', 'class' => 'btn btn-info']) !!}
                                <a href="{!! route('crudivor.'.$slug.'.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{ $section->trans('back_to_list') }}</a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        @elseif ($section->creatable)

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $section->trans('create_form_title') }}</h3>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['route' => "crudivor.$slug.store"]) !!}
                            @include($formView)

                            <div class="form-group">
                                {!! Form::button('<i class="fa fa-plus"></i> Ajouter', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        @endif

    </div>
@endsection
