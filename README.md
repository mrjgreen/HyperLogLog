HyperLogLog
===========

PHP implementation of the HyperLogLog algorithm. [Based on Antirez/Redis implementation.](https://github.com/antirez/redis/blob/unstable/src/hyperloglog.c)

### Note!
This version has been tuned to work with a P value of 14. This is a register size of 2^14 Bytes = 16KB

There is a large bias that can be seen in the graphs below, which begins when the set cardinality reaches around 2^P * 2.5. Polynomial regression has been used to calculate bias offsets BUT ONLY FOR P = 14. You are free to change the P value but the bias offsets will not be applied. Check out the code for more information
