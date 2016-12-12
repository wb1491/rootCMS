<?php

// +----------------------------------------------------------------------
// | rootCMS 内容模型
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\content\model;

use think\Model;

class Content extends Model {

    //静态成品变量 保存全局实例
    static $_instance = NULL;
    
    //当前模型id
    public $modelid = 0;
        
    /**
     * 取得内容模型实例
     * @param type $modelid 模型ID
     * @return obj
     */
    static public function getInstance($modelid) {
        //内容模型缓存
        $modelCache = sys_cache("Model");
        if (empty($modelCache[$modelid])) {
            return false;
        }
        $tableName = $modelCache[$modelid]['tablename'];
        return new Content($tableName,$modelid);
    }
    
    public function __construct($name="",$modelid=0) {
        $data = [];
        if(!empty($name) && !is_array($name)){
            $this->name = $name;
            if(empty($modelid)){
                $modelid = getModelIdByName($name);
            }
        }elseif(is_array($name)|| is_object($name)){
            $data = $name;
            if( isset($data['catid']) && $data['catid']>0 ){
                $modelid = getCategory($data['catid'],'modelid');
            }
            $this->data = $data;
        }
        if(!empty($modelid)){
            $this->modelid = $modelid;
        }
        
        if(!empty($this->modelid)){
            //内容模型缓存
            $modelCache = sys_cache("Model");
            if (empty($modelCache[$modelid])) {
                return false;
            }
            //根据模型id重新赋值name
            $this->name = $modelCache[$modelid]['tablename'];
        }
        $this->connection = config("database");
        $this->table = config("database.prefix").$this->name;
        $this->class = get_class($this);
        $this->db()->name($this->name);
        $this->db()->table($this->table);
        $this->pk = $this->getPk($this->name);
    }
    
    /**
     * 进行关联查询
     * @access public
     * @param mixed $isrel 关联名称
     * @return Model
     */
    public function relation($isrel=false) {
        if($isrel){
            //关联关系
            if( is_null($this->relation) ){
                $this->relation = new ContentData($this->name,  $this->modelid);
            }
            parent::relation($this->getRelationName());
        }
        return $this;
    }
    
    /**
     * 获取关联定义名称
     * @param type $tableName 表名
     * @return type
     */
    public function getRelationName($tableName = '') {
        if (empty($tableName)) {
            $tableName = $this->name;
        }
        return $tableName . '_data';
    }

    /**
     * 查询当前模型的关联数据
     * @access public
     * @param string|array $relations 关联名
     * @return $this
     */
    public function relationQuery($relations){
        if (is_string($relations)) {
            $relations = explode(',', $relations);
        }
        $this->relation(true);
        foreach ($relations as $relation) {
            $this->data[$relation] = $this->relation->getRelation($this->data['id']);
        }
        return $this;
    }
    
    /**
     * 对通过连表查询的数据进行合并处理
     * @param type $data
     */
    public function dataMerger(&$data) {
        $relationName = $this->getRelationName();
        $datafb = $data[$relationName];
        unset($datafb['id'], $data[$relationName]);
        if (is_array($datafb)) {
            $data = array_merge($data, $datafb);
        }
        return $data;
    }
    
