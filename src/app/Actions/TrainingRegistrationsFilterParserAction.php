<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrainingRegistrationsFilterParserAction
{
    const PARSER_SEPARATOR = '/';

    const FILTER_OPERATIONS = ['asc', 'desc', '=', '<', '>', '<=', '>='];

    const OPERATION_SQL = [
        'asc' => 'orderBy',
        'desc' => 'orderBy',
        '=' => 'where',
        '>=' => 'where',
        '<=' => 'where',
        '>' => 'where',
        '<' => 'where',
    ];

    public function __construct(
        private string $text,
        private Model $model,
        private Builder $builder
        ) {
    }

    public function parse()
    {
        $lines = explode(self::PARSER_SEPARATOR, $this->text);
        $modelFillables = $this->model->getFillable();
        array_push($modelFillables, 'created_at');
        foreach ($lines as $filterLine) {
            $line = $this->readLine($filterLine);
            if (count($line) === 0) {
                return false;
            }
            $this->parseLine($line, $this->builder);
        }

        return $this->builder;
    }

    public function readLine(string $line): array
    {
        $filterKeyValue = [];
        $operationMethod = null;
        $operationValue = null;
        foreach (self::FILTER_OPERATIONS as $operation) {
            if (str_contains($line, $operation)) {
                $filterKeyValue = explode($operation, $line);
                $operationMethod = self::OPERATION_SQL[$operation];
                $operationValue = $operation;
            }
        }
        if (count($filterKeyValue) !== 2) {
            return [];
        }
        $filterName = $filterKeyValue[0];
        $filterValue = $filterKeyValue[1];
        $columnName = $this->getColumnName($filterName);
        if ($columnName === 'is_celec_member') {
            $columnName = 'is_celec_memeber';
        }

        return ['columnName' => $columnName, 'value' => $filterValue, 'operationMethod' => $operationMethod, 'operationValue' => $operationValue];
    }

    public function getColumnName(string $filterName): string
    {
        return str_replace(' ', '_', strtolower($filterName));
    }

    public function parseLine(array $filter): Builder
    {
        if (count($filter) !== 4) {
            return $this->builder;
        }
        if ($filter['operationMethod'] === null or $filter['operationValue'] === null) {
            return $this->builder;
        }

        return ($filter['operationMethod'] === 'where')
            ? $this->builder->where($filter['columnName'], $filter['operationValue'], $filter['value'])
            : $this->builder->orderBy($filter['columnName'], $filter['operationValue']);
    }
}
