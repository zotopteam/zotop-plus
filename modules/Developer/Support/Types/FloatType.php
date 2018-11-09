<?php
namespace Modules\Developer\Support\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType as FloatTypeBase;

class FloatType extends FloatTypeBase
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::FLOAT;
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

        $fieldDeclaration['precision'] = ( ! isset($fieldDeclaration['precision']) || empty($fieldDeclaration['precision']))
            ? 10 : $fieldDeclaration['precision'];
        $fieldDeclaration['scale'] = ( ! isset($fieldDeclaration['scale']) || empty($fieldDeclaration['scale']))
            ? 0 : $fieldDeclaration['scale'];

        return 'FLOAT(' . $fieldDeclaration['precision'] . ', ' . $fieldDeclaration['scale'] . ')'.$unsigned;
    }    
}
