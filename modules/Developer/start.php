<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.navbar',function($navbar){
    
    $navbar['developer'] = [
        'text'   => trans('developer::module.title'),
        'href'   => route('developer.index'),
        'active' => Route::is('developer.*')
    ];

    return $navbar;
});

/**
 * 扩展模块管理
 */
\Filter::listen('global.start',function($navbar){
    
    $navbar['developer'] = [
        'text' => trans('developer::module.develop'),
        'href' => route('developer.module.index'),
        'icon' => 'fa fa-puzzle-piece bg-warning text-white',
        'tips' => trans('developer::module.develop.description'),
    ];
    
    return $navbar;
},80);


/**
 * 一键刷新
 */
\Action::listen('system.refresh', function(){
    \Artisan::call('module:publish');
    \Artisan::call('theme:publish');
});


function getFun($str) {

    //preg_match_all('#public\s+function\s+([a-zA-Z0-9_]+)\s*\([^\)]*\)\s*{([^{}]+({[^}]+})*[^}]+)}#', $file, $matches);
    
    //dd($matches);

    $fun = array();
    $of = 0;
    while($sta = stripos($str, 'function', $of)){ 
        $ob    = stripos($str, '(', $sta+=7);
        $name  = substr($str, $sta+1, $ob-$sta-1);
        $cb    = stripos($str, ')',$ob);
        $param = substr($str, $ob+1, $cb-$ob-1);
        $cnt   = 1;
        $start = strpos($str, '{', $cb);
        $ss    = $start;
        while ($cnt){
            $s = strpos($str, '{', $ss+1);
            $e = strpos($str, '}', $ss+1);
            if($s < $e  and $s > 0){$cnt++; $ss = $s;} else {$cnt--; $ss = $e;}
        }
        $end  = $ss;
        $body = substr($str, $start+1, $end - $start-1);
        $of   = $ob;
        $fun[]= array(trim($name), trim($param), trim($body));
    }
    
    return $fun;    
}