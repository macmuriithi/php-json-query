<?php
require_once 'JsonQuery.php';
$jsonData = '[
    {"id": 1, "name": "John", "age": 30, "salary": 50000, "department": "IT", "city": "New York"},
    {"id": 2, "name": "Jane", "age": 25, "salary": 55000, "department": "HR", "city": "Los Angeles"},
    {"id": 3, "name": "Bob", "age": 35, "salary": 60000, "department": "IT", "city": "Chicago"},
    {"id": 4, "name": "Alice", "age": 28, "salary": 52000, "department": "Finance", "city": "New York"},
    {"id": 5, "name": "Charlie", "age": 40, "salary": 70000, "department": "IT", "city": "Los Angeles"}
]';

echo "Example 1: Basic filtering and sorting\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['name', 'age', 'salary'])
              ->where('age >= 28')
              ->orderBy('salary', 'DESC')
              ->getResult();
print_r($result);

echo "\nExample 2: Using LIKE for pattern matching\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['name', 'age'])
              ->where('name LIKE a')
              ->orderBy('age')
              ->getResult();
print_r($result);

echo "\nExample 3: Multiple conditions and limiting results\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['id', 'name', 'salary'])
              ->where('age < 35')
              ->where('salary > 50000')
              ->orderBy('salary')
              ->limit(2)
              ->getResult();
print_r($result);

echo "\nExample 4: Grouping and counting\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['department', 'city'])
              ->groupBy('department')
              ->aggregate('COUNT', 'id')
              ->getResult();
print_r($result);

echo "\nExample 5: Complex filtering with multiple conditions\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['name', 'age', 'salary', 'city'])
              ->where('age > 25')
              ->where('salary >= 55000')
              ->where('city != Chicago')
              ->orderBy('age')
              ->getResult();
print_r($result);

echo "\nExample 6: Nested grouping and aggregation\n";
$jql = new JsonQuery($jsonData);
$result = $jql->select(['department', 'city', 'salary'])
              ->groupBy('department')
              ->groupBy('city')
              ->aggregate('AVG', 'salary')
              ->getResult();
print_r($result);
