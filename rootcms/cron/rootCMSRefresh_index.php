<?php

// +----------------------------------------------------------------------
// | rootCMS 计划任务 - 刷新首页
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace CronScript;

//指定内容模块生成，没有指定默认使用GROUP_NAME
defined('GROUP_MODULE') or define('GROUP_MODULE', 'Content');

class rootCMSRefresh_index {

    //任务主体
    public function run($cronId) {
        CMS()->Html->index();
    }

}
