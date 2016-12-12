<?php

// +----------------------------------------------------------------------
// | rootCMS 插件后台管理
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Addon\Keylink;

use Common\Model\Model;

class KeylinkModel extends Model {

    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('word', 'require', '关键字不能为空！', 1, 'regex', 3),
        array('word', '', '该关键字已经存在！', 0, 'unique', 1),
        array('url', 'require', '链接地址不能为空！', 1, 'regex', 3),
        array('url', 'url', '链接地址错误！', 1, 'regex', 3),
    );

    /**
     * 添加关键字
     * @param type $data
     * @return boolean
     */
    public function addKey($data) {
        if (empty($data)) {
            $this->error = '数据不能为空！';
            return false;
        }
        $data = $this->create($data, 1);
        if ($data) {
            $keylinkid = $this->add($data);
            if ($keylinkid) {
                $this->keylink_cache();
                return $keylinkid;
            } else {
                $this->error = '入库失败！';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 编辑
     * @param type $data
     * @return boolean
     */
    public function editKey($data) {
        if (empty($data) || empty($data['keylinkid'])) {
            $this->error = '数据不能为空！';
            return false;
        }
        $keylinkid = $data['keylinkid'];
        unset($data['keylinkid']);
        $data = $this->create($data, 2);
        if ($data) {
            if ($this->where(array('keylinkid' => $keylinkid))->save($data) !== false) {
                $this->keylink_cache();
                return true;
            } else {
                $this->error = '编辑失败！';
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 删除
     * @param type $keylinkid
     * @return boolean
     */
    public function deleteKey($keylinkid) {
        if (empty($keylinkid)) {
            $this->error = '请选择需要删除的信息！';
            return false;
        }
        $where = array();
        if (is_array($keylinkid)) {
            $where['keylinkid'] = array('IN', $keylinkid);
        } else {
            $where['keylinkid'] = $keylinkid;
        }

        if (empty($where)) {
            $this->error = '没有删除条件，无法删除！';
            return false;
        }

        if ($this->where($where)->delete() !== false) {
            $this->keylink_cache();
            return true;
        } else {
            return false;
        }
    }

    //更新生成缓存
    public function keylink_cache() {
        $data = $this->order(array('keylinkid' => 'DESC'))->select();
        if (empty($data)) {
            return false;
        }
        cache('keylink_cache', $data, 3600);
        return $data;
    }

}
