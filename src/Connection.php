<?php

/**
 * MySQL Connection Wrapper
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

class Connection
{
  /** @property object PDO instance */
  protected $pdo;

  /** @property string PDO error message */
  public $error;

  /**
   * Constructor
   *
   * @param string host
   * @param string database name
   * @param string database user
   * @param string database password
   */
  public function __construct($host, $name, $user, $pass)
  {
    try {
      $this->pdo = new \PDO(
        'mysql:host=' . $host . ';dbname=' . $name,
        $user,
        $pass,
        \PDO::ERRMODE_EXCEPTION
      );

      $errorInfo = $this->pdo->errorInfo();

      if (isset($errorInfo[2])) {
        $this->error = $errorInfo[2];
      } else {
        $this->error = false;
      }
    } catch (\PDOException $e) {
      $this->error = $e->getMessage();
    }
  }

  /**
   * Fetch assoc rows with an SQL statement
   *
   * @param string SQL
   * @param array string values
   * 
   * @return array of mixed rows
   */
  public function fetch($sql, $params = array())
  {
    if (count($params) > 0) {
      // Have params, use the safer prepare/execute
      $sth = $this->pdo->prepare($sql);

      if ($res = $sth->execute($params)) {
        if ($rows = $sth->fetchAll(\PDO::FETCH_ASSOC)) {
          return $rows;
        }
      } else {
        $errorInfo = $this->pdo->errorInfo();
        if (isset($errorInfo[2])) {
          $this->error = $errorInfo[2];
        }
      }
    } elseif ($res = $this->pdo->query($sql)) {
      if ($rows = $res->fetchAll(\PDO::FETCH_ASSOC)) {
        return $rows;
      }
    }

    $errorInfo = $this->pdo->errorInfo();
    if (isset($errorInfo[2])) {
      $this->error = $errorInfo[2];
    }

    return false;
  }

  /**
   * Execute an insert statement and return the insert id
   * 
   * @param string SQL
   * @param array string values
   * 
   * @return integer
   */
  public function insert($sql, $params = array())
  {
    if (count($params) > 0) {
      // Have params, use the safer prepare/execute
      $sth = $this->pdo->prepare($sql);

      if ($res = $sth->execute($params)) {
        return $this->pdo->lastInsertId();
      }
    } elseif ($res = $this->pdo->exec($sql)) {
      return $this->pdo->lastInsertId();
    }

    $errorInfo = $this->pdo->errorInfo();

    if (isset($errorInfo[2])) {
      $this->error = $errorInfo[2];
    }

    return false;
  }

  /**
   * Execute an update statement and return the number of affected rows
   * 
   * @param string SQL
   * @param array string values
   * 
   * @return integer number of affected rows
   */
  public function update($sql, $params = array())
  {
    if (count($params) > 0) {
      // Have params, use the safer prepare/execute
      $sth = $this->pdo->prepare($sql);

      if ($res = $sth->execute($params)) {
        return $sth->rowCount();
      }
    } elseif ($res = $this->pdo->exec($sql)) {
      return $res;
    }

    $errorInfo = $this->pdo->errorInfo();
    if (isset($errorInfo[2])) {
      $this->error = $errorInfo[2];
    }

    return false;
  }

  /**
   * Execute an update statement and return the number of affected rows
   * 
   * NOTE: This is just an alias for SQLConnection::update
   * 
   * @param string SQL
   * @param array string values
   * 
   * @return integer number of affected rows
   */
  public function delete($sql, $params = array())
  {
    return $this->update($sql, $params);
  }

  /**
   * Whenever we receive params from an endpoint, use this to prepare 
   * the statement and then call execute, passing in the parameters
   *
   * @param string SQL with placeholders
   * 
   * @return object PDO Statement Handle
   */
  public function prepare($sql)
  {
    return $this->pdo->prepare($sql);
  }

  /**
   * Passthrough method (using MySQL command 'START')
   *
   * @return void
   */
  public function startTransaction()
  {
    $this->pdo->beginTransaction();
  }

  /**
   * Passthrough method to commit a transaction
   *
   * @return void
   */
  public function commitTransaction()
  {
    $this->pdo->commit();
  }

  /**
   * Passthrough method to roll back a transaction
   *
   * @return void
   */
  public function rollbackTransaction()
  {
    $this->pdo->rollBack();
  }
}
