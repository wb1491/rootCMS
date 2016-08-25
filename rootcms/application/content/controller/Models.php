<?php

// +----------------------------------------------------------------------
// | rootCMS 内容模型管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Content\Controller;

use Common\Controller\AdminBase;

class ModelsController extends AdminBase {

    //显示模型列表
    public function index() {
        $data =  model("Content/Model")->where(array("type" => 0))->select();
        $this->assign("data", $data);
        $this->display();
    }

    //添加模型
    public function add() {
        if (IS_POST) {
            $data = input('post.');
            if (empty($data)) {
                $this->error('提交数据不能为空！');
            }
            if ( model("Content/Model")->addModel($data)) {
                $this->success("添加模型成功！");
            } else {
                $error =  model("Content/Model")->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            $this->display();
        }
    }

    //编辑模型
    public function edit() {
        if (IS_POST) {
            $data = input('post.');
            if (empty($data)) {
                $this->error('提交数据不能为空！');
            }
            if ( model("Content/Model")->editModel($data)) {
                $this->success('模型修改成功！',  url('index'));
            } else {
                $error =  model("Content/Model")->getError();
                $this->error($error ? $error : '修改失败！');
            }
        } else {
            $modelid = input('get.modelid', 0, 'intval');
            $data =  model("Content/Model")->where(array("modelid" => $modelid))->find();
            $this->assign("data", $data);
            $this->display();
        }
    }

    //删除模型
    public function delete() {
        $modelid = input('get.modelid', 0, 'intval');
        //检查该模型是否已经被使用
        $count = M("Category")->where(array("modelid" => $modelid))->count();
        if ($count) {
            $this->error("该模型已经在使用中，请删除栏目后再进行删除！");
        }
        //这里可以根据缓存获取表名
        $modeldata =  model("Content/Model")->where(array("modelid" => $modelid))->find();
        if (!$modeldata) {
            $this->error("要删除的模型不存在！");
        }
        if ( model("Content/Model")->deleteModel($modelid)) {
            $this->success("删除成功！",  url("index"));
        } else {
            $this->error("删除失败！");
        }
    }

    //检查表是否已经存在
    public function public_check_tablename() {
        $tablename = input('get.tablename', '', 'trim');
        $count =  model("Content/Model")->where(array("tablename" => $tablename))->count();
        if ($count == 0) {
            $this->success('表名不存在！');
        } else {
            $this->error('表名已经存在！');
        }
    }

    //模型的禁用与启用
    public function disabled() {
        $modelid = input('get.modelid', 0, 'intval');
        $disabled = input('get.disabled') ? 0 : 1;
        $status =  model("Content/Model")->where(array('modelid' => $modelid))->save(array('disabled' => $disabled));
        if ($status !== false) {
            $this->success("操作成功！");
        } else {
            $this->error("操作失败！");
        }
    }

    //模型导入
    public function import() {
        if (IS_POST) {
            if (empty($_FILES['file'])) {
                $this->error("请选择上传文件！");
            }
            $filename = $_FILES['file']['tmp_name'];
            if (strtolower(substr($_FILES['file']['name'], -3, 3)) != 'txt') {
                $this->error("上传的文件格式有误！");
            }
            //读取文件
            $data = file_get_contents($filename);
            //删除
            @unlink($filename);
            //模型名称
            $name = input('post.name', NULL, 'trim');
            //模型表键名
            $tablename = input('post.tablename', NULL, 'trim');
            //导入
            $status =  model("Content/Model")->import($data, $tablename, $name);
            if ($status) {
                $this->success("模型导入成功，请及时更新缓存！");
            } else {
                $this->error( model("Content/Model")->getError() ?  model("Content/Model")->getError() : '模型导入失败！');
            }
        } else {
            $this->display();
        }
    }

    //模型导出
    public function export() {
        //需要导出的模型ID
        $modelid = input('get.modelid', 0, 'intval');
        if (empty($modelid)) {
            $this->error('请指定需要导出的模型！');
        }
        config('SHOW_PAGE_TRACE',false);
        //导出模型
        $status =  model("Content/Model")->export($modelid);
        if ($status) {
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=spf_model_" . $modelid . '.txt');
            echo $status;
        } else {
            $this->error( model("Content/Model")->getError() ?  model("Content/Model")->getError() : '模型导出失败！');
        }
    }

}
