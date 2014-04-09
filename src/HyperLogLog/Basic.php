<?php namespace HyperLogLog;

use SplFixedArray;

class Basic {

    /* With P=14, 16384 registers. */
    const DEFAULT_HLL = 14;

    private $HLL_P_MASK;

    private $HLL_REGISTERS;

    private $ALPHA;

    /**
     * @var SplFixedArray
     */
    private $registers;

    public function __construct($HLL_P = self::DEFAULT_HLL)
    {
        $this->resize(1 << $HLL_P);

        $this->registers = new SplFixedArray($this->HLL_REGISTERS);

        for ($i = 0; $i < $this->HLL_REGISTERS; $i++) {
            $this->registers[$i] = 0;
        }
    }

    private function resize($register_size)
    {
        $this->HLL_REGISTERS = $register_size;

        $this->HLL_P_MASK = ($this->HLL_REGISTERS - 1); /* Mask to index register. */

        $this->ALPHA = 0.7213 / (1 + 1.079 / $this->HLL_REGISTERS);

        if(isset($this->registers))
        {
            $this->registers->setSize($this->HLL_REGISTERS);
        }
    }

    public function add($v)
    {
        $h = $hash = crc32(md5($v));

        $h |= 1 << 63; /* Make sure the loop terminates. */
        $bit = $this->HLL_REGISTERS; /* First bit not used to address the register. */
        $count = 1; /* Initialized to 1 since we count the "00000...1" pattern. */
        while(($h & $bit) == 0) {
            $count++;
            $bit <<= 1;
        }

        /* Update the register if this element produced a longer run of zeroes. */
        $index = $h & $this->HLL_P_MASK; /* Index a register inside registers. */

        if ($this->registers[$index] < $count) {
            $this->registers[$index] = $count;
        }

        return $hash;
    }

    public function export()
    {
        $str = '';
        for ($i = 0; $i < $this->HLL_REGISTERS; $i++) {
            $str .= chr($this->registers[$i]);
        }
        return $str;
    }

    public function import($str)
    {
        for ($i = 0; $i < $this->HLL_REGISTERS; $i++) {
            $this->registers[$i] = isset($str[$i]) ? ord($str[$i]) : 0;
        }
    }

    public function getRegisters()
    {
        return $this->registers->toArray();
    }

    public function union(Basic $hll)
    {
        $registers = $hll->getRegisters();

        // The set to be unioned may be bigger than this initial set so we need to increase this set to match
        if(count($this->registers) < ($newCount = count($registers)))
        {
            $this->resize($newCount);
        }


        for ($i = 0; $i < count($registers); $i++) {
            if (!isset($this->registers[$i]) || $this->registers[$i] < $registers[$i]) {
                $this->registers[$i] = $registers[$i];
            }
        }
    }

    /**
     * @static
     * @param $arr
     * @return int Number of unique items in $arr
     */
    public function count() {
        $E = 0;

        $ez = 0;

        for ($i = 0; $i < $this->HLL_REGISTERS; $i++) {
            if ($this->registers[$i] !== 0) {
                $E += (1.0 / pow(2, $this->registers[$i]));
            } else {
                $ez++;
                $E += 1.0;
            }
        }

        $E = (1 / $E) * $this->ALPHA * $this->HLL_REGISTERS * $this->HLL_REGISTERS;

        /* Use the LINEARCOUNTING algorithm for small cardinalities.
         * For larger values but up to 72000 HyperLogLog raw approximation is
         * used since linear counting error starts to increase. However HyperLogLog
         * shows a strong bias in the range 2.5*16384 - 72000, so we try to
         * compensate for it. */
        if ($E < $this->HLL_REGISTERS * 2.5 && $ez != 0) {
            $E = $this->HLL_REGISTERS * log($this->HLL_REGISTERS / $ez); /* LINEARCOUNTING() */
        }

        else if ($this->HLL_REGISTERS == 16384 && $E < 72000) {
            // We did polynomial regression of the bias for this range, this
            // way we can compute the bias for a given cardinality and correct
            // according to it. Only apply the correction for P=14 that's what
            // we use and the value the correction was verified with.
            $bias = 5.9119 * 1.0e-18 * ($E*$E*$E*$E)
                -1.4253 * 1.0e-12 * ($E*$E*$E)+
                1.2940 * 1.0e-7 * ($E*$E)
                -5.2921 * 1.0e-3 * $E+
                83.3216;
            $E -= $E * ($bias/100);
        }

        return floor($E);
    }
}
