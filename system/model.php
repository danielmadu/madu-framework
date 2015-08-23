<?php

/*
 * O nome da tabela não pode conter "_" (underline)
 * */

	class Model {
		protected $db;
		public $_tabela;
		public function __construct()
		{
			try{
			$this->db = new PDO('mysql:host=localhost;dbname=site;','root','');
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e){
				die('Ocorreu um erro na conex&atilde;o com o banco de dados: '.$e->getMessage());
			}
			$classe = get_class($this);
			$classe = explode('_', $classe);
			$tabela = $classe[0];
			$this->_tabela = $tabela;

		}
		/*
		 * @param Array $dados
		 * @return Array
		 *
		 * */

		public function insert(Array $dados){

			foreach ($dados as $key => $value) {
				//$campos[] = $key."='".$value."'";
				$campos[] = $key;
				$valores[] = addslashes($value);
				//$valores[] = ":{$key}";
				//$insere[":{$key}"] = $value;

			}

			$campos = implode(', ', $campos);
			$valores = "'".implode("','", $valores)."'";
			//$q = $this->db->prepare("INSERT INTO {$this->_tabela} ({$campos}) VALUES ({$valores})");
			//return $q->execute(array($insere));
			return $this->db->query("INSERT INTO {$this->_tabela} ({$campos}) VALUES ({$valores})");
			//return print_r($tabela);
		}

		public function read($where = null, $limit=null, $offset=null, $order=null){
			$where = ($where != null ? "WHERE {$where}" : '');
			$limit = ($limit != null ? "LIMIT {$limit}" : '');
			$offset = ($offset != null ? "OFFSET {$offset}" : '');
			$order = ($order != null ? "ORDER BY {$order}" : '');
			$q = $this->db->query("SELECT * FROM {$this->_tabela} {$where} {$order} {$limit} {$offset}");
			$q->setFetchMode(PDO::FETCH_ASSOC);
			return $q->fetchAll();
		}

		public function update(Array $dados ,$where = null){
			$where = ($where != null ? "WHERE {$where}" : '');
			foreach ($dados as $key => $value) {
				//$campos[] = $key."='".$value."'";
				$value = addslashes($value);
				$campos[] = "{$key}='{$value}'";
				//$campos[] = "{$key}=':{$key}'";
			}
			$campos = implode(', ', $campos);
			//$this->db->prepare("UPDATE {$this->_tabela} SET {$campos} {$where}");
			//return $this->db->exec(array($dados));
			return $this->db->query("UPDATE {$this->_tabela} SET {$campos} {$where}");
		}

		public function delete($where = null){
			$where = ($where != null ? "WHERE {$where}" : '');
			return $this->db->query("DELETE FROM {$this->_tabela} {$where}");

		}
	}
?>
