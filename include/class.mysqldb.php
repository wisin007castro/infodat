<?php
	class mysqldb {
			var $link;
			var $result;
		function connect($config) {
			$this->link = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['database']);
			if($this->link) {
				mysqli_query($this->link, "SET NAMES 'utf-8'");
				return true;
			}
			$this->show_error(mysql_error($this->link), "connect()");
			return false;
		}
		function selectdb($database) {
			if($this->link) {
				mysql_select_db($database, $this->link);
				return true;
			}
			$this->show_error("Not connect the database before", "selectdb($database)");
			return false;
		}
		function query($con, $sql) {
			$this->query = mysqli_query($con, $sql);
			return $this->query;
		}
		function fetch() {
			try {
				$result = mysqli_fetch_object($this->query);
			 	return $result;
			} catch (Exception $e) {
				return 0;
			}
		}
		function num_rows() {
			return $this->fetch()->num_rows; 
		}
		function show_error($errmsg, $func) {
			echo "<b><font color=red>" . $func . "</font></b> : " . $errmsg . "<BR>\n";
			exit(1);
		} 
	}

?>
