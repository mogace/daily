<?php 
/**
 * Hash算法与数据库实现
 * 
 */

class HashTable{
	private $buckets;
	private $size = 10;

	public function __construct(){
		$this->buckets = [];
	}

	public function hashfunc($key){
		$strlen = strlen($key);
		$hashval = 0;

		for ($i=0; $i < $strlen ; $i++) { 
			$hashval += ord($key{$i});
		}

		return $hashval % $this->size;
	}

	public function insert($key, $val){
		$index = $this->hashfunc($key);
		$this->buckets[$index] = $val;
	}

	public function find($key){
		$index = $this->hashfunc($key);
		return $this->buckets[$index];
	}
}


$ht = new HashTable();
$ht->insert('key1', 'value1');
$ht->insert('key12', 'value12');
echo $ht->find('key1');
echo $ht->find('key12');

 ?>