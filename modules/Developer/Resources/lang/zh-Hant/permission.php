<?php
return [
    'title'        => 'Permission許可權助手',
    'description'  => '協助創建和校驗模塊許可權設定檔',
    'key'          => '節點鍵名',
    'val'          => '節點名稱',
    'name'         => '顯示名稱',
    'scan'         => '掃描並生成',
    'scan.confirm' => '警告：掃描並生成將覆蓋已有的許可權配寘',
    'scan.success' => '掃描並生成操作成功，原有許可權配寘備份成功',
    'scan.empty'   => '沒有在當前模塊路由中掃描到許可權節點，請先在路由配寘中加入[allow:：0.test]中介軟體',
    'missing'      => '路由中有以下節點，但是許可權設定檔中沒有，請在許可權設定檔中添加',
    'question'     => '許可權配寘中有該節點，但是路由中並沒有，如果已經不再使用，請從許可權設定檔中删除',
];