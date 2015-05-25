<?php
namespace Home\Model;
use Think\Model;
class PlaceModel extends Model {
    //定义不同表名
    protected $tableName = 'place';
    j
    protected $fields = array(
		'id',
		'placeNum',
		'province',
		'city',
	'_type'=>array(  
		'id' => 'double',
		'placeNum' => 'varchar',
		'province' => 'varchar',
		'city' => 'varchar'
	)) ;
}

