<?php

// +----------------------------------------------------------------------
// | rootCMS 评论卸载脚本
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 wb1491 All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace Comments\Uninstall;

use System\UninstallBase;

class Uninstall extends UninstallBase {

    public function run() {
        $db = M('CommentsSetting');
        $info = $db->find();
        if (!empty($info)) {
            for ($i = 1; $i <= $info['stbsum']; $i++) {
                $db->execute('DROP TABLE IF EXISTS `' . config("DB_PREFIX") . 'comments_data_' . $i . '`;');
            }
        }
        return true;
    }

}
