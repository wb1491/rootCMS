<?php

// +----------------------------------------------------------------------
// | rootCMS 内容模型
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wb23 <wb1491@gmail.com>
// +----------------------------------------------------------------------

namespace app\content\model;

use app\common\model\Model;

class FlightInfoOut extends Model {

    //定义真实表名称
    protected $trueTableName = "v_web_dyn_arr_flt";
    //自动验证 array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array();
    //自动完成 array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array();
    //连接航班动态的oracle数据
    protected $connection = 'airinfo_db';

}
