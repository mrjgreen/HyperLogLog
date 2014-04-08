<?php

class HyperLogLogMinHash extends HyperLogLog {

    private $minHash;

    public function __construct(MinHash $minHash)
    {
        $this->minHash = $minHash;
    }

    public function add($v)
    {
        $hash = parent::add($v);

        $this->minHash->add($hash);
    }

    public function getMinHash()
    {
        return $this->minHash;
    }

    public function merge(HyperLogLogMinHash $hll)
    {
        $this->minHash->merge($hll->getMinHash());

        parent::merge($hll);
    }

    public function export()
    {
        return array(parent::export(), $this->minHash->export());
    }

    public function import($hllStr, $minHashStr)
    {
        parent::import($hllStr);

        $this->minHash->import($minHashStr);
    }
}
