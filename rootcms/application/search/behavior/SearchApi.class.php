<?php

// +----------------------------------------------------------------------
// | rootCMS 搜索行为Api
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rootcms.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------

namespace Search\Behavior;

class SearchApi {

    private $config = array();

    public function __construct() {
        $this->config = cache('Search_config');
    }

    public function content_add_end($param) {
        $modelid = getCategory($param['catid'], 'modelid');
        if (empty($modelid)) {
            return false;
        }
        if (!in_array($modelid, $this->config['modelid'])) {
            return false;
        }
        return  model('Search/Search')->search_api($param['id'], $param, $modelid, 'add');
    }

    public function content_edit_end($param) {
        $modelid = getCategory($param['catid'], 'modelid');
        if (empty($modelid)) {
            return false;
        }
        if (!in_array($modelid, $this->config['modelid'])) {
            return false;
        }
        return  model('Search/Search')->search_api($param['id'], $param, $modelid, 'updata');
    }

    public function content_check_end($param) {
        $modelid = getCategory($param['catid'], 'modelid');
        if (empty($modelid)) {
            return false;
        }
        if (!in_array($modelid, $this->config['modelid'])) {
            return false;
        }
        if ($param['status'] == 99) {
            $action = 'updata';
        } else {
            $action = 'delete';
        }
        return  model('Search/Search')->search_api($param['id'], $param, $modelid, $action);
    }

    public function content_delete_end($param) {
        $modelid = getCategory($param['catid'], 'modelid');
        if (empty($modelid)) {
            return false;
        }
        if (!in_array($modelid, $this->config['modelid'])) {
            return false;
        }
        return  model('Search/Search')->search_api($param['id'], $param, $modelid, 'delete');
    }

}