    /**
     * 创建数据对象 但不保存到数据库
     * @access public
     * @param mixed $data 创建数据
     * @param string $type 状态
     * @param string $name 关联名称
     * @return mixed
     */
    public static function create($data = '', $type = '', $name = true) {
        //是否使用关联
        if (empty($this->options['link'])) {
            return parent::create($data, $type);
        }
        // 如果没有传值默认取POST数据
        if (empty($data)) {
            $data = $_POST;
        } elseif (is_object($data)) {
            $data = get_object_vars($data);
        }
        // 验证数据
        if (empty($data) || !is_array($data)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }
        //关联定义
        $relation = $this->_link;
        //验证规则
        $_validate = $this->_validate;
        //自动完成
        $_auto = $this->_auto;
        if (!empty($relation)) {
            // 遍历关联定义
            foreach ($relation as $key => $val) {
                // 操作制定关联类型
                $mappingName = $val['mapping_name'] ? $val['mapping_name'] : $key; // 映射名称
                if (empty($name) || true === $name || $mappingName == $name || (is_array($name) && in_array($mappingName, $name))) {
                    //关联类名
                    $mappingClass = !empty($val['class_name']) ? $val['class_name'] : $key;
                    //关联类型
                    $mappingType = !empty($val['mapping_type']) ? $val['mapping_type'] : $val;
                    switch ($mappingType) {
                        case self::HAS_ONE:
                            //是否有副表数据
                            $isLinkData = false;
                            //数据
                            if (isset($data[$mappingName])) {
                                $sideTablesData = $data[$mappingName];
                                unset($data[$mappingName]);
                                $isLinkData = true;
                            }
                            //自动验证
                            if (isset($_validate[$mappingName])) {
                                $_validateSideTables = $_validate[$mappingName];
                                unset($_validate[$mappingName], $this->_validate[$mappingName]);
                            }
                            //自动完成
                            if (isset($_auto[$mappingName])) {
                                $_autoSideTables = $_auto[$mappingName];
                                unset($_auto[$mappingName], $this->_auto[$mappingName]);
                            }
                            //进行主表create
                            if ($type == 1) {
                                $data = parent::create($data, $type);
                            } else {
                                if (empty($data)) {
                                    $data = true;
                                    if (empty($sideTablesData)) {
                                        $this->error = L('_DATA_TYPE_INVALID_');
                                        return false;
                                    }
                                } else {
                                    $data = parent::create($data, $type);
                                }
                                //存在主键副表也自动加上
                                if (!empty($data[$this->getPk()])) {
                                    $sideTablesData[$this->getPk()] = $data[$this->getPk()];
                                }
                            }
                            //下面进行的是副表验证操作，这里需要检查特殊情况，例如没有开启关联的，其实不用进行下面
                            if (empty($this->options['link']) || empty($isLinkData)) {
                                return $data;
                            }
                            //关闭表单验证
                            C('TOKEN_ON', false);
                            //不管成功或者失败，清空_validate和_auto
                            $this->_validate = $this->_auto = array();
                            if ($data) {
                                if (empty($sideTablesData)) {
                                    return $data;
                                } else {
                                    $sideTablesData = M($mappingClass)->validate($_validateSideTables)->auto($_autoSideTables)->create($sideTablesData, $type);
                                    if ($sideTablesData) {
                                        if (is_array($data)) {
                                            return array_merge($data, array($mappingName => $sideTablesData));
                                        } else {
                                            return array($mappingName => $sideTablesData);
                                        }
                                    } else {
                                        $this->error = M($mappingClass)->getError();
                                        return false;
                                    }
                                }
                            } else {
                                return false;
                            }
                            break;
                        default :
                            return parent::create($data, $type);
                            break;
                    }
                }
            }
        }
        return parent::create($data, $type);
    }

    /**
     * 是否终极栏目
     * @param type $catid
     * @return boolean
     */
    public function isUltimate($catid) {
        if (getCategory($catid, 'child')) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 信息锁定
     * @param type $catid 栏目ID
     * @param type $id 信息ID
     * @param type $userid 用户名ID
     * @param type $username 用户名
     * @return type
     */
    public function locking($catid, $id, $userid = 0) {
        $db = db("Locking");
        $time = time();
        //锁定有效时间
        $Lock_the_effective_time = 300;
        if (empty($userid)) {
            $userid = \app\admin\service\User::getInstance()->id;
        }
        $where = array();
        $where['catid'] = array("EQ", $catid);
        $where['id'] = array("EQ", $id);
        $where['locktime'] = array("EGT", $time - $Lock_the_effective_time);
        $info = $db->where($where)->find();
        if ($info && $info['userid'] != \app\admin\service\User::getInstance()->id) {
            $this->error = 'o(︶︿︶)o 唉，该信息已经被用户【<font color=\"red\">' . $info['username'] . '</font>】锁定~请稍后在修改！';
            return false;
        }
        //删除失效的
        $where = array();
        $where['locktime'] = array("LT", $time - $Lock_the_effective_time);
        $db->where($where)->delete();
        return true;
    }

    /**
     * 内容模型处理类生成
     */
    public static function classGenerate() {
        //字段类型存放目录
        $fields_path = APP_PATH . 'content/fields/';
        //内置字段类型列表
        $fields = include $fields_path . 'fields.inc.php';
        $fields = $fields? : array();
        //更新内容模型数据处理相关类
        $classtypes = array('form', 'input', 'output', 'update', 'delete');
        //缓存生成路径
        $cachemodepath = RUNTIME_PATH;
        foreach ($classtypes as $classtype) {
            $content_cache_data = file_get_contents($fields_path . "content_$classtype.php");
            $cache_data = '';
            //循环字段列表，把各个字段的 form.inc.php 文件合并到 缓存 content_form.class.php 文件
            foreach ($fields as $field => $fieldvalue) {
                //检查文件是否存在
                if (file_exists($fields_path . $field . DIRECTORY_SEPARATOR . $classtype . '.inc.php')) {
                    //读取文件，$classtype.inc.php 
                    $ca = file_get_contents($fields_path . $field . DIRECTORY_SEPARATOR . $classtype . '.inc.php');
                    $cache_data .= str_replace(array("<?php", "?>"), "", $ca);
                }
            }
            $content_cache_data = str_replace('##{字段处理函数}##', $cache_data, $content_cache_data);
            //写入缓存
            file_put_contents($cachemodepath . 'content_' . $classtype . '.php', $content_cache_data);
            //设置权限
            chmod($cachemodepath . 'content_' . $classtype . '.php', 0777);
            unset($cache_data);
        }
    }
}
