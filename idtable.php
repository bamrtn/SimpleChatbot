<?php
class IDTable{
	public $idref = array();
	public $exist = false;
	
	function __construct(){
		for ($i = 0; $i < 10; $i++){
			$this-> idref[$i]=0;
		}
	}
	
	protected function strvalidate($s){
		if (gettype($s)!="string") return 0;
		if ($s == "" || $s == NULL) return 0;
		for ($i = 0; $i < strlen($s); $i++){
			if ($s[$i]==0 && $s[$i]!='0') return 0;
		}
		return 1;
	}
	
	protected function ins_rec($s){
		if ($this-> idref[$s[0]]===0) {
			$this-> idref[$s[0]] = new IDTable();
		}
		if (strlen($s)==1){
			$this-> idref[$s[0]]-> exist = true;
			return 1;
		}
		$this-> idref[$s[0]]-> ins_rec(substr($s,1));
		return 0;
	}
	
	protected function fin_rec($s){
		if ($this-> idref[$s[0]]===0) return 0;
		if (strlen($s)==1) return ($this-> idref[$s[0]]-> exist == true);
		return (
			$this-> idref[$s[0]]-> fin_rec(substr($s,1)) );
	}
	
	public function insert($s){
		if (gettype($s)=="integer") $s = strval($s);
		if ($this-> strvalidate($s)){
			$this-> ins_rec($s);
			return 1;
		}
		return 0;
	}
	
	public function find($s){
		if (gettype($s)=="integer") $s = strval($s);
		if ($this-> strvalidate($s)){
			return intval($this-> fin_rec($s));
		}
		return 0;
	}
	
	public function load($data){
		$temp = new IDTable();
		$temp-> exist = $data["exist"];
		for ($i = 0; $i < 10; $i++){
			if ($data["idref"][$i]!==0) 
				$temp-> idref[$i] = $this-> load($data["idref"][$i]);
		}
		return $temp;
	}
}
?>