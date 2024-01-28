<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelPlaceholder;

class ControllerNamePlaceholder extends BaseController
{
    protected $model_placeholder;
    function __construct()
    {
        $this->model_placeholder = new ModelPlaceholder();
    }
    // 新增
    /**
     * @api {post} /ControllerNamePlaceholder/create 新增
     * @apiDescription 新增
     * @apiName Create
     * @apiGroup ControllerNamePlaceholder
     * @apiVersion 1.0.0
     * @apiSampleRequest /ControllerNamePlaceholder/create
     * 
     api_doc_placeholder
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'model_placeholder_id' =>[
                'label' => 'model_placeholder_id',
                'rules' => 'required|is_unique[model_placeholder.model_placeholder_id]',
                'errors' => [
                    'required' => '{field} is required.',
                    'is_unique' => '{field} is unique.'
                ]
            ]
        ];
        if (!$this->validate($rules)) {
            return $this->errorResponse($this->validator->getErrors(), 400);
        }
        try {
            $this->model_placeholder->insert($data);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    
    // 修改
    /**
     * @api {post} /ControllerNamePlaceholder/update 修改
     * @apiDescription 修改
     * @apiName Update
     * @apiGroup ControllerNamePlaceholder
     * @apiVersion 1.0.0
     * @apiSampleRequest /ControllerNamePlaceholder/update
     * 
     api_doc_placeholder
     */
    public function update(){
        $data = $this->request->getJSON(true);
        $rules = [
            // model_id 必須存在
            'model_placeholder_id' =>[
                'label' => 'model_placeholder_id',
                'rules' => 'required|exists[model_placeholder.model_placeholder_id]',
                'errors' => [
                    'required' => '{field} is required.',
                    'exists' => '{field} not exists.'
                ]
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->errorResponse($this->validator->getErrors(), 400);
        }
        try {
            $this->model_placeholder->update($data['model_placeholder_id'], $data);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    // 刪除
    /**
     * @api {post} /ControllerNamePlaceholder/delete 刪除
     * @apiDescription 刪除
     * @apiName Delete
     * @apiGroup ControllerNamePlaceholder
     * @apiVersion 1.0.0
     * @apiSampleRequest /ControllerNamePlaceholder/delete
     * 
     * @apiBody {String} model_placeholder_id model_placeholder_id
     * 
     * @apiParamExample {json} Request-Example:
     *     {
     *       "model_placeholder_id": ""
     *     }
     */
    public function delete(){
        $data = $this->request->getJSON(true);
        $rules = [
            
            'model_placeholder_id' =>[
                'label' => 'model_placeholder_id',
                'rules' => 'required|exists[model_placeholder.model_placeholder_id]',
                'errors' => [
                    'required' => '{field} is required.',
                    'exists' => '{field} is not exists.'
                ]
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->errorResponse($this->validator->getErrors(), 400);
        }
        try {
            $this->model_placeholder->delete($data['model_placeholder_id']);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    // 查詢指定單筆
    /**
     * @api {get} /ControllerNamePlaceholder/get_once/:model_placeholder_id 查詢指定單筆
     * @apiDescription 查詢指定單筆
     * @apiName GetOnce
     * @apiGroup ControllerNamePlaceholder
     * @apiVersion 1.0.0
     * @apiSampleRequest /ControllerNamePlaceholder/get_once/:model_placeholder_id
     * 
     * @apiParam {String} model_placeholder_id model_placeholder_id
     */
    public function get_once($model_placeholder_id){
        $data = $this->model_placeholder->find($model_placeholder_id);
        if ($data === null) {
            return $this->errorResponse('model_placeholder_id not exists.', 400);
        }
        return $this->successResponse($data);
    }
    // 通用查詢列表
    /**
     * @api {post} /ControllerNamePlaceholder/get_list 通用查詢列表
     * @apiDescription 通用查詢列表
     * @apiName GetList
     * @apiGroup ControllerNamePlaceholder
     * @apiVersion 1.0.0
     * @apiSampleRequest /ControllerNamePlaceholder/get_list
     * 
     * @apiBody {Number} [page] page
     * @apiBody {Number} [items] items
     * @apiBody {Object} [whereCols] 查詢條件where  ex:[{"model_placeholder_id":"1"}]
     * @apiBody {Object} [whereInCols] 查詢條件whereIn ex:[{"model_placeholder_id":["1","2"]}]
     * @apiBody {Object} [likeCols] 查詢條件like   ex:[{"model_placeholder_id":"1"}]
     * @apiBody {Object} [rangeCols] 查詢條件range ex:[{"model_placeholder_id":["開始值","結束值"]}]
     * @apiBody {Object} [sortCols] 排序    ex:[{"model_placeholder_id":"desc"}]
     * @apiBody {Object} [selectCols] 查詢欄位 ex:["model_placeholder_id,created_at,updated_at,deleted_at"]
     * 
     */
    public function get_list(){
        $data = $this->request->getJSON(true);
        $page = (int) ($data['page'] ?? 1);
        $items = (int) ($data['items'] ?? 20);
        try {
            $listdata = $this->model_placeholder->paginateArray($data, $page, $items);
            return $this->successResponse($listdata);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}
