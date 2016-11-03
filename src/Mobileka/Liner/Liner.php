<?php

namespace Mobileka\Liner;

use Closure;
use SplFileObject;

class Liner implements LinerInterface
{
    /**
     * @var SplFileObject
     */
    protected $file;

    /**
     * @var int
     */
    protected $numberOfLines;

    /**
     * @param string|SplFileObject $file
     */
    public function __construct($file)
    {
        $file = $file instanceof SplFileObject
            ? $file
            : new SplFileObject($file, 'r');

        $this->setFile($file);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param Closure $modifier
     *
     * @return array
     */
    public function read($limit = 0, $offset = 0, Closure $modifier = null)
    {
        $file = $this->getFile();
        $file->rewind();
        $result = [];

        $numberOfLines = $this->getNumberOfLines();
        $offset = $this->getOffset($offset);
        $limit = $this->getLimit($limit, $offset);

        if ($offset >= $numberOfLines) {
            return [];
        }

        $this->setOffset($offset);

        for ($i = 0; $i < $limit; $i++) {
            $line = $file->current();

            if (!is_null($modifier)) {
                $line = $modifier($file, $line);

                if (is_null($line)) {
                    $this->setNumberOfLines($this->getNumberOfLines() - 1);
                    $file->next();
                    continue;
                }
            }

            $result[] = $line;
            $file->next();
        }

        $file = null;

        return $result;
    }

    /**
     * @return int
     */
    public function getNumberOfLines()
    {
        if (!is_null($this->numberOfLines)) {
            return $this->numberOfLines;
        }

        $file = $this->getFile();
        $currentPosition = $file->key();

        $file->rewind();

        $file->seek($file->getSize());
        $result = $file->key() + 1;

        $file->seek($currentPosition);

        return $this->numberOfLines = $result;
    }

    /**
     * @param int $number
     */
    protected function setNumberOfLines($number)
    {
        $this->numberOfLines = (int) $number;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return int
     */
    public function getLimit($limit = 0, $offset = 0)
    {
        $numberOfLines = $this->getNumberOfLines();
        $maxLimit = $numberOfLines - $offset;

        if ($limit < 0) {
            $limit = 0;
        }

        if (!$limit) {
            $limit = $numberOfLines - $offset;
        }

        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }

        return $limit;
    }

    /**
     * @param int $offset
     *
     * @return int
     */
    public function getOffset($offset = 0)
    {
        $numberOfLines = $this->getNumberOfLines();

        if ($offset > $numberOfLines) {
            return $numberOfLines;
        }

        if ($offset < 0) {
            $offset += $numberOfLines;
            $offset = $offset < 0 ? 0 : $offset;
        }

        return $offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset = 0)
    {
        $this->getFile()->seek($offset);
    }

    /**
     * @return SplFileObject
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param SplFileObject $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->getFile(), $method)) {
            return call_user_func_array([$this->getFile(), $method], $args);
        }

        $class = __CLASS__;
        $trace = debug_backtrace();
        $file = $trace[0]['file'];
        $line = $trace[0]['line'];
        trigger_error("Call to undefined method $class::$method() in $file on line $line", E_USER_ERROR);
    } // @codeCoverageIgnore
}