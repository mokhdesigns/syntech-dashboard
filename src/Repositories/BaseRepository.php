<?php

namespace Syntech\Dashboard\Repositories;

use App\Traits\ImageUpload;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
  class BaseRepository
{
    use ImageUpload;
    /**
     * Eloquent model instance.
     */
    protected $model;

    protected $dataTable;

    protected $request;

    protected $rules;

    /*
    * set model name
    */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /*
    * set Request class
    */

    public function modelName()
    {
        return $this->model;
    }



    /**
     * load default class dependencies.
     *
     * @param Model $model Illuminate\Database\Eloquent\Model
     */

    /**
     * get all the items collection from database table using model.
     *
     * @return Collection of items.
     */
    public function get()
    {
        return   $this->getDatatable();
        // return $this->model->get();
    }
    /**
     * get collection of items in paginate format.
     *
     * @return Collection of items.
     */
    public function paginate(Request $request)
    {
        return $this->model->paginate($request->input('limit', 10));
    }
    /**
     * create new record in database.
     *
     * @param Request $request Illuminate\Http\Request
     * @return saved model object with data.
     */
    public function store(Request $request)
    {

        (isset($this->rules) && !empty($this->rules)) ?  $this->validate($request, $this->rules) : '' ;

        $data = $this->setMergePayLoad($request);

        $item = $this->model;

        // remove custom fields from request array

        $data = array_filter($data, function($key) {

            return !in_array($key, ['custom_names', 'custom_values']);

        }, ARRAY_FILTER_USE_KEY);

        // remove sub employees from request array

        $data = array_filter($data, function($key) {

            return !in_array($key, ['sub_employees']);

        }, ARRAY_FILTER_USE_KEY);


        $item->fill($data);


        $item->save();

        session()->flash('success', 'تم اضافه البيانات بنجاح');

         return $item;
    }
    /**
     * update existing item.
     *
     * @param  Integer $id integer item primary key.
     * @param Request $request Illuminate\Http\Request
     * @return send updated item object.
     */
    public function update(Request $request, $id)
    {
        (isset($this->rules) && !empty($this->rules)) ?  $this->validate($request, $this->rules) : '' ;

        $data = $this->setMergePayLoad($request);

        $item = $this->model->findOrFail($id);

        $item->fill($data);

        $item->save();

        session()->flash('success', 'تم تعديل البيانات بنجاح');

        return $item;
    }
    /**
     * get requested item and send back.
     *
     * @param  Integer $id: integer primary key value.
     * @return send requested item data.
     */
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }


        /**
     * get requested item and send back.
     *
     * @param  Integer $id: integer primary key value.
     * @return send requested item data.
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Delete item by primary key id.
     *
     * @param  Integer $id integer of primary key id.
     * @return boolean
     */
    public function delete($id)
    {


        if($this->getModel() == 'Category' && $id == 1) {

           return false;

        } else {

            return $this->model->destroy($id);
        }

       //return $this->model->destroy($id);
    }
    /**
     * set data for saving
     *
     * @param  Request $request Illuminate\Http\Request
     * @return array of data.
     */
    protected function setDataPayload(Request $request)
    {
        return $request->all();
    }


    protected function setMergePayLoad(Request $request)
    {
        $data = $this->setDataPayload($request);

        if($request->image){

            $data['image'] = $this->ImageUpload($request->image);

        }



        if($request->gallery && is_array($request->gallery) && ! is_null($request->gallery)){

            $data['gallery'] = $this->GallryUpload($request->gallery);

        }

        if($request->avatar){

            $data['avatar'] = $this->ImageUpload($request->avatar);

        }

        if($request->password){

            $data['password'] = bcrypt($request->password);

        }else{

            unset($data['password']);
        }


                // remove custom fields from request array

                $data = array_filter($data, function($key) {

                    return !in_array($key, ['custom_names', 'custom_values']);

                }, ARRAY_FILTER_USE_KEY);

                // remove sub employees from request array

                $data = array_filter($data, function($key) {

                    return !in_array($key, ['sub_employees']);

                }, ARRAY_FILTER_USE_KEY);


        return $data;

    }



    public function getDatatable() {

        $routeSegment = request()->segment(2);

        $dataTable = $this->dataTable;

        $model = class_basename($this->model);



        $model = Str::lower($model);

        // if(class_exists($dataTable::class)) {

        //     if ($routeSegment == 'dashboard') {

        //         if (view()->exists(''.$model.'.index')){

        //             return $this->dataTable->render(''.$model.'.index');

        //         } else {

        //             return 'view not exists please create: admin.'.$model.'.index' ;
        //         }

        //     }

        //  } else {

        //     return 'DataTable not exists please create: App\DataTables\\'.ucfirst($model).'DataTable';

        //  }

    }


    public function countries() {

        return  Country::all();

    }

    public function operators() {

        return  Operator::all();

    }

    public function categories() {

        return  Category::all();

    }


    public function types() {

        return  Type::all();

    }

    public function getModel() {

        // get model name

       return $model = class_basename($this->model);

    }

    public function getRequest() {


       return $request =  $this->request;

    }


    public function changeStatus($id) {

        $item = $this->model->findOrFail($id);

        if($item->status == 0) {

            $item->status = 1;

        } else {

            $item->status = 0;
        }

        $item->save();

        return $item;
    }

    public function validate($request, $rules) {

        // $validate = $request->validate($rules);

        $validate = \Validator::make($request->all(), $rules);

        $validate->validate();


        if($validate->fails()) {

            return $validate->messages();

        } else {

            return $validate;
        }

        return $validate;

    }


}
