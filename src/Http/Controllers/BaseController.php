<?php

namespace Syntech\Dashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Traits\Validate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected $repository;

    protected $request;

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;

    }


    public function index()
    {
        if(request()->input('changeStatus')){

            return $this->repository->changeStatus(request()->input('changeStatus'));

        }

        $data = $this->repository->get();

        // return $this->view . '.index';

        // return view($this->view . '.index');

        isset($this->compact) ? $compact = $this->compact : $compact = null;

        return $this->dataTable->render($this->view . '.index', compact('compact'));

    }

    public function create()
    {
        isset($this->related) ? $related = $this->related : $related = null;

            return view($this->view . '.create', compact('related'));


        return view($this->view . '.create');
    }

    public function show($id)
    {
        $data = $this->repository->find($id);

        isset($this->related) ? $related = $this->related : $related = null;

        return view($this->view . '.show', compact('related', 'data'));
    }

    public function store()
    {

      $this->repository->store($this->request);

      return redirect()->route($this->route . '.index');

    }

    public function edit($id)
    {
        $data = $this->repository->find($id);

        isset($this->related) ? $related = $this->related : $related = null;

        return view($this->view . '.edit', compact('related', 'data'));

        return   view($this->view . '.edit', compact('data'));
    }

    public function update($id)
    {
        $this->repository->update($this->request, $id);

        return redirect()->route($this->route . '.index');

    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route($this->route . '.index');

    }


    public function multiDelete(Request $request)
    {
        $this->repository->multiDelete($request);

        return redirect()->route($this->route . '.index');
    }


    public function block($id)
    {
        $this->repository->block($id);

        return true;
    }


}
