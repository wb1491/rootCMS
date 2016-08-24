<?php 
 return [
    'cookie_expire' => '3600',
    'cookie_domain' => '',
    'cookie_path' => '/',
    'session_prefix' => '',
    'session_options' => 
    [ 'domain' => '',],
    'show_error_msg' => '1',
    'error_message' => '您浏览的页面暂时发生了错误！请稍后再试～',
    'error_page' => '',

    'url_case_insensitive' => false,
    'url_model' => 0,
    'url_pathinfo_depr' => '/',
    'url_html_suffix' => '.html',

    'token_on' => true,
    'token_name' => '__hash__',
    'token_type' => 'md5',
     
    'page_listrows' => '20',
    'var_page' => 'page',
    'page_template' => '<span class="all">共有{recordcount}条信息</span><span class="pageindex">{pageindex}/{pagecount}</span>{first}{prev}{liststart}{list}{listend}{next}{last}',
];