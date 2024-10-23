<?php

namespace Schema;

use JsonSchema\Validator;

trait SchemaGenerator
{
    private array $typeMap = [
        'int' => 'integer',
        'bool' => 'boolean',
        'mixed' => 'any',
        'float' => 'number',
    ];

    public function getJsonSchema(): string
    {
        return @json_encode($this->getSchema()) ?: '';
    }

    public function getSchema(): \stdClass
    {
        // Získání reflexe třídy, ke které je traita připojena
        $reflectionClass = new \ReflectionClass($this);

        // Seznam vlastností
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $schema = [
            'type' => 'object'
        ];
        $propertiesItems = [];
        $requiredItems = [];

        foreach ($properties as $property) {
            // Kontrola, zda je vlastnost readonly
            // Získání typu vlastnosti
            $type = $property->getType();

            if ($type) {
                $typeName = $type->getName();

                // Kontrola, zda je typ skalární (int, string, bool, float)
                if (in_array($typeName, ['int', 'string', 'bool', 'float'], true)) {
                    // Pokud je typ skalární, vložíme ho přímo
                    $propertiesItems[$property->getName()] = (object)[
                        "type" => $this->typeMap[$typeName] ?? $typeName
                    ];
                } elseif ($typeName === 'array') {
                    $docComment = $property->getDocComment();
                    if ($docComment) {
                        // Najdeme typy pomocí regulárního výrazu z PHPDoc
                        if (preg_match('/@var\s+array<([^>]+)?>/', $docComment, $matches)) {
                            $valueType = $matches[1]; // Typ hodnoty

                            // Zpracujeme typy hodnot v poli
                            if (in_array($valueType, ['int', 'string', 'bool', 'float'], true)) {
                                $propertiesItems[$property->getName()] = (object)[
                                    'type' => 'array',
                                    'items' => (object)[
                                        'type' => $valueType,
                                    ]
                                ];
                            } elseif (class_exists($valueType) && is_subclass_of($valueType, Schema::class)) {
                                // Pokud hodnoty v poli jsou objekty implementující Schema
                                $object = new $valueType();
                                $propertiesItems[$property->getName()] = (object)[
                                    'type' => 'array',
                                    'items' => $object->getSchema()
                                ];
                            }
                        }
                    }
                } else {
                    // Pokud je typ objekt, zkontrolujeme, zda implementuje rozhraní Schema
                    if (is_subclass_of($typeName, Schema::class)) {
                        // Vytvoříme instanci objektu a zavoláme getSchema
                        $object = new $typeName;
                        $propertiesItems[$property->getName()] = $object->getSchema();
                    } else {
                        // Pokud objekt neimplementuje rozhraní Schema, vložíme jen název typu
                        $propertiesItems[$property->getName()] = (object)[
                            'type' => $typeName
                        ];
                    }
                }
            }

            if ($property->isReadOnly()) {
                $requiredItems[] = $property->getName();
            }
        }

        $schema['properties'] = (object) $propertiesItems;
        if (!empty($requiredItems)) {
            $schema['required'] = $requiredItems;
        }

        return (object) $schema;
    }

    public function validate(\stdClass &$object): bool
    {
        $validator = new Validator;
        return $validator->validate(
            $request,
            $this->getSchema(),
            \JsonSchema\Constraints\Constraint::CHECK_MODE_COERCE_TYPES
        ) === 0;
    }
}
