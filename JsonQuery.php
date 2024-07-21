<?php
class JsonQuery {
    private $data;
    private $result;
    private $selectedFields = [];
    private $groupedFields = [];

    public function __construct($jsonData) {
        $this->data = json_decode($jsonData, true);
        $this->result = $this->data;
    }

    public function select($fields) {
        $this->selectedFields = is_array($fields) ? $fields : [$fields];
        return $this;
    }

    public function where($condition) {
        $this->result = array_filter($this->result, function($item) use ($condition) {
            return $this->evaluateCondition($item, $condition);
        });
        return $this;
    }

    private function evaluateCondition($item, $condition) {
        $operators = ['>=', '<=', '!=', '=', '>', '<', 'LIKE'];
        foreach ($operators as $op) {
            if (strpos($condition, $op) !== false) {
                list($field, $value) = array_map('trim', explode($op, $condition, 2));
                $value = trim($value, " '\"");
                
                if (!isset($item[$field])) return false;

                switch ($op) {
                    case '=': return $item[$field] == $value;
                    case '!=': return $item[$field] != $value;
                    case '>': return $item[$field] > $value;
                    case '<': return $item[$field] < $value;
                    case '>=': return $item[$field] >= $value;
                    case '<=': return $item[$field] <= $value;
                    case 'LIKE': return stripos($item[$field], $value) !== false;
                }
            }
        }
        return false;
    }

    public function orderBy($field, $direction = 'ASC') {
        usort($this->result, function($a, $b) use ($field, $direction) {
            $aVal = isset($a[$field]) ? $a[$field] : null;
            $bVal = isset($b[$field]) ? $b[$field] : null;
            
            if ($aVal === $bVal) return 0;
            if ($aVal === null) return $direction === 'ASC' ? -1 : 1;
            if ($bVal === null) return $direction === 'ASC' ? 1 : -1;
            
            return (($aVal < $bVal) xor ($direction === 'DESC')) ? -1 : 1;
        });
        return $this;
    }

    public function groupBy($field) {
        $this->groupedFields[] = $field;
        return $this;
    }

    public function aggregate($function, $field) {
        if (empty($this->groupedFields)) {
            throw new \Exception("Aggregate can only be used after groupBy");
        }

        $this->result = $this->recursiveGroupAndAggregate($this->result, $this->groupedFields, $function, $field);
        return $this;
    }

    private function recursiveGroupAndAggregate($data, $groupFields, $function, $aggregateField) {
        if (empty($groupFields)) {
            return $this->performAggregate($function, array_column($data, $aggregateField));
        }

        $currentField = array_shift($groupFields);
        $grouped = [];

        foreach ($data as $item) {
            $key = isset($item[$currentField]) ? $item[$currentField] : 'null';
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $item;
        }

        $result = [];
        foreach ($grouped as $key => $group) {
            $result[$key] = $this->recursiveGroupAndAggregate($group, $groupFields, $function, $aggregateField);
        }

        return $result;
    }

    private function performAggregate($function, $values) {
        $values = array_filter($values, 'is_numeric');
        if (empty($values)) return null;

        switch (strtoupper($function)) {
            case 'SUM': return array_sum($values);
            case 'AVG': return array_sum($values) / count($values);
            case 'MIN': return min($values);
            case 'MAX': return max($values);
            case 'COUNT': return count($values);
            default: throw new \Exception("Unsupported aggregate function");
        }
    }

    public function limit($count, $offset = 0) {
        $this->result = array_slice($this->result, $offset, $count);
        return $this;
    }

    public function getResult() {
        if (!empty($this->selectedFields) && empty($this->groupedFields)) {
            return array_map(function($item) {
                return array_intersect_key($item, array_flip($this->selectedFields));
            }, $this->result);
        }
        return $this->result;
    }
}
