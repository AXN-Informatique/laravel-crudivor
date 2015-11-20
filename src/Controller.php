<?php

namespace Axn\Crudivor;

use Exception;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Axn\LaravelNotifier\Contract as Notifier;
use DB;

class Controller extends BaseController
{
    use ValidatesRequests;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Notifier
     */
    protected $notifier;

    /**
     *
     * @param  Repository $repository
     * @param  Notifier   $notifier
     * @return void
     */
    public function __construct(Repository $repository, Notifier $notifier)
    {
        $this->repository = $repository;

        $this->notifier = $notifier;
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        $section = $this->repository->getCurrent();

        return view($section->getViewName('list'), [
            'section'  => $section,
            'slug'     => $section->getSlug(),
            'list'     => $this->getList($section),
            'formView' => $section->getViewName('form')
        ]);
    }

    /**
     *
     * @param  mixed   $id
     * @return Response
     */
    public function edit($id)
    {
        $section = $this->repository->getCurrent();

        return view($section->getViewName('list'), [
            'section'  => $section,
            'slug'     => $section->getSlug(),
            'record'   => $section->getModel()->findOrFail($id),
            'list'     => $this->getList($section),
            'formView' => $section->getViewName('form')
        ]);
    }

    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $section = $this->repository->getCurrent();

        $section->filterCreateRequest($request);
        $this->validate($request, $section->getCreateRules($request), $section->getCreateMessages($request));

        $data = $section->getCreateData($request);
        if ($section->sortable) {
            $data[$section->getSortField()] = $section->getModel()->max($section->getSortField()) + 1;
        }
        $section->getModel()->create($data);

        $this->notifier->success($section->trans('store_success'));

        return back();
    }

    /**
     *
     * @param  mixed   $id
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $section = $this->repository->getCurrent();

        $section->filterEditRequest($request);
        $this->validate($request, $section->getEditRules($request), $section->getEditMessages($request));

        $section->getModel()->whereId($id)->update($section->getEditData($request));

        $this->notifier->success($section->trans('update_success'));

        return back();
    }

    /**
     *
     * @param  mixed   $id
     * @param  Request $request
     * @return Response
     */
    public function updateContent($id, Request $request)
    {
        $section = $this->repository->getCurrent();

        $section->filterEditContentRequest($request);
        $this->validate($request, $section->getEditContentRules(), $section->getEditContentMessages($request));

        $section->getModel()->whereId($id)->update([
            $section->getContentField() => $request->input('content')
        ]);

        return response()->json([
            'message' => $section->trans('update_success'),
            'content' => $request->input('content')
        ]);
    }

    /**
     *
     * @param  mixed   $id
     * @return Response
     */
    public function enable($id)
    {
        $section = $this->repository->getCurrent();

        $section->getModel()->whereId($id)->update([
            $section->getActiveField() => 1
        ]);

        $this->notifier->success($section->trans('enable_success'));

        return back();
    }

    /**
     *
     * @param  mixed   $id
     * @return Response
     */
    public function disable($id)
    {
        $section = $this->repository->getCurrent();

        $section->getModel()->whereId($id)->update([
            $section->getActiveField() => 0
        ]);

        $this->notifier->success($section->trans('disable_success'));

        return back();
    }

    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function sort(Request $request)
    {
        $section = $this->repository->getCurrent();

        $sort = array_map('intval', $request->input('sort', []));

        DB::transaction(function() use ($section, $sort) {
            foreach ($sort as $value => $id) {
                $section->getModel()->whereId($id)->update([
                    $section->getSortField() => $value + 1
                ]);
            }
        });

        return response()->json([
            'message' => $section->trans('sort_success')
        ]);
    }

    /**
     *
     * @param  mixed   $id
     * @return Response
     */
    public function destroy($id)
    {
        $section = $this->repository->getCurrent();

        try {
            $section->getModel()->destroy($id);
        }
        catch (Exception $e) {
            return back()->withErrors($section->trans('destroy_failure'));
        }

        $this->notifier->success($section->trans('destroy_success'));

        return back();
    }

    /**
     *
     * @param  Section $section
     * @return Collection|LengthAwarePaginator
     */
    protected function getList(Section $section)
    {
        $query = $section->getModel()->newQuery();

        if ($section->getSortField()) {
            $query->orderBy($section->getSortField());
        }

        if ($section->sortable) {
            return $query->get();
        }

        return $query->paginate();
    }
}
