<?php
namespace Modules\Developer\Support\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Modules\Developer\Support\Types\MediumintType;
/**
 * Type that maps an SQL MediumintType to a PHP string.
 *
 * @since 2.0
 */
class MediumintegerType extends MediumintType
{

}
