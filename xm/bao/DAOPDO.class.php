<?php
require_once 'i_DAOPDO.class.php';
class DAOPDO implements i_DAOPDO{
    private $host;//服务器地址
    private $user;//用户名
    private $pass;//密码
    private $dbname;//数据库名
    private $port;//端口号
    private $charset;//字符集
    private $pdo;
    private static $instance;//静态的保存实例对象的属性
//     私有的__clone()
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __construct($options)
    {
        $this->host=isset($options['host'])?$options['host']:'localhost';//给服务器地址赋值
        $this->user=isset($options['user'])?$options['user']:'root';//给用户名赋值
        $this->pass=isset($options['pass'])?$options['pass']:'root';//给密码赋值
        $this->dbname=isset($options['dbname'])?$options['dbname']:'xm';//给数据库赋值
        $this->port=isset($options['port'])?$options['port']:'3306';//给端口号赋值
        $this->charset=isset($options['charset'])?$options['charset']:'utf8';//给字符集赋值
        try{
            $dsn="mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=$this->charset";
            $user=$this->user;
            $pass=$this->pass;
            $this->pdo=new PDO($dsn,$user,$pass);
        }catch (PDOException $e){
            echo '连接失败,错误信息如下'.$e->getMessage();
            exit;//连接失败以后代码不向下执行
        }

    }

//     提供一个公有的对外公开的创造对象的方法
    public static function getSingleton($options){
        if(!self::$instance instanceof self){
            self::$instance=new self($options);
        }
        return self::$instance;//返回对象
    }
    //    查询所有的数据
    public function fetchAll($sql){
        $pdo_statement=$this->pdo->query($sql);
        if($pdo_statement==false){
            echo 'sql语句有问题'.$this->pdo->errorInfo()[2];
            die();
        }
//            返回查询的二维关联数组
        return $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
    }
//    查询一条记录
    public function fetchRow($sql){
        $pdo_statement=$this->pdo->query($sql);
        if($pdo_statement==false){
            echo 'sql语句有问题'.$this->pdo->errorInfo()[2];
            die();
        }
        //            返回查询的一维关联数组
        return $pdo_statement->fetch(PDO::FETCH_ASSOC);
    }
//    查询某个字段的值
    public function fetchOne($sql){
        $pdo_statement=$this->pdo->query($sql);
        if($pdo_statement==false){
            echo 'sql语句有问题'.$this->pdo->errorInfo()[2];
            die();
        }
        return $pdo_statement->fetchColumn();

    }
//    执行增删改
    public function query($sql){
        $affect_rows=$this->pdo->exec($sql);
        if($affect_rows==false){
            echo 'sql语句有问题'.$this->pdo->errorInfo()[2];
            die();
        }
        if($affect_rows>0){
            return true;
        }else{
            return false;
        }
    }
//    引号转义并且包裹
    public function quote($data){
        return $this->pdo->quote($data);
    }
//    返回刚刚插入成功的id值
    public function getInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}


