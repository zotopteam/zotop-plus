<?php
namespace Modules\Developer\Support\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use DB;

class TinyintType extends IntegerType
{
	/**
	 * Get the type name
	 * 
	 * @return string
	 */
    public function getName()
    {
        return 'tinyint';
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
        $unsigned   = ! empty($fieldDeclaration['unsigned']) ? ' UNSIGNED' : '';
        $increments = ! empty($fieldDeclaration['autoincrement']) ? ' AUTO_INCREMENT' : '';

        return 'TINYINT(3)'.$unsigned.$increments;
    }

    /**
     * 如果字段为 tinyint(1) ，则为boolean字段
     * 
     * @param  string $table  表名（包含前缀）
     * @param  string $column 字段名称
     * @return boolean
     */
    public function isBoolean($table, $column)
    {
        $boolean = false;

        $result = DB::select("show columns from ".$table." where Field = '".$column."'");

        // tinyint类型且长度等于1时为boolean字段
        if ($result && stripos($result[0]->Type, 'tinyint(1)') !== false) {
            $boolean = true;
        }

        return $boolean;
    }
}
