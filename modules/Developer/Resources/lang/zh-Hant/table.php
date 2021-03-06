<?php
return [
    'title'                       => 'Table資料表',
    'description'                 => '模塊資料表管理，資料表首碼需要為模塊名',
    'index'                       => '資料表清單',
    'create'                      => '創建',
    'edit'                        => '修改',
    'manage'                      => '管理',
    'rename'                      => '重命名',
    'drop'                        => '删除',
    'exists'                      => '資料表:0已經存在',
    'name'                        => '資料表名稱',
    'name.help'                   => '如果模塊名稱為test，則資料表名稱需要為test或者test_***，不含資料表首碼',
    'name.error'                  => '資料表名稱必須等於：0或者以：0_開頭，不能包含特殊字元或者以下劃線結尾',
    'columns'                     => '資料表欄位',
    'columns.count'               => '欄位數量：:0',
    'indexes'                     => '資料表索引',
    'indexes.count'               => '索引數量：:0',
    'migration.create'            => '生成創建錶遷移',
    'migration.create.confirm'    => '你確認要生成創建錶遷移嗎？',
    'migration.override'          => '覆蓋創建錶遷移',
    'migration.override.confirm'  => '你確認要覆蓋創建錶遷移嗎？覆蓋將删除該錶已有的全部遷移檔案',
    'migration.update'            => '生成修改錶遷移',
    'migration.update.confirm'    => '你確認要生成修改錶遷移嗎？',
    'migration.drop'              => '生成删除錶遷移',
    'migration.drop.confirm'      => '你確認要生成删除錶遷移嗎？',
    'migration.created'           => '遷移檔案創建成功，請修改或者執行遷移',
    'model.create'                => '生成模型檔案',
    'model.override'              => '覆蓋模型檔案',
    'column.name'                 => '名字',
    'column.type'                 => '類型',
    'column.length'               => '長度/值',
    'column.nullable'             => '空',
    'column.index'                => '索引',
    'column.unsigned'             => '無符號',
    'column.increments'           => '自增',
    'column.default'              => '預設值',
    'column.comment'              => '注釋',
    'column.add'                  => '新欄位',
    'column.add_timestamps'       => '時間戳',
    'column.add_softdeletes'      => '軟删除',
    'column.exists'               => '欄位已經存在',
    'column.validator.columnname' => '長度2-20，允許小寫英文字母、數位和底線，並且僅能字母開頭，不以底線結尾',
    'column.validator.uniquename' => '標識已經存在，請使用其它標識',
    'index.name'                  => '名稱',
    'index.type'                  => '類型',
    'index.columns'               => '欄位',
    'index.primary'               => '主鍵',
    'index.index'                 => '索引',
    'index.unique'                => '唯一',
    'index.unselect'              => '請選擇要索引的欄位',
    'index.exists'                => '索引已經存在',
];