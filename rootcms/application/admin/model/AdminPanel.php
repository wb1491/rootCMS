<?php

// +----------------------------------------------------------------------
// | rootCMS 常用菜单
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\admin\Model;

use app\common\model\Model;

class AdminPanel extends Model {

    /**
     * 添加常用菜单
     * @param type $data
     * @return boolean
     */
    public function addPanel($data) {
        //删除旧的
        $this->where(array("userid" => \app\admin\service\User::getInstance()->id))->delete();
        if (empty($data)) {
            return true;
        }
        config('TOKEN_ON', false);
        foreach ($data as $k => $rs) {
            $data[$k] = $this->create($rs, 1);
        }

        return $this->addAll($data) !== false ? true : false;
    }

    /**
     * 返回某个用户的全部常用菜单
     * @param type $userid 用户ID
     * @return type
     */
    public function getAllPanel($userid) {
        return $this->where(array('userid' => $userid))->select();
    }

    /**
     * 检查该菜单是否已经添加过
     * @param type $mid 菜单ID
     * @return boolean
     */
    public function isExist($mid) {
        return $this->where(array('mid' => $mid, "userid" => \app\admin\service\User::getInstance()->id))->count();
    }

}
