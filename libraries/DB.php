<?php
/* Database class (frontend for PDO; supports replication for insert and updates)
 * 
 */
class DB {
	// PHP 5.4 and above, obviously:
	protected $db = [];
	public function __construct($params = []) {
		if(isset($params['database'])) {
  		// If we specify a 
  		foreach($params['database'] as $d) {
  			
			}
		}
	}
  public function addDB($d) {
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
  		trigger_error("Database not found!", E_USER_ERROR);
      return;
    }
  	// Now that we've finished with the foreplay, let's do a query!
		
	}
  protected function _selectdb() {
  	// TODO: Determine which DB has the least load, then select that one
    // Not a high priority; most people will only deploy with 1
    // Will probably not be included until I'm almost ready to call it "finished"
	}
}