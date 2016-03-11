<?php

define('DBCONNECT_SQLITE3_API', 0);
define('DBCONNECT_SQLITE3_CLASS', 1);
define('DBCONNECT_SQLITE3_PDO', 2);

class DBRes
{
  var $dbType;
  var $result;
  var $newsq3;

  function DBRes($pType, $pResult, $newsq3)
    {
      $this->dbType = $pType;
      $this->result = $pResult;
      $this->newsq3 = $newsq3;
    }
  
  function isValid()
    {
      return $this->result;
    }

  function nextObject()
    {   
      if($this->dbType == 'SQLITE3')
	{
	  die("NextObject : SQLITE3 : Not yet implemented");
	}
      else
	{
	  return mysql_fetch_object($this->result); 
	}
    }

  function nextRow()
    { 
      if($this->dbType == 'SQLITE3')
	{
	  if($this->newsq3 == DBCONNECT_SQLITE3_CLASS)
	    {
	      return $this->result->fetchArray(SQLITE3_NUM);
	    }
	  else if($this->newsq3 == DBCONNECT_SQLITE3_API)
	    {
	      return sqlite3_fetch($this->result);
	    }
	  else if($this->newsq3 == DBCONNECT_SQLITE3_PDO)
	    {
	      return $this->result->fetch(PDO::FETCH_OBJ);
	    }
	  else
	    {
	      die("DBRes:nextRow : Unmanaged sqlite3 version");
	    }
	}
      else
	{
	  return mysql_fetch_row($this->result);
	}
    }

  function nextAssoc()
    {   
      if($this->dbType == 'SQLITE3')
	{	  
	  if($this->newsq3 == DBCONNECT_SQLITE3_CLASS)
	    {
	      return $this->result->fetchArray(SQLITE3_ASSOC);
	    }
	  else if($this->newsq3 == DBCONNECT_SQLITE3_API)
	    {
	      return sqlite3_fetch_array($this->result);
	    }
	  else if($this->newsq3 == DBCONNECT_SQLITE3_PDO)
	    {
	      return $this->result->fetch(PDO::FETCH_ASSOC);
	    }
	  else
	    {
	      die("DBRes:nextAssoc : Unmanaged sqlite3 version");
	    }
	}
      else
	{
	  return mysql_fetch_assoc($this->result);
	}
    }

  function freeResult()
    {      
      if($this->dbType == 'SQLITE3')
	{
	  if(gettype($this->result) != 'boolean')
	    {
	      if($this->newsq3 == DBCONNECT_SQLITE3_CLASS)
		{
		  $this->result->finalize();
		}
	      else if($this->newsq3 == DBCONNECT_SQLITE3_API)
		{
		  sqlite3_query_close($this->result);
		} 
	      else if($this->newsq3 == DBCONNECT_SQLITE3_PDO)
		{
		  $this->result = null;
		}
	      else
		{
		  die("DBRes:freeResult : Unmanaged sqlite3 version");
		}
	    }
	}
      else
	{
	  mysql_free_result($this->result); 
	}
    }
}

/**
 * \class DBConnect
 * Manage a connection to a database
 */
class DBConnect 
{
  var $dbType;
  var $dbName;
  var $dbHost;
  var $dbUser;
  var $dbPasswd;
  
  var $connection = false;

  var $newsq3;

  function DBConnect($pType, $pHost, $pName,
		     $pUser, $pPasswd)
    {
      $this->dbType= $pType;
      $this->dbHost = $pHost;
      $this->dbName = $pName;
      $this->dbUser = $pUser;
      $this->dbPasswd = $pPasswd;
      $this->newsq3 = false;
    }

  function connectToDB()
    {  
      if($this->dbType == 'SQLITE3')
	{
	  if(function_exists('sqlite3_open'))
	    {
	      $this->newsq3 = DBCONNECT_SQLITE3_API;
	      $this->connection = sqlite3_open($this->dbName) ||
 		die("Couldn't connect to SQLITE3 Database");
	    } 
	  else 
	    {
	      try {
		$this->newsq3 = DBCONNECT_SQLITE3_PDO;
		$this->connection = new PDO('sqlite:' . $this->dbName);
	      } 
	      catch(PDOException $e) 
		{
		  $this->newsq3 = DBCONNECT_SQLITE3_CLASS;
		  $this->connection = new SQLite3($this->dbName, SQLITE3_OPEN_READWRITE);
		}
	      
	    }

	  if(!$this->connection)
	    {
	      die("DBConnect::connectToDB : Unmanaged sqlite3 version");
	    }
	}
      else
	{
	  ($this->connection = mysql_pconnect($this->dbHost, $this->dbUser, $this->dbPasswd)) || 
	    die("Couldn't connect to MySQL");
	  mysql_select_db($this->dbName, $this->connection) || 
	    die("Couldn't connect to Database " . $this->dbName);  
	}         
    }

  function describes($table)
  {
    $res = array();
    $result = null;
    if($this->dbType == 'SQLITE3')
      {
	$result = $this->query("pragma table_info('$table')");
	while($col = $result->nextAssoc())
	  {
	    $col["Field"] = $col["name"];
	    $res[] = $col;
	  }
	$result->freeResult();
      }
    else
      {
	$result = $this->query("show columns from $table");
	while($col = $result->nextAssoc())
	  {
	    $res[] = $col;
	  }
	$result->freeResult();
      }
    return $res;
  }

