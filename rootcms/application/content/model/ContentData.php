<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\content\model;

use think\Model;
/**
 * Description of ContentData
 *
 * @author wb
 */
class ContentData extends Model {
    
    //静态成品变量 保存全局实例
    static $_instance = NULL;
    
    //当前模型id
    public $modelname = "";
    
    /**
     * 取得内容模型实例
     * @param type $modelname 模型名称
     * @return obj
     */
    static public function getInstance($modelname) {
        if (is_null(self::$_instance[$modelname]) || !isset(self::$_instance[$modelname])) {
            self::$_instance[$modelname] = new ContentData(["modelname"=>$modelname]);
        }
        return self::$_instance[$modelname];
    }
    /**
     * 创建内容数据模型
     * @param type $data
     */
    public function __construct($data = array()) {
        if(isset($data['modelname'])&&!empty($data['modelname'])){
            $this->name = $data['modelname'];
            //设置模型名称
            $this->modelname = $data['modelname'];
        }
        if(isset($data['foreignKey'])&&!empty($data['foreignKey'])){
            $this->foreignKey = $data['foreignKey'];
        }
        
        if(isset($data['foreignKey'])&&!empty($data['foreignKey'])){
            $this->foreignKey = $data['foreignKey'];
        }
        
        parent::__construct($data);
        
    }
    
}
