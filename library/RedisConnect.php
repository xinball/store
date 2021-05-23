<?php
namespace library;
use Redis;
use RedisException;

class RedisConnect{
    private static $redis;
    private static function connection(){
        //连接到本地的 redis 服务
        self::$redis=new Redis();
        self::$redis->connect('127.0.0.1');
        self::$redis->select(1);
        //echo "connection to server successfully<br>";
    }
    private static function check(): bool{
        if(self::$redis!=null){
            try {
                if (self::$redis->ping() == true) {
                    return true;
                }
            } catch (RedisException $e) {
                return false;
            }
        }
        return false;
    }
    public static function getKey($key){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->get($key);
    }

    public static function setKey($key,$value,$expire=null){
        if(!self::check()){
            self::connection();
        }
        self::$redis->set($key,$value);
        if($expire){
            self::$redis->expire($key,$expire-time());
        }
    }

    public static function getList($key){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->lrange($key, 0, -1);
    }

    public static function setList($key,$list){
        if(!self::check()){
            self::connection();
        }
        foreach ($list as $value)
            self::$redis->lpush($key,$value);
    }

    public static function getListIndex($key,$index){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->lIndex($key, $index);
    }

    public static function setListIndex($key, $index, $value){
        if(!self::check()){
            self::connection();
        }
        self::$redis->lSet($key, $index, $value);
    }


    public static function getHashKey($key, $hashKey){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->hGet($key, $hashKey);
    }

    public static function getHash($key){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->hGetAll($key);
    }

    public static function setHash($key,$hashKey,$value){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->hSet($key,$hashKey,$value);
    }
    public static function del($key,...$otherKeys){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->del($key,...$otherKeys);
    }
    public static function ttl($key){
        if(!self::check()){
            self::connection();
        }
        return self::$redis->ttl($key);
    }
}

