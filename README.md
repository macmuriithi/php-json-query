# JsonQuery

`JsonQuery` is a PHP class for querying and manipulating JSON data. It provides methods for selecting fields, filtering records, sorting, grouping, aggregating, and limiting results.

## Installation

To use the `JsonQuery` class, include the PHP file in your project:

```php
include 'JsonQuery.php';
```

## Usage

### Creating an Instance

Create a new instance of `JsonQuery` by passing a JSON string to the constructor:

```php
$jsonData = '[{"name": "John", "age": 30}, {"name": "Jane", "age": 25}]';
$query = new JsonQuery($jsonData);
```

### Selecting Fields

To select specific fields from the data:

```php
$query->select(['name']);
```

### Filtering Records

To filter records based on conditions:

```php
$query->where('age > 25');
```

### Sorting Data

To sort the data by a specific field:

```php
$query->orderBy('age', 'ASC');
```

### Grouping Records

To group records by a specific field:

```php
$query->groupBy('age');
```

### Aggregating Values

To perform aggregation functions like SUM, AVG, MIN, MAX, or COUNT:

```php
$query->aggregate('SUM', 'age');
```

### Limiting Results

To limit the number of results:

```php
$query->limit(10, 0);
```

### Getting the Result

To get the final result of the query:

```php
$result = $query->getResult();
print_r($result);
```

## Methods

### `__construct($jsonData)`

- **Parameters**: `$jsonData` - A JSON string to initialize the data.

### `select($fields)`

- **Parameters**: `$fields` - An array of fields to select or a single field name.
- **Returns**: `self` - The instance of `JsonQuery`.

### `where($condition)`

- **Parameters**: `$condition` - A string representing the filter condition.
- **Returns**: `self` - The instance of `JsonQuery`.

### `orderBy($field, $direction = 'ASC')`

- **Parameters**:
  - `$field` - The field to sort by.
  - `$direction` - The sort direction, either 'ASC' or 'DESC'.
- **Returns**: `self` - The instance of `JsonQuery`.

### `groupBy($field)`

- **Parameters**: `$field` - The field to group by.
- **Returns**: `self` - The instance of `JsonQuery`.

### `aggregate($function, $field)`

- **Parameters**:
  - `$function` - The aggregate function (SUM, AVG, MIN, MAX, COUNT).
  - `$field` - The field to aggregate.
- **Returns**: `self` - The instance of `JsonQuery`.

### `limit($count, $offset = 0)`

- **Parameters**:
  - `$count` - The number of records to return.
  - `$offset` - The starting point to return records from.
- **Returns**: `self` - The instance of `JsonQuery`.

### `getResult()`

- **Returns**: The filtered, sorted, grouped, and aggregated data.

## Example

```php
$jsonData = '[{"name": "John", "age": 30}, {"name": "Jane", "age": 25}]';
$query = new JsonQuery($jsonData);

$result = $query
    ->select(['name'])
    ->where('age > 25')
    ->orderBy('name', 'ASC')
    ->getResult();

print_r($result);
```

## License

This project is licensed under the MIT License
