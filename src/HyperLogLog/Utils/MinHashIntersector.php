<?php namespace HyperLogLog\Utils;

use HyperLogLog\Basic;
use HyperLogLog\MinHash as HyperLogLogMinHash;

class MinHashIntersector
{
    public static function count(array $minHashes, $strict = true)
    {
        $minHashK = self::getMinHashKForSet($minHashes, $strict);

        $totalHll = new HyperLogLogMinHash(Basic::DEFAULT_HLL, new MinHash($minHashK));

        $intersection = null;

        foreach($minHashes as $hll)
        {
            $totalHll->union($hll);

            $hashK = $hll->getMinHash()->toArray();

            $intersection = isset($intersection) ? array_intersect($intersection, $hashK) : $hashK;

            if(count($intersection) === 0)
            {
                return 0;
            }
        }

        $intersection = array_intersect($intersection, $totalHll->getMinHash()->toArray());

        $hllUnionCount = $totalHll->count();

        /**
         * For low numbers there is no need to estimate
         * If we assume an even spread with no has collisions then the intersection of
         * the min hash data structures will be accurate until the size of the union is
         * greater than the max size of the min hash data structure
         */
        if($hllUnionCount < $minHashK)
        {
            return count($intersection);
        }

        return floor((count($intersection) / $minHashK) * $hllUnionCount);
    }

    private static function getMinHashKForSet(array $minHashes, $strict)
    {
        $set = array();

        foreach($minHashes as $minHash)
        {
            if(!$minHash instanceof HyperLogLogMinHash)
            {
                throw new \Exception('All arguments must be of type "HyperLogLogMinHash", ' . gettype($minHash) . ' given.');
            }

            $set[$minHash->getMinHashK()] = 1;
        }

        if(count($set) > 1 and $strict)
        {
            throw new \Exception('With strict mode turned on, the min hash k values for each hash data object must match.');
        }


        return min(array_keys($set));
    }

}