<?php namespace Models;

use Doctrine\Common\Inflector\Inflector;

class Model
{
    /**
     * @var null|PDO
     */
    protected $pdo = null;

    /**
     * @var null
     */
    protected $table = null;

    /**
     * @var array
     */
    private $whereAnd = [];

    /**
     * @var string
     */
    private $select = '*';

    /**
     * @var array
     */
    private $operators = ['=', '>', '<', '!=', '<>'];

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var string
     */
    protected $order = 'id';

    /**
     * @var string
     */
    protected $orderDirection = 'DESC';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    protected $join = '';

    protected $tableNum = 1;


    public function __construct()
    {
        if (!class_exists('\Connect')) throw new \RuntineException("class Connect doesn't exists");

        $this->pdo = \Connect::$pdo;

        if (empty($this->table)) throw new \RuntineException('table name is null, you must set a table name into Entity model...');
    }

    /**
     * @param string|array $args
     * @return $this
     */
    public function select($args = '*')
    {
        if (is_array($args)) {
            $this->select = "`" . implode('`,`', $args) . "`";

            return $this;
        } elseif ($args == '*') {
            $this->select = '*';

            return $this;
        } else {
            throw new \RuntimeException(sprintf('method select Model invalid argument method %s', $args));
        }
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($field, $operator, $value)
    {
        $value = is_numeric($value) ? $value : $this->pdo->quote($value);

        if (in_array($operator, $this->operators)) {
            $this->whereAnd[] = "`$field` $operator $value";

            return $this;
        }

        throw new \RuntimeException(sprintf('unsupported operator %s', $operator));
    }

    public function count()
    {

        $where = $this->buildWhere();

        $res = $this->pdo->query(sprintf(
            'SELECT count(*) as c FROM %s WHERE %s',
            "`" . $this->table . "`",
            $where
        ));

        return $res->fetchColumn();
    }

    /**
     * @param bool $without
     * @return \PDOStatement
     */
    public function get($without = false)
    {
        $where = $this->buildWhere(); // factoring
        $select = $this->select;

        $this->select = '*';
        $this->whereAnd = [];

        if (!$without) {
            $sql = sprintf(
                'SELECT %s FROM %s WHERE %s ORDER By %s %s LIMIT 0, %s',
                $select,
                "`" . $this->table . "`",
                $where,
                $this->order,
                $this->orderDirection,
                $this->limit
            );
        } else {
            $sql = sprintf(
                'SELECT %s FROM %s WHERE %s ',
                $select,
                "`" . $this->table . "`",
                $where
            );
        }

        $this->debug($sql);

        return $this->pdo->query($sql);

    }


    /**
     * @param $data
     * @return PDOStatement
     */
    public function create($data)
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $current) {
            $values[] = (is_numeric($current)) ? (int)$current : $this->pdo->quote($current);

            if (!in_array($key, $this->fillable)) continue;

            $fields[] = $key;
        }

        $fields = "(`" . implode('`, `', $fields) . "`)";
        $values = "(" . implode(',', $values) . ")";

        $sql = sprintf("INSERT INTO %s %s VALUES %s",
            $this->table,
            $fields,
            $values
        );

        $this->debug($sql);

        return $this->pdo->query($sql);
    }

    /**
     * @param $id
     * @param $data
     * @return PDOStatement|void
     */
    public function update($id, $data)
    {
        $sets = [];

        while ($current = current($data)) {

            $value = $this->sanitize($current);

            $key = key($data);

            if (!in_array($key, $this->fillable)) return;

            $sets[] = "`" . $key . "`=$value";
            next($data);
        }

        $sets = implode(', ', $sets);

        $sql = sprintf("UPDATE %s SET %s WHERE id=%d",
            $this->table,
            $sets,
            (int)$id
        );

        $this->debug($sql);

        return $this->pdo->query($sql);
    }

    /**
     * @param $id
     * @return PDOStatement
     */
    public function destroy($id)
    {
        $sql = sprintf("DELETE FROM %s WHERE id=%s",
            $this->table,
            (int)$id
        );

        $this->debug($sql);

        return $this->pdo->query($sql);
    }

    /**
     * @param string $args
     * @return array
     */
    public function all($args = '*')
    {
        $stmt = $this->select($args)->get();

        if (!$stmt) return false;

        return $stmt->fetchAll();
    }

    /**
     * @param $id
     * @param string $args
     * @return mixed
     */
    public function find($id, $args = '*')
    {
        $stmt = $this->select($args)->where('id', '=', $id)->get(true);

        if (!$stmt) return false;

        return $stmt->fetch();
    }

    /**
     * refactoring
     *
     * @return string
     */
    private function buildWhere()
    {
        $where = ' 1=1 ';

        if (!empty($this->whereAnd))
            $where .= "AND " . implode(' AND ', $this->whereAnd);

        $this->debug($where);

        return $where;
    }

    private function sanitize($value)
    {
        foreach ($this->fillable as $fillable)
            if (preg_match('/' . $fillable . '\+1/', $value)) return "`$fillable`+1";

        if (is_float($value)) {
            return (float)$value;
        } elseif (is_integer($value)) {
            return (int)$value;
        } else {
            return $this->pdo->quote($value);
        }
    }

    private function debug($string)
    {
        if (defined('DEBUG') && DEBUG) {
            var_dump($string);
        }
    }

}