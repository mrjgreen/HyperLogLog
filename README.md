HyperLogLog & MinHash
===========

PHP implementation of the HyperLogLog algorithm. [Based on Antirez/Redis implementation.](https://github.com/antirez/redis/blob/unstable/src/hyperloglog.c)

### Resources

 * [The original HLL algorithm from Phillipe Flajolet](http://algo.inria.fr/flajolet/Publications/FlFuGaMe07.pdf)
 * [An actual paper on the algorithm with real maths and scary equations.](http://stefanheule.com/papers/edbt2013-hyperloglog.pdf)
 * [Awesome explanation and experimental data from AdRoll](http://tech.adroll.com/media/hllminhash.pdf)
 * [Very nice blog post explaining what the HLL this is ;)](http://research.neustar.biz/2012/10/25/sketch-of-the-day-hyperloglog-cornerstone-of-a-big-data-infrastructure/)


### Note!
This version has been tuned to work with a P value of 14. This is a register size of 2^14 Bytes = 16KB

There is a large bias that can be seen in the graphs below, which begins when the set cardinality reaches around 2^P * 2.5. Polynomial regression has been used to calculate bias offsets BUT ONLY FOR P = 14. You are free to change the P value but the bias offsets will not be applied. [Check out the code for more information](https://github.com/joegreen0991/HyperLogLog/blob/master/src/HyperLogLog/Basic.php#L141)


### Some Professional Looking Graphs

####HyperLogLog

*P=14*
![HyperLogLog P = 14](https://raw.githubusercontent.com/joegreen0991/HyperLogLog/master/errortest/img/P14hll.png)


*P=16*
Note the offset bias around 2.5 * 2^16 ~= 165,000
![HyerLogLog P = 16](https://raw.githubusercontent.com/joegreen0991/HyperLogLog/master/errortest/img/p16hll.png)

*P=20*
Note the offset bias around 2.5 * 2^20 ~= 2,600,000
![HyerLogLog P = 20](https://raw.githubusercontent.com/joegreen0991/HyperLogLog/master/errortest/img/p20hll.png)

####MinHash

*K=8192*
![MinHash K = 8129](https://raw.githubusercontent.com/joegreen0991/HyperLogLog/master/errortest/img/minhask8192.png)
