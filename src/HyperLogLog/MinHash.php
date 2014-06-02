<?php namespace HyperLogLog;

use HyperLogLog\Utils\MinHash as UtilsMinHash;

class MinHash extends Basic {

    private $minHash;

    public function __construct($HLL_P = self::DEFAULT_HLL, UtilsMinHash $minHash = null)
    {
        $this->minHash = $minHash ?: new UtilsMinHash();

        parent::__construct($HLL_P);
    }

    public static function make($HLL_P = self::DEFAULT_HLL, $MIN_HASH_K = UtilsMinHash::DEFAULT_MIN_HASH_K)
    {
        return new self($HLL_P, new UtilsMinHash($MIN_HASH_K));
    }

    public function add($v)
    {
        return $this->addRaw(static::hash($v));
    }

    public function addRaw($hash)
    {
        $this->minHash->add($hash);

        return parent::addRaw($hash);
    }

    public function getMinHash()
    {
        return $this->minHash;
    }

    public function getMinHashK()
    {
        return $this->minHash->getMinHashK();
    }

    public function union(Basic $hll)
    {
        $this->getMinHash()->union($hll->getMinHash());

        parent::union($hll);
    }

    public function export()
    {
        return array(parent::export(), $this->minHash->export());
    }

    public function import($pair)
    {
        parent::import($pair[0]);

        $this->minHash->import($pair[1]);
    }
}
