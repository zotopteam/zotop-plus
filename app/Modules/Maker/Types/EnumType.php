<?php

namespace App\Modules\Maker\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use DB;

class EnumType extends Type
{
    /**
     * Get the type name
     * 
     * @return string
     */
    public function getName()
    {
        return 'enum';
    }

    /**
     * 获取enum字段的允许数据
     * 
     * @param  string $table  表名（包含前缀）
     * @param  string $column 字段名称
     * @return string
     */
    public function getAllowed($table, $column)
    {
        $allowed = '';

        $result = DB::select("show columns from " . $table . " where Field = '" . $column . "'");

        if ($result) {
            //  enum('Y','N')
            $allowed = $result[0]->Type;
            $allowed = substr($allowed, 5, -1);
            $allowed = array_map(function ($a) {
                return trim($a, "'");
            }, explode(',', $allowed));
            $allowed = implode(",", $allowed);
        }

        // 获取数据表允许的值
        return $allowed;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $name = $platform->getName();

        if ($name == 'mysql') {
            return $this->getMysqlPlatformSQLDeclaration($fieldDeclaration);
        }

        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type for the MySQL Platform.
     *
     * @param array $fieldDeclaration The field declaration.
     *
     * @return string
     */
    protected function getMysqlPlatformSQLDeclaration(array $fieldDeclaration)
    {
        $allowed = array_map(function ($val) {
            return "'" . $val . "'";
        }, $fieldDeclaration['allowed']);

        return "ENUM(" . implode(", ", $allowed) . ")";
    }
}
