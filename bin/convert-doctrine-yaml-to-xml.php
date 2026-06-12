<?php declare(strict_types=1);

use DOMDocument;
use DOMElement;
use Symfony\Component\Yaml\Yaml;

require dirname(__DIR__) . '/vendor/autoload.php';

const DOCTRINE_NS = 'http://doctrine-project.org/schemas/orm/doctrine-mapping';
const GEDMO_NS = 'http://gediminasm.org/schemas/orm/doctrine-extensions-mapping';

$inputDir = dirname(__DIR__) . '/config/doctrine';
$outputDir = dirname(__DIR__) . '/config/doctrine-xml';

if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
    throw new RuntimeException(sprintf('Unable to create output directory "%s".', $outputDir));
}

foreach (glob($inputDir . '/*.orm.yml') ?: [] as $filePath) {
    $mappingDocument = Yaml::parseFile($filePath);
    if (!is_array($mappingDocument) || $mappingDocument === []) {
        throw new RuntimeException(sprintf('Invalid mapping file: %s', $filePath));
    }

    $className = array_key_first($mappingDocument);
    $mapping = $mappingDocument[$className];
    if (!is_string($className) || !is_array($mapping)) {
        throw new RuntimeException(sprintf('Unsupported mapping structure in: %s', $filePath));
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    $root = $dom->createElementNS(DOCTRINE_NS, 'doctrine-mapping');
    $root->setAttribute('xmlns:gedmo', GEDMO_NS);
    $dom->appendChild($root);

    $entityType = (string) ($mapping['type'] ?? 'entity');
    $entityTag = match ($entityType) {
        'embeddable' => 'embeddable',
        'mappedSuperclass' => 'mapped-superclass',
        default => 'entity',
    };

    $entity = $dom->createElementNS(DOCTRINE_NS, $entityTag);
    $entity->setAttribute('name', $className);

    if (isset($mapping['repositoryClass'])) {
        $entity->setAttribute('repository-class', (string) $mapping['repositoryClass']);
    }

    if (isset($mapping['table'])) {
        $entity->setAttribute('table', (string) $mapping['table']);
    }

    foreach (($mapping['id'] ?? []) as $fieldName => $fieldMapping) {
        appendId($dom, $entity, (string) $fieldName, (array) $fieldMapping);
    }

    foreach (($mapping['fields'] ?? []) as $fieldName => $fieldMapping) {
        appendField($dom, $entity, (string) $fieldName, (array) $fieldMapping);
    }

    foreach (($mapping['embedded'] ?? []) as $fieldName => $embeddedMapping) {
        appendEmbedded($dom, $entity, (string) $fieldName, (array) $embeddedMapping);
    }

    appendAssociations($dom, $entity, 'many-to-one', $mapping['manyToOne'] ?? []);
    appendAssociations($dom, $entity, 'one-to-many', $mapping['oneToMany'] ?? []);
    appendAssociations($dom, $entity, 'many-to-many', $mapping['manyToMany'] ?? []);
    appendAssociations($dom, $entity, 'one-to-one', $mapping['oneToOne'] ?? []);

    $root->appendChild($entity);

    $targetFile = $outputDir . '/' . basename($filePath, '.yml') . '.xml';
    $dom->save($targetFile);
    echo sprintf("Converted %s -> %s\n", basename($filePath), basename($targetFile));
}

function appendId(DOMDocument $dom, DOMElement $parent, string $fieldName, array $fieldMapping): void
{
    $id = $dom->createElementNS(DOCTRINE_NS, 'id');
    $id->setAttribute('name', $fieldName);

    foreach (['type', 'column', 'length'] as $attribute) {
        if (isset($fieldMapping[$attribute])) {
            $id->setAttribute(toXmlAttributeName($attribute), (string) $fieldMapping[$attribute]);
        }
    }

    if (isset($fieldMapping['generator']['strategy'])) {
        $generator = $dom->createElementNS(DOCTRINE_NS, 'generator');
        $generator->setAttribute('strategy', (string) $fieldMapping['generator']['strategy']);
        $id->appendChild($generator);
    }

    appendOptions($dom, $id, $fieldMapping['options'] ?? null);
    $parent->appendChild($id);
}

function appendField(DOMDocument $dom, DOMElement $parent, string $fieldName, array $fieldMapping): void
{
    $field = $dom->createElementNS(DOCTRINE_NS, 'field');
    $field->setAttribute('name', $fieldName);

    foreach (['type', 'column', 'length', 'precision', 'scale', 'enumType', 'columnDefinition'] as $attribute) {
        if (isset($fieldMapping[$attribute])) {
            $field->setAttribute(toXmlAttributeName($attribute), (string) $fieldMapping[$attribute]);
        }
    }

    foreach (['unique', 'nullable', 'index', 'insertable', 'updatable', 'version'] as $booleanAttribute) {
        if (isset($fieldMapping[$booleanAttribute])) {
            $field->setAttribute(toXmlAttributeName($booleanAttribute), toXmlBool((bool) $fieldMapping[$booleanAttribute]));
        }
    }

    if (isset($fieldMapping['options'])) {
        appendOptions($dom, $field, $fieldMapping['options']);
    }

    if (isset($fieldMapping['gedmo']['timestampable']) && is_array($fieldMapping['gedmo']['timestampable'])) {
        $timestampable = $dom->createElementNS(GEDMO_NS, 'gedmo:timestampable');
        foreach (['on', 'field', 'value'] as $attribute) {
            if (isset($fieldMapping['gedmo']['timestampable'][$attribute])) {
                $timestampable->setAttribute($attribute, (string) $fieldMapping['gedmo']['timestampable'][$attribute]);
            }
        }
        $field->appendChild($timestampable);
    }

    $parent->appendChild($field);
}

function appendEmbedded(DOMDocument $dom, DOMElement $parent, string $fieldName, array $embeddedMapping): void
{
    $embedded = $dom->createElementNS(DOCTRINE_NS, 'embedded');
    $embedded->setAttribute('name', $fieldName);

    if (isset($embeddedMapping['class'])) {
        $embedded->setAttribute('class', (string) $embeddedMapping['class']);
    }

    if (isset($embeddedMapping['columnPrefix'])) {
        $embedded->setAttribute('column-prefix', (string) $embeddedMapping['columnPrefix']);
    }

    if (isset($embeddedMapping['useColumnPrefix'])) {
        $embedded->setAttribute('use-column-prefix', toXmlBool((bool) $embeddedMapping['useColumnPrefix']));
    }

    $parent->appendChild($embedded);
}

function appendAssociations(DOMDocument $dom, DOMElement $parent, string $tagName, array $associations): void
{
    foreach ($associations as $fieldName => $associationMapping) {
        $associationMapping = (array) $associationMapping;
        $association = $dom->createElementNS(DOCTRINE_NS, $tagName);
        $association->setAttribute('field', (string) $fieldName);

        foreach (['targetEntity', 'mappedBy', 'inversedBy', 'indexBy', 'fetch'] as $attribute) {
            if (isset($associationMapping[$attribute])) {
                $association->setAttribute(toXmlAttributeName($attribute), (string) $associationMapping[$attribute]);
            }
        }

        if (isset($associationMapping['orphanRemoval'])) {
            $association->setAttribute('orphan-removal', toXmlBool((bool) $associationMapping['orphanRemoval']));
        }

        appendCascade($dom, $association, $associationMapping['cascade'] ?? []);
        appendJoinColumnOrColumns($dom, $association, $associationMapping['joinColumn'] ?? null);
        appendJoinTable($dom, $association, $associationMapping['joinTable'] ?? null);
        appendOrderBy($dom, $association, $associationMapping['orderBy'] ?? null);

        $parent->appendChild($association);
    }
}

function appendCascade(DOMDocument $dom, DOMElement $parent, mixed $cascadeConfig): void
{
    if (!is_array($cascadeConfig) || $cascadeConfig === []) {
        return;
    }

    $cascade = $dom->createElementNS(DOCTRINE_NS, 'cascade');
    foreach ($cascadeConfig as $operation) {
        $cascade->appendChild($dom->createElementNS(DOCTRINE_NS, 'cascade-' . str_replace('_', '-', (string) $operation)));
    }

    $parent->appendChild($cascade);
}

function appendJoinColumnOrColumns(DOMDocument $dom, DOMElement $parent, mixed $joinColumnConfig): void
{
    if (!is_array($joinColumnConfig) || $joinColumnConfig === []) {
        return;
    }

    $isList = array_is_list($joinColumnConfig);
    if ($isList) {
        $joinColumns = $dom->createElementNS(DOCTRINE_NS, 'join-columns');
        foreach ($joinColumnConfig as $columnMapping) {
            $joinColumns->appendChild(createJoinColumnElement($dom, (array) $columnMapping));
        }
        $parent->appendChild($joinColumns);

        return;
    }

    $parent->appendChild(createJoinColumnElement($dom, $joinColumnConfig));
}

function appendJoinTable(DOMDocument $dom, DOMElement $parent, mixed $joinTableConfig): void
{
    if (!is_array($joinTableConfig) || $joinTableConfig === []) {
        return;
    }

    $joinTable = $dom->createElementNS(DOCTRINE_NS, 'join-table');
    if (isset($joinTableConfig['name'])) {
        $joinTable->setAttribute('name', (string) $joinTableConfig['name']);
    }

    foreach (['joinColumns' => 'join-columns', 'inverseJoinColumns' => 'inverse-join-columns'] as $yamlKey => $xmlTag) {
        if (!isset($joinTableConfig[$yamlKey]) || !is_array($joinTableConfig[$yamlKey])) {
            continue;
        }

        $columns = $dom->createElementNS(DOCTRINE_NS, $xmlTag);
        foreach ($joinTableConfig[$yamlKey] as $columnName => $columnConfig) {
            $columnConfig = (array) $columnConfig;
            if (!isset($columnConfig['name'])) {
                $columnConfig['name'] = (string) $columnName;
            }
            $columns->appendChild(createJoinColumnElement($dom, $columnConfig));
        }
        $joinTable->appendChild($columns);
    }

    $parent->appendChild($joinTable);
}

function appendOrderBy(DOMDocument $dom, DOMElement $parent, mixed $orderByConfig): void
{
    if (!is_array($orderByConfig) || $orderByConfig === []) {
        return;
    }

    $orderBy = $dom->createElementNS(DOCTRINE_NS, 'order-by');
    foreach ($orderByConfig as $field => $direction) {
        $orderByField = $dom->createElementNS(DOCTRINE_NS, 'order-by-field');
        $orderByField->setAttribute('name', (string) $field);
        $orderByField->setAttribute('direction', strtoupper((string) $direction));
        $orderBy->appendChild($orderByField);
    }

    $parent->appendChild($orderBy);
}

function appendOptions(DOMDocument $dom, DOMElement $parent, mixed $options): void
{
    if (!is_array($options) || $options === []) {
        return;
    }

    $optionsElement = $dom->createElementNS(DOCTRINE_NS, 'options');
    foreach ($options as $name => $value) {
        $option = $dom->createElementNS(DOCTRINE_NS, 'option');
        $option->setAttribute('name', (string) $name);
        appendOptionValue($dom, $option, $value);
        $optionsElement->appendChild($option);
    }

    $parent->appendChild($optionsElement);
}

function appendOptionValue(DOMDocument $dom, DOMElement $option, mixed $value): void
{
    if (is_array($value)) {
        foreach ($value as $name => $childValue) {
            $childOption = $dom->createElementNS(DOCTRINE_NS, 'option');
            $childOption->setAttribute('name', (string) $name);
            appendOptionValue($dom, $childOption, $childValue);
            $option->appendChild($childOption);
        }

        return;
    }

    if (is_bool($value)) {
        $option->nodeValue = toXmlBool($value);
        return;
    }

    $option->nodeValue = (string) $value;
}

function createJoinColumnElement(DOMDocument $dom, array $columnMapping): DOMElement
{
    $joinColumn = $dom->createElementNS(DOCTRINE_NS, 'join-column');

    foreach (['name', 'referencedColumnName', 'onDelete', 'columnDefinition'] as $attribute) {
        if (isset($columnMapping[$attribute])) {
            $joinColumn->setAttribute(toXmlAttributeName($attribute), (string) $columnMapping[$attribute]);
        }
    }

    foreach (['unique', 'nullable'] as $booleanAttribute) {
        if (isset($columnMapping[$booleanAttribute])) {
            $joinColumn->setAttribute(toXmlAttributeName($booleanAttribute), toXmlBool((bool) $columnMapping[$booleanAttribute]));
        }
    }

    return $joinColumn;
}

function toXmlAttributeName(string $attribute): string
{
    return match ($attribute) {
        'targetEntity' => 'target-entity',
        'mappedBy' => 'mapped-by',
        'inversedBy' => 'inversed-by',
        'indexBy' => 'index-by',
        'enumType' => 'enum-type',
        'columnDefinition' => 'column-definition',
        'referencedColumnName' => 'referenced-column-name',
        'onDelete' => 'on-delete',
        default => strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $attribute) ?? $attribute),
    };
}

function toXmlBool(bool $value): string
{
    return $value ? 'true' : 'false';
}
