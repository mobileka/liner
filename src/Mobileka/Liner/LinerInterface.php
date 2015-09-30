<?php

namespace Mobileka\Liner;

use Closure;
use SplFileObject;

interface LinerInterface
{
    /**
     * @param int     $limit
     * @param int     $offset
     * @param Closure $modifier
     * @return array
     */
    public function read($limit = 0, $offset = 0, Closure $modifier = null);

    /**
     * @return int
     */
    public function getNumberOfLines();

    /**
     * @param int $limit
     * @param int $offset
     * @return int
     */
    public function getLimit($limit = 0, $offset = 0);

    /**
     * @param int $offset
     * @return int
     */
    public function getOffset($offset = 0);

    /**
     * @param int $offset
     */
    public function setOffset($offset = 0);

    /**
     * @return SplFileObject
     */
    public function getFile();

    /**
     * @param SplFileObject $file
     */
    public function setFile($file);
}
