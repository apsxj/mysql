<?php

/**
 * MySQL Column Object
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

class Column
{
  /** @property string the column name */
  public $name;

  /** @property integer Type::BOOL | Type::INT | Type::FLOAT | Type::STRING */
  public $type;

  /**
   * Constructor
   *
   * @param string column name
   * @param integer Type::BOOL | Type::INT | Type::FLOAT | Type::STRING
   */
  public function __construct($name, $type = Type::STRING)
  {
    $this->name = $name;
    $this->type = $type;
  }

  /**
   * Convert the string value to the column type
   *
   * @param string $string
   * 
   * @return mixed
   */
  public function value($string)
  {
    switch ($this->type) {
      case Type::BOOL:
        if ($string == 'Y' || $string == '1') {
          return true;
        } else {
          return false;
        }
        break;
      case Type::INT:
        return intval($string);
        break;
      case Type::FLOAT:
        return floatval($string);
        break;
      default:
        return strval($string);
    }
  }
}
