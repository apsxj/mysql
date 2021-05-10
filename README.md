# mysql #

_MySQL Library for APSXJ Server_

### Getting Started ###

1. Add the following to your `composer.json` file:

```JavaScript
  "require": {
    "apsxj/mysql": "dev-main"
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/apsxj/mysql.git"
    }
  ]
```

2. Run `composer install`

3. Before calling any of the methods, require the vendor autoloader

```PHP
// For example, from the root directory...
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
```

4. Extend the `Connection` class

```PHP
<?php

class Connection extends \apsxj\mysql\Connection 
{
  public function __construct()
  {
    parent::__construct(
      'localhost',
      'test_db',
      'test_user',
      'supersecret'
    );
  }
}

$connection = new Connection();

$rows = $connection->fetch("SELECT * FROM `test_table`;");
```

4. Extend the `Table` class

```PHP
<?php

use apsxj\mysql\Column;
use apsxj\mysql\Type;

class Table extends \apsxj\mysql\Table
{
  public function __construct()
  {
    parent::__construct(
      new Connection(),
      'test',
      array(
        new Column('id', Type::INT),
        new Column('created', Type::INT),
        new Column('updated', Type::INT),
        new Column('deleted', Type::BOOL),
        new Column('name')
      )
    );
  }

  public function insert($name)
  {
    $sql = "INSERT INTO `test` (`id`, `created`, `updated`, `deleted`, `name`) VALUES (NULL, ?, ?, 0, ?);";
    $date = date('U');
    $args = array($date, $date, $name);
    return $this->connection->insert($sql, $args);
  }
}

$table = new Table();

$table->insert('Test Name');
```
