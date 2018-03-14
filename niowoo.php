<?php

/*
一.按照给定菜单(menu)和订单(order)，计算订单价格总和
*/

$menu = '[
			{"type_id":1,"name":"大菜","food":[
											{"food_id":1,"name":"鱼香肉丝","price":"10"},
											{"food_id":2,"name":"红烧肉","price":"11"},
											{"food_id":3,"name":"香辣粉","price":"12"}
											]},
			{"type_id":2,"name":"中菜","food":[
											{"food_id":4,"name":"小炒肉","price":"13"},
											{"food_id":5,"name":"云吞","price":"14"}
											]},
			{"type_id":3,"name":"小菜","food":[
											{"food_id":6,"name":"雪糕","price":"15"},
											{"food_id":7,"name":"黄瓜","price":"16"}
											]}	    
		]';

/*
*/

//num系数量
$order = '[{"food_id":1,"num":2},{"food_id":3,"num":1},{"food_id":6,"num":2},{"food_id":7,"num":1}]';


/*
二.设计一个类Menu，实现以下功能：
1. 设定菜单，每个实例必须有且只有一个菜单(json字符串，结构如上题)
2. 方法calculate, 输入订单后(json字符串，结构如上题), 输出格价
3. 方法discount, 可设定折扣，输出格价时自动计算折扣
4. 静态方法counter。输出calculate方法被调用的次数
5. 将结果发送到247828058@qq.com，邮件标题写上姓名，谢谢！
*/

class Menu
{
    static private $counter  = 0;
    static private $discount = 0;
    static private $menu     = [];
    
    public function __construct (string $menu)
    {
        if ($menu)
        {
            self::$menu = json_decode($menu, TRUE);
        }
    }
    
    static public function calculate (string $order):float
    {
        $price = '0';
        
        $goods = json_decode($order, TRUE);
        
        if (self::$menu && $goods)
        {
            foreach ($goods AS $item)
            {
                foreach (self::$menu AS $menu)
                {
                    if (empty($menu['food']))
                    {
                        continue;
                    }
                    
                    foreach ($menu['food'] AS $food)
                    {
                        if ($food['food_id'] == $item['food_id'])
                        {
                            $price = bcadd($price, bcmul($food['price'], $item['num'], 2), 2);
                        }
                    }
                }
            }
        }
        
        if (self::$discount > 0)
        {
            $price = bcdiv(bcmul($price, self::$discount, 2), 10, 2);
        }
        
        self::$counter++;
        
        return $price;
    }
    
    static public function discount (int $discount = 0)
    {
        self::$discount = $discount;
    }
    
    static public function counter ():int
    {
        return self::$counter;
    }
}

$Menu = new Menu($menu);
$Menu->discount(5);
var_dump($Menu->calculate($order));
$Menu->discount(6);
var_dump($Menu->calculate($order));
$Menu->discount(7);
var_dump($Menu->calculate($order));
$Menu->discount(8);
var_dump($Menu->calculate($order));
echo $Menu->counter();
