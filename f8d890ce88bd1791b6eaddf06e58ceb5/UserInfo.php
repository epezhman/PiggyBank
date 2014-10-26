<?php

function getCustomerId()
{
	try{
			
		// Connect to the database
		require_once("dbconnect.php");
			
			
		if(isset($_SESSION["username"]) and isset($_SESSION["loginstatus"]))
		{
			if($_SESSION["loginstatus"] == "authenticated")
			{
				$userID = NULL;
				$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
				$customerID = $dbConnection->prepare("SELECT customerID FROM Customer WHERE customerUsername LIKE (?)");
				$customerID->bind_param("s", $userUsername);
				$customerID->execute();
					
				$customerID->bind_result($ID);
					
				$customerID->store_result();
					
				if($customerID->num_rows() == 1)
				{
					while($customerID->fetch())
					{
						$userID = $ID;
					}
					$customerID->free_result();
					$customerID->close();

					return $userID;
				}
				return "";
			}
			else
				return "";
		}
		else 
			return "";
	}catch(Exception $e){
		return "";
	}
}

function getCustomerFullName()
{
	try{
			
		// Connect to the database
		require_once("dbconnect.php");
			
		session_start();

		if(isset($_SESSION["username"]) and isset($_SESSION["loginstatus"]))
		{
			if($_SESSION["loginstatus"] == "authenticated")
			{

				$userName = NULL;
				$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
				return $_SESSION['username'];
				$customerID = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerUsername LIKE (?)");
				$customerID->bind_param("s", $userUsername);
				$customerID->execute();
					
				$customerID->bind_result($name);
					
				$customerID->store_result();

				if($customerID->num_rows() == 1)
				{
					while($customerID->fetch())
					{
						$userName = $name;
					}
					$customerID->free_result();
					$customerID->close();

					return $userName;
				}
				return "";
			}
			else
				return "";
		}
		else 
			return "";
	}catch(Exception $e){
		return "";
	}
}

?>
