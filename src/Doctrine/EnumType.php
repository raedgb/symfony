<?php

namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (!isset($fieldDeclaration['columnDefinition'])) {
            throw new \InvalidArgumentException('The "columnDefinition" key is missing from field declaration.');
        }

        $columnDefinition = $fieldDeclaration['columnDefinition'];

        if (strpos($columnDefinition, 'enum(') === false) {
            throw new \InvalidArgumentException('The provided column definition does not contain an ENUM type.');
        }

        preg_match('/^enum\((.*)\)$/', $columnDefinition, $matches);

        if (!isset($matches[1])) {
            throw new \InvalidArgumentException('Could not extract ENUM values from column definition.');
        }

        $values = array_map(function ($value) {
            return "'" . trim($value, "'") . "'";
        }, explode(',', $matches[1]));

        return sprintf('ENUM(%s)', implode(',', $values));
    }

    public function getName()
    {
        return self::ENUM;
    }
}