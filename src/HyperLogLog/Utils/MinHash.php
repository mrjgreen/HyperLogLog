<?php namespace HyperLogLog\Utils;

class MinHash {

    const DEFAULT_MIN_HASH_K = 8921;

    private $MIN_HASH_K;

    private $registers = array();

    public function __construct($MIN_HASH_K = self::DEFAULT_MIN_HASH_K)
    {
        $this->MIN_HASH_K = $MIN_HASH_K;
    }

    public function add($hash)
    {
        $this->registers[$hash] = 1;

        // We don't need to sort and slice every time - it's very very expensive!
        if(count($this->registers) >= $this->MIN_HASH_K * 2)
        {
            $this->clean();
        }
    }

    public function clean()
    {
        ksort($this->registers);

        $this->registers = array_slice($this->registers, 0, $this->MIN_HASH_K, true);
    }

    public function toArray()
    {
        $this->clean();

        return array_keys($this->registers);
    }

    public function getMinHashK()
    {
        return $this->MIN_HASH_K;
    }

    public function union(MinHash $minHash)
    {
        foreach($minHash->toArray() as $hash)
        {
            $this->registers[$hash] = 1;
        }

        $this->clean();
    }

    public function export()
    {
        return call_user_func_array('pack', array_merge(array('L*'), $this->toArray()));

        //return implode(',',$this->toArray());
    }

    public function import($str)
    {
        //$hash = explode(',', $str);

        $hash = unpack('L*',$str);

        $this->registers = array_flip($hash);
    }
}
