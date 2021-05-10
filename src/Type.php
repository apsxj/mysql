<?php

/**
 * MySQL Type Class
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

class Type
{

  /** @property-read integer boolean case */
  const BOOL = 1;

  /** @property-read integer integer case */
  const INT = 2;

  /** @property-read integer float case */
  const FLOAT = 3;

  /** @property-read integer string case */
  const STRING = 4;
}
