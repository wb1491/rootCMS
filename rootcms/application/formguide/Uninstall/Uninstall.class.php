<?php

// +----------------------------------------------------------------------
// | rootCMS 评论卸载脚本
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Formguide\Uninstall;

use Libs\System\UninstallBase;

class Uninstall extends UninstallBase {

    public function run() {
        //取得模型
        $model =  model('Content/Model')->where(array("type" => 3))->select();
        if ($model) {
            foreach ($model as $r) {
                if ($r['modelid'] && $r['type'] == 3) {
                    //删除模型数据
                     model('Content/Model')->where(array('modelid' => $r['modelid']))->delete();
                    //删除数据表
                     model('Content/Model')->DeleteTable($r['tablename']);
                }
            }
        }
        return true;
    }

}
