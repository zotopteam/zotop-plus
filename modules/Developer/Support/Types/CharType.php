<?php
namespace Modules\Developer\Support\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
/**
 * Type that maps an SQL CHAR to a PHP string.
 *
 * @since 2.0
 */
class CharType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'char';
    }
}
