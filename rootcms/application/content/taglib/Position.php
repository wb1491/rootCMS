<?php

// +----------------------------------------------------------------------
// | rootCMS 推荐位解析标签
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace app\content\taglib;

class Position {

    /**
     * 推荐位数据获取
     * 参数名	 是否必须	 默认值	 说明
     * posid	 是	 null	 推荐位ID
     * catid	 否	 null	 调用栏目ID
     * thumb	 否	 0	 是否仅必须缩略图
     * order	 否	 null	 排序类型
     * num	 是	 null	 数据调用数量
     * @param type $data 
     */
    public function position($data) {
        //缓存时间
        $cache = isset($data['cache']) ? (int) $data['cache']:0;
        $cacheID = to_guid_string($data);
        if ($cache && $return = cache($cacheID)) {
            return $return;
        }
        $posid = (int) $data['posid'];
        if ($posid < 1) {
            return false;
        }
        $catid = isset($data['cache']) ? (int) $data['catid']:0;
        $thumb = isset($data['thumb']) ? $data['thumb'] : 0;
        $order = empty($data['order']) ? ["listorder" => "DESC", "id" => "DESC"] : $data['order'];
        $num = (int) $data['num'];

        $db = db('PositionData');
        $Position = sys_cache('Position');
        if ($num == 0) {
            $num = $Position[$posid]['maxnum'];
        }
        $where = [];
        $where['posid']= ["EQ", $posid];
        if ($thumb) {
            $where['thumb'] = ["EQ", 1];
        }
        if ($catid > 0) {
            $cat = getCategory($catid);
            if ($cat) {
                //是否包含子栏目
                if ($cat['child']) {
                    $where['catid'] = [ "IN", $cat['arrchildid']];
                } else {
                    $where['catid'] = [ "EQ", $catid];
                }
            }
        }
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            //$where['_string'] = $data['where'];
            $tmparray = parseWhere($data['where']);
            foreach($tmparray[0] as $v){
                $where[$v[0]] = [$v[1],$v[2]];
            }
            foreach($tmparray[1] as $v){
                $where[$v[0]] = [[$v[1],$v[2]],'or'];
            }
        }
        $data = $db->where($where)->order($order)->limit($num)->select();
        foreach ($data as $k => $v) {
            unset($data[$k]['data']);
            $data[$k] = array_merge($data[$k],unserialize($v['data']));
            $tb = \app\content\model\Content::getInstance($v['modelid']);
            $tmp = $tb::get($v['id']);
            if(!empty($tmp) && isset($tmp['url'])){
                $data[$k]['url'] = $tmp['url'];
            }
        }
        //结果进行缓存
        if ($cache) {
            cache($cacheID, $data, $cache);
        }
        return $data;
    }

}
