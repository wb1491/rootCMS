<?php

/**
 * 自动获取标题作为关键字 插件
 * Some rights reserved：abc3210.com
 * Contact email:admin@abc3210.com
 */

namespace Addon\GetKeyword;

use \Addons\Util\Addon;

class GetKeywordAddon extends Addon {

    //插件信息
    public $info = array(
        'name' => 'GetKeyword',
        'title' => '标题自动作为关键字',
        'description' => '假如关键词 TAG没有输入 将自动获取标题作为关键词TAG 增强SEO<br><span style="color:red;font-weight:bolder;">懒人之福，懒蛋必备的站长工具</span>',
        'status' => 1,
        'author' => '随风',
        'version' => '1.0',
        'has_adminlist' => 0,
        'sign' => '95F60BD60E1F8A0602EF2744C6DF8770',
    );

    //安装
    public function install() {
        //检查行为是否有添加
        $Behavior =  model('Common/Behavior');
        config('TOKEN_ON', false);
        if ($Behavior->where(array('name' => 'content_add_begin'))->count() == 0) {
            $Behavior->addBehavior(array(
                'name' => 'content_add_begin',
                'title' => '内容添加前行为调用',
                'type' => 1,
            ));
        }
        return true;
    }

    //卸载
    public function uninstall() {
        return true;
    }

    //实现行为 content_add_begin
    //$param 是行为传递过来的参数
    public function content_add_begin(&$data) {
        return $this->getKeyword($data);
    }

    protected function getKeyword(&$data) {
        if (empty($data)) {
            return false;
        }
        $config = $this->getAddonConfig(); //插件配置
        if (!$config['kaiguan']) {
            return false;
        }
        $tags = $data['tags'];
        $keywords = $data['keywords'];
        $fenci = $data['title'];
        if ($config['kaiguan'] == 1) {
            if ($config['jiekou'] == 1) {
                $shu = $this->segment($fenci);
                $shu = implode(' ', $shu);
            } elseif ($config['jiekou'] == 2) {
                $shu = $this->discuzSegment($fenci);
                $shu = implode(' ', $shu);
            } else {
                $shu = $this->vapsegment($fenci);
                $shu = implode(' ', $shu);
            }
        }
        if (empty($tags)) {
            $data['tags'] = $shu;
        }
        if (empty($keywords)) {
            $data['keywords'] = $shu;
        }
        return true;
    }

    /**
     * 矩网智慧在线中文分词
     * */
    public function vapsegment($word) {
        $url = "http://open.vapsec.com/segment/get_word?word={$word}&token=95F60BD60E1F8A0602EF2744C6DF8770&format=string";
        $file_contents = file_get_contents($url);  //获取远程文件
        $wordArr = explode('_', $file_contents);
        return $wordArr;
    }

    /**
     * DZ在线中文分词
     * @param $title string 进行分词的标题
     * @param $content string 进行分词的内容
     * @param $encode string API返回的数据编码
     * @return  array 得到的关键词数组
     */
    public function discuzSegment($title = '', $content = '', $encode = 'utf-8') {
        if (empty($title)) {
            return false;
        }
        //标题处理
        $title = rawurlencode(strip_tags(trim($title)));
        //内容处理
        $content = str_replace(' ', '', strip_tags($content));
        //在线分词服务有长度限制
        if (strlen($content) > 2400) {
            $content = mb_substr($content, 0, 2300, $encode);
        }
        //进行URL编码
        $content = rawurlencode($content);
        //API地址
        $url = 'http://keyword.discuz.com/related_kw.html?title=' . $title . '&content=' . $content . '&ics=' . $encode . '&ocs=' . $encode;
        //将XML中的数据,读取到数组对象中
        $xml_array = simplexml_load_file($url);
        $result = $xml_array->keyword->result;
        //分词数据
        $data = array();
        foreach ($result->item as $key => $value) {
            array_push($data, (string) $value->kw);
        }
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 使用内置本地分词处理进行分词
     * @param type $data
     * @return boolean
     */
    public function segment($data1) {
        if (empty($data1)) {
            return false;
        }
        import('Segment', $this->addonPath);
        $Segment = new \Segment();
        $fulltext_data = $Segment->get_keyword($Segment->split_result($data1));
        $data1 = explode(' ', $fulltext_data);
        if (count($data1) > 0) {
            return $data1;
        } else {
            return false;
        }
    }

}
