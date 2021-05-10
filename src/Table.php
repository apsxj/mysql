<?php

/**
 * MySQL Table Object
 *
 * @category  Server Software
 * @package   apsxj/mysql
 * @author    Roderic Linguri <apsxj@mail.com>
 * @copyright 2021 Roderic Linguri
 * @license   https://github.com/apsxj/mysql/blob/main/LICENSE MIT
 * @link      https://github.com/apsxj/mysql
 * @version   0.1.0
 * @since     0.1.0
 */

namespace apsxj\mysql;

class Table
{
  /** The database connection object */
  protected $connection;

  /** @property string the table name */
  protected $name;

  /** @property array of column objects */
  protected $columns;

  /** @property string error message */
  public $error;

  /**
   * Constructor
   *
   * @param object Connection
   * @param string table name
   * @param array of Column objects
   */
  public function __construct($connection, $name, $columns)
  {
    $this->error = false;
    $this->connection = $connection;
    $this->name = $name;
    $this->columns = $columns;
    $this->error = $this->connection->error;
  }

  /**
   * Fetch all rows from a table (use with caution)
   *
   * @param boolean whether or not to type the values
   * 
   * @return void
   */
  public function fetchAll($typed = false)
  {
    $sql = "SELECT * FROM `" . $this->name . "`;";

    if ($rows = $this->connection->fetch($sql)) {
      if ($typed) {
        $typed = array();
        foreach ($rows as $row) {
          array_push($typed, $this->typed($row));
        }
        return $typed;
      } else {
        return $rows;
      }
    }

    $this->error = $this->connection->error;
    return false;
  }

  /**
   * Fetch a row by its integer ID
   *
   * @param integer $id
   * @param boolean whether or not to type the values
   * 
   * @return mixed
   */
  public function fetchRowById($id, $typed = false)
  {
    $sql = "SELECT * FROM `" . $this->name . "` WHERE `id` = ? ;";

    if ($rows = $this->connection->fetch($sql, array($id))) {
      foreach ($rows as $row) {
        if ($typed) {
          return $this->typed($row);
        } else {
          return $row;
        }
      }
    }

    $this->error = $this->connection->error;
    return false;
  }

  /**
   * Update a database row by its integer id
   * 
   * @param integer id
   * @param string column name
   * @param string the new value
   * 
   * @return integer count of rows affected
   */
  public function updateRowById($id, $column, $value)
  {
    $sql = "UPDATE `" . $this->name . "` SET `" . $column . "` = ? WHERE `id` = ?;";

    if ($affected = $this->connection->update($sql, array($value, $id))) {
      return $affected;
    }

    $this->error = $this->connection->error;
    return false;
  }

  /**
   * Delete a row by its integer id
   *
   * @param integer id
   * 
   * @return integer count of rows affected
   */
  public function deleteRowById($id)
  {
    $sql = "DELETE FROM `" . $this->name . "` WHERE `id` = ? ;";

    if ($affected = $this->connection->update($sql, array($id))) {
      return $affected;
    } else {
      $this->error = $this->connection->error;
      return false;
    }
  }

  /**
   * name property getter
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Return an array of column names
   * 
   * @return array of string
   */
  protected function keys()
  {
    $keys = array();
    foreach ($this->columns as $column) {
      array_push($keys, $column->name);
    }
    return $keys;
  }

  /**
   * Format keys for an insert statement
   * 
   * @return string
   */
  protected function insertKeys()
  {
    return '(`' . implode('`, `', $this->keys())  . '`)';
  }

  /**
   * Assemble SQL insert command with formatted keys for the insert statement without values
   */
  protected function insertPrefix()
  {
    return "INSERT INTO `" . $this->name . "` " . $this->insertKeys() . " VALUES ";
  }

  /**
   * Convert PDO::FETCH_ASSOC to typed values
   *
   * @param mixed row
   * 
   * @return mixed
   */
  protected function typed($row)
  {
    $typed = array();
    foreach ($this->columns as $column) {
      $typed[$column->name] = $column->value($row[$column->name]);
    }
    return $typed;
  }
}