  function query($pQuery)
    {
      if($this->dbType == 'SQLITE3')
	{
	  $manips = 'INSERT|UPDATE|DELETE|REPLACE|'
	    . 'CREATE|DROP|'
	    . 'LOAD DATA|SELECT .* INTO|COPY|'
	    . 'ALTER|GRANT|REVOKE|'
	    . 'LOCK|UNLOCK';
	  if(preg_match('/^\s*"?(' . $manips . ')\s+/i', $pQuery))
	    {
	      $method = "sqlite3_exec";
	    }
	  else
	    {
	      $method = "sqlite3_query";
	    }
	  if($this->newsq3 == DBCONNECT_SQLITE3_CLASS || $this->newsq3 == DBCONNECT_SQLITE3_PDO)
	    {
	      if($method == "sqlite3_exec")
		{
		  $result = $this->connection->exec($pQuery);
		}
	      else
		{
		  $result = $this->connection->query($pQuery);
		}
	    }	  
	  else if($this->newsq3 == DBCONNECT_SQLITE3_API)
	    {
	      ($result = $method($this->connection, $pQuery)) ||
		die("Erreur de la requete ($pQuery)<br>".
		    "<b>Message de SQLITE3 :</b>".sqlite3_error($this->connection));
	    } 
	  else
	    {
	      die("DBConnect::query : Unmanaged sqlite3 version");
	    }
	}
      else
	{
	  ($result = mysql_query($pQuery, $this->connection))
	    or die("Erreur de la requete ($pQuery)<br>".
		   "<b>Message de MySQL :</b>".mysql_error($this->connection));
	}
      return new DBRes($this->dbType, $result, $this->newsq3);
    }
  
  function affectedRows()
    {
      if($this->dbType == 'SQLITE3')
	{
	  if($this->newsq3 == DBCONNECT_SQLITE3_CLASS)
	    {
	      return $this->connection->changes();
	    }
	  else
	    {
	      die("NextObject : SQLITE3 : Not yet implemented for this version of sqlite3");
	    }
	}
      else
	{
	  return mysql_affected_rows($this->connection);
	}
    }

  function closeDB()
    {
      if($this->dbType == 'SQLITE3')
	{
	  if($this->newsq3 == DBCONNECT_SQLITE3_CLASS)
	    {
	      $this->connection->close();
	    }
	  else if($this->newsq3 ==  DBCONNECT_SQLITE3_PDO)
	    {
	      $this->connection = null;
	    }
	  else if($this->newsq3 ==  DBCONNECT_SQLITE3_API)
	    {
	      sqlite3_close($this->connection);
	    }
	  else
	    {
	      die("DBConnect::closeDB : Umanaged sqlite3 version");
	    }
	}
      else
	{
	  mysql_close($this->connection);
	}
    }

  /**
   * delete all rows of a table
   * return the number of rows deleted
   */
  function clearTable($pTableName)
    {
      $query="DELETE FROM " . $pTableName;
      if($this->query($query))
	{
	  if($this->dbType == 'SQLITE3')
	    {
	      return -1;
	    }
	  else
	    {
	      return $this->affectedRows();
	    }
	}
      return 0;
    }

  function isTableExist($pTableName)
  {
    $query = false;
    if($this->dbType == 'SQLITE3')
      {
	$query = "select name from sqlite_master where name='$pTableName'";
      }
    else
      {
	  $query = "show tables like '$pTableName'";
      }
    
    if($query)
      {
	$result = $this->query($query);
	$obj = $result->nextAssoc();
	$result->freeResult();
	return $obj;
      }
    return false;
  }

  function insert($table, $data)
  {
    $cols = "(";
    $values = "values (";
    $datakeys = array_keys($data);
    $lastkey = end($datakeys);
    foreach($data as $k => $v)
      {
	$cols .= $k;
	$values .= "\"" . $v . "\"";
	if($lastkey != $k)
	  {
	    $cols .= ",";
	    $values .= ",";
	  }
      }
    $cols .= ")";
    $values .= ")";
    $query = "insert into $table $cols $values";
    return $this->query($query);
  }

  function update($table, $keys, $data)
  {
    $up = "";
    $wp = "";
    $firstKey = true;
    $firstData = true;
    foreach($data as $k => $v)
      {
	if(in_array($k, $keys))
	  {
	    if(!$firstKey)
	      {
		$wp .= " and ";
	      }
	    else
	      {
		$firstKey = false;
	      }
	    $wp .= "$k=\"$v\"";
	  }
	else
	  {
	    if(!$firstData)
	      {
		$up .= ", ";
	      }
	    else
	      {
		$firstData = false;
	      }
	    $up .= "$k=\"$v\"";
	  }
	}
    $query = "update $table set $up where $wp";
    return $this->query($query);
  }

  function delete($table, $keys, $data)
  {
    $cond = "";
    $lastKey = end($keys);
    foreach($keys as $k => $v)
      {
	$cond = "$v=\"" . $data[$v] . "\"";
	if($v != $lastKey)
	  {
	    $cond .= " and ";
	  }
      }
    $query = "delete from $table where $cond";
    return $this->query($query);
  }
}

?>
