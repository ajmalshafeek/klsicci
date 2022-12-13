<?php


	
	function DynamicBindVariables($stmt, $params)
	{
		if ($params != null)
		{
			$types = '';
			foreach($params as $param)
			{
				if(is_int($param)) {
					$types .= 'i';
				} elseif (is_float($param)) {
					$types .= 'd';
				} elseif (is_string($param)) {
					$types .= 's';
				} else {
					$types .= 'b';
				}
			}
	  
			$bind_names[] = $types;
	  
			for ($i=0; $i<count($params);$i++)
			{
				$bind_name = 'bind' . $i;
				$$bind_name = $params[$i];
				$bind_names[] = &$$bind_name;
			}
			call_user_func_array(array($stmt,'bind_param'), $bind_names);
		}
		return $stmt;
	}
	
?>