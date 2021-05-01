<?php

namespace Zotop\Modules\Maker\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TimestampType extends Type
{
    /**
     * Get the type name
     * 
     * @return string
     */
    public function getName()
    {
        return 'timestamp';
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array                                     $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform         The currently used database platform.
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
        if (isset($fieldDeclaration['notnull']) && $fieldDeclaration['notnull'] == true) {
            return 'TIMESTAMP';
        }

        return 'TIMESTAMP NULL';
    }
}
