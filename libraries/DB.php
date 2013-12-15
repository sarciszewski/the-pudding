<?php
/* Database class (frontend for PDO; supports replication for insert and updates)
 * 
 */
class DB {
	// PHP 5.4 and above, obviously:
	public $db = [];
  public $errors = [];
	public function __construct($params = []) {
		if(isset($params)) {
  		// If we specify a 
  		foreach($params as $d) {
  			$this->addDB($d);
			}
		}
	}
  public function addDB($d) {
    if(!isset($d['id'])) {
			trigger_error("Database ".cleanOut($d['id'])." already exists!", E_USER_NOTICE);
    }
		if(isset($this->db[$d['id']])) { 
			trigger_error("Database ".cleanOut($d['id'])." already exists!", E_USER_NOTICE);
  		return;
  	}
  	switch($d['type']) {
  		case 'mysql':
  			$this->db[$d['id']] = new PDO("mysql:dbname=".$d['name'].";host=".$d['host'], $d['user'], $d['password']);
  				break;
  		case 'sqlite':
  			$this->db[$d['id']] = new PDO("sqlite:".$d['file']);
  			break;
  	}
  }
	// Execute a query, return an associative array of results
	public function query($statement, $params = [], $force_db = null) {
  	// Do we force the DB selection?
		if($force_db === null) {
  		switch(count($this->db)) {
  			case 0:
  				trigger_error("No databases initialized", E_USER_ERROR);
  				break;
  			case 1:
  				// Force the only one we have
  				$db = array_shift(array_keys($this->db));
  				return $this->query($statement, $params, $db);
  		}
  		$db =& $this->_selectdb();
  	} elseif(isset($this->db[$force_db])) {
      $db =& $this->db[$force_db];
		} else {
  		trigger_error("Database ".cleanOut($force_db)." not found!", E_USER_ERROR);
      return;
    }
  	// Now that we've finished with the foreplay, let's do a query!
    try {
  		if(!empty($params)) {
        // Prepare a statement
        $st = $db->prepare($statement);
        $pass = $st->execute($params);
        if(stripos($statement, 'SELECT') !== false) {
          return $st->fetchAll(PDO::FETCH_ASSOC);
        } else {
          return $pass;
        }
      } else {
        // No params, just do a normal query then
        $res = [];
        foreach($db->query($statement, PDO::FETCH_ASSOC) as $r) {
          $res[] = $r;
        }
        return $res;
      }
    } catch(Exception $e) {
      var_dump($e->getMessage());
    }
	}
  // Add a key to a table in each database
  public function insert($table, $properties = []) {
    $query = 'INSERT INTO '.$table.' (';
    $params = null;
    $post = [];
    if(!empty($properties)) {
      $query .= implode(', ', array_keys($properties));
      $params = array_values($properties);
      for($i = 0; $i < count($params); ++$i) {
        $post[] = '?';
      }
    }
    $query .= ') VALUES ('.implode(', ', $post).');';
    
    $success = true;
    foreach(array_keys($this->db) as $db) {
      $round = $this->query($query, $params, $db);
      if(!$round) {
        $success = false;
      }
    }
    return $success;
  }
  // Update table
  public function update($table, $properties, $where = []) {
    if(empty($properties)) {
      return false; // Nothing to update
    }
    $query = 'UPDATE '.$table.' SET ';
    $params = array_values($properties);
    $post = [];
    foreach(array_keys($properties) as $key) {
      $post[] = "{$key} = ?";
    }
    $query .= implode(', ', $post);
    if(empty($where)) {
      $query .= ' 1';
    } elseif(is_array($where)) {
      $query .= ' WHERE ';
      $params = array_values($properties);
      $post = [];
      foreach($properties as $key => $val) {
        $post[] = "{$key} = ?";
        $params[] = $val;
      }
      $query .= implode(' AND ', $post);
    } elseif(is_string($where)) {
      if(!strpos($where, 'WHERE ')) {
        $query .= ' WHERE ';
      }
      $query .= $where;
    }
    $success = true;
    echo "{$query}\n";
    var_dump($params);
    foreach(array_keys($this->db) as $db) {
      $round = $this->query($query, $params, $db);
      if(!$round) {
        $success = false;
      }
    }
    return $success;
  }
  protected function _selectdb() {
  	// TODO: Determine which DB has the least load, then select that one
    // Not a high priority; most people will only deploy with 1
    // Will probably not be included until I'm almost ready to call it "finished"
	}
}