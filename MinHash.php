<?php

class MinHash {

    private $MIN_HASH_K;

    private $registers = array();

    public function __construct($MIN_HASH_K = 9000)
    {
        $this->MIN_HASH_K = $MIN_HASH_K;
    }

    public function add($hash)
    {
        $this->registers[$hash] = 1;

        // We don't need to sort and slice every time - it's very very expensive!
        if(count($this->registers) > $this->MIN_HASH_K * 2)
        {
            $this->clean();
        }
    }

    public function clean()
    {
        ksort($this->registers);

        $this->registers = array_slice($this->registers, 0, $this->MIN_HASH_K, true);
    }

    public function getMinHash()
    {
        $this->clean();

        return array_keys($this->registers);
    }

    public function merge(MinHash $minHash)
    {
        foreach($minHash->getMinHash() as $hash)
        {
            $this->registers[$hash] = 1;
        }

        $this->clean();
    }

    public function export()
    {
        return implode(',',$this->getMinHash());
    }

    public function import($str)
    {
        $hash = explode(',', $str);

        $this->registers = array_flip($hash);
    }
}
