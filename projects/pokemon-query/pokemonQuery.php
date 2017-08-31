<?php
session_start();

if(!isset($_SESSION['username'])){
header("Location: loginOneBit.php");
}

echo "<fieldset id='logout'><legend>Welcome, " . $_SESSION['name'] . "</legend>";

require 'db_connection.php';
?>

<form method="post">
	<input type="submit" name="viewActivity" value="View Past Activity"/>
	
</form>
<?php

if (isset ($_POST['viewActivity'])){
	
	echo "<table border ='1px black' align = 'center' font-size='smaller'>";
	echo "<tr><td>Date</td><td>Time</td></tr>";

	$sql = "SELECT *
			FROM user_" . $_SESSION['username'] . " 
			ORDER BY time DESC"; 
		
			$stmt = $dbConn -> prepare($sql);
    		$stmt -> execute ();	
		
			while ($result = $stmt -> fetch()){
				$phpdate = strtotime( $result['time'] );
				$result['time'] = date( 'm/d/y g:i A', $phpdate );
				$arr = str_split($result['time'], 9);
				echo "<tr><td>" . $arr[0] . "</td>";
				echo "<td>" . $arr[1] . "</td></tr>";
		}
	
	echo "</table>";
	echo "<form method='post'><input type='submit' name='reset' value='Hide Table'/></br></br></br>";
			
}
?>
<form method="post">
	<input type="submit" name="changePW" value="Password Change"/>
	
</form>


<?php

if (isset ($_POST['changePW']))	{
	
	echo "New Password:";
	echo "<form method='post'>";
	echo "<input type='password' name='password'></br></br>";
	echo "Re-type Password:";
	echo "<input type='password' name='retype'>";
	echo "<input type='submit' name ='pwButton' value='Confirm'>";
	echo "</form></br></br>";
			
	
	
}		
if (isset ($_POST['pwButton'])){
			
		if ($_POST['password'] != null){
			
			if($_POST['password'] == $_POST['retype']){
			
			$sql = "UPDATE pokemon_user
					SET password = :password
					WHERE username = :username"; 
		
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute (array(":password"=>hash('sha1', $_POST['password']), ":username"=>$_SESSION['username'])) ;
			
			echo "Password successfully changed.</br></br>";
			}
			else {
				echo "Error: Password mismatch.</br></br>";
			}
		}
		else {
			echo "Error: Password cannot be blank.</br></br>";
		}
		
	}
		
		

		
?>


<form method="post" action="logoutOneBit.php" onsubmit="confirmLogout()">
<input type="submit" value="Logout" />

</fieldset>
</form>



<?php



//----------------------------- (1) .php code for preparing dropdown menu for Pokemon stat search -------------------------------
$sql = "SELECT name, pokemonId
        FROM pokemon_name 
        ORDER BY pokemonId ASC";

$stmt = $dbConn -> prepare($sql); //prepares a statement for execution and returns a statement object
$stmt -> execute(); //execute the prepared statement
$name = $stmt->fetchAll(); //store the obtained data into an array variable

//----------------------------- (2) .php code for preparing dropdown menu for types search -------------------------------
$sql = "SELECT typeName, typeId
        FROM pokemon_type 
        ORDER BY typeId ASC";

$stmt = $dbConn -> prepare($sql); //prepares a statement for execution and returns a statement object
$stmt -> execute(); //execute the prepared statement
$typeName = $stmt->fetchAll(); //store the obtained data into an array variable



//----------------------------- (1) .php code for data retrieval -------------------------------
if (isset ($_GET['pokemonId'])) {

	if ($_GET['pokemonId'] > 0){
   		$pokemonId = $_GET['pokemonId'];
   		$sql = "SELECT * 
           FROM pokemon_name  
           WHERE pokemonId = :pokemonId ";
      
   		$stmt = $dbConn -> prepare($sql);
   		$stmt -> execute( array (':pokemonId' => $pokemonId));
   		$pokemonInfo = $stmt->fetch();
   		$pokemonInfo['name'] = ucfirst(strtolower($pokemonInfo['name']));
   
//-------------- Every time a Pokemon whose location data does not exist gets chosen, create new table ------------

 	$sql2 = "CREATE TABLE IF NOT EXISTS " . $pokemonInfo['name'] . "_location (
  							postId int(4) NOT NULL AUTO_INCREMENT,
  							location text NOT NULL,
  							PRIMARY KEY (postId)
  							)";
  					
  		$stmt2 = $dbConn -> prepare($sql2);
		$stmt2 -> execute();	
					
	}


}


?>




<!DOCTYPE html>
<html lang="en">

<head>
	<?php
		require 'styles.css'; //need .css file
			
	?>
		
		
		

		
  <meta charset="utf-8">
  <title> One Bit - Pokemon Database</title>
</head>
<header class="main-header" role="banner">
  <img src="banner.png" alt="Banner Image"/>
</header>

<body>

   <div id ="wrapper">
<!--------------------- (1) Form for searching up stats for chosen Pokemon  -------------------------->
   <h2> Search Pokemon </h2>
   
 		<fieldset id="sortMethod">
 			
		<legend>Search Catalog</legend>
</br>
     <form>
         <select name="pokemonId",  align = "center">
           <option value="-1"> - Select Pokemon -</option>
           <?php
                foreach ($name as $pokemonName) {
                	$pokemonName['name'] = ucfirst(strtolower($pokemonName['name'])); //capitalizes first letter of each pokemon
                    echo "<option  value=" . $pokemonName['pokemonId'] . ">" . $pokemonName['name'] . "</option>";

                
                }         
             
           ?>   
         </select>
         <input type="submit" value="Get Pokemon Info!" />
     </form>
     
     
     
     
     
 <!--------------------- (1) .php code for printing out stats for desired Pokemon  -------------------------->    
			<?php
    
        if (isset($pokemonInfo) && !empty($pokemonInfo)) {
        	$pokemonInfo['name'] = ucfirst(strtolower($pokemonInfo['name'])); //capitalizes first letter of each pokemon
        	
   			echo "</br><table id = 'tableInfo', border='2', align = 'center', width='550', height='75'>";
			echo "<tr><td>#</td><td>Name</td><td>Height</td><td>Weight</td><td>Starting Exp</td></tr>";
			echo "<tr>";     
			
			echo "<td>" . $pokemonInfo['pokemonId'] . "</td>";
			echo "<td>" . $pokemonInfo['name'] . "</td>";
			echo "<td>" . $pokemonInfo['height']/10 . " m". "</td>";
			echo "<td>" . $pokemonInfo['weight']/10 . " kg". "</td>";
			echo "<td>" . $pokemonInfo['base_experience'] . "</td>";
			
			
	
			echo "</tr>";
			echo "</table></br>";


//---------------------  .php code for printing out text box form submission  --------------------------  
			
			echo "<form method='post'>";
			echo "<textarea name='location' rows=5 cols=60 placeholder='Enter Location Sited'></textarea><br />";
			echo "<input type='submit' name='addLocation' value = 'Submit to Database'>";
			
			echo "</form>";
			
			


//--------------------- .php code for adding location for Pokemon  --------------------------			
						
			if (isset ($_POST['addLocation'])) {
				if ($_POST['location'] != null){								
												
						
					$postId = $dbConn->lastInsertId();
				
					$sql = "INSERT INTO " . $pokemonInfo['name'] . "_location
            				(postId, location)
            				VALUES
            				(:postId, :location)";
    			
					$stmt2 = $dbConn -> prepare($sql);
    				$stmt2 -> execute (array (":postId" => $postId, ":location"  => $_POST['location']));
                            
					echo "Thank you for your entry!";
					
  					
  					}
				
				else{
						echo "You must enter location before submitting.";
					}
			
	
			}

//--------------------- .php code for when "Location button is pressed"  --------------------------			
			//if (isset ($_POST['viewLocation'])){

   				$sql = "SELECT * 
           				FROM " . $pokemonInfo['name'] . "_location"; 

					
				
				$stmt2 = $dbConn -> prepare($sql);
    			$stmt2 -> execute ();
				
				echo "</br></br><table id = 'tableInfo', border='2', align = 'center', width='500', height='100'>";
				echo "<tr><td>Entry</td><td>List of known locations for " . $pokemonInfo['name'] . ": </td></tr>";
				
				
				
				while($result = $stmt2 -> fetch()){
					
						echo "<tr>";
						echo "<td>" . $result['postId'] . "</td>";
						echo "<td>" . $result['location'] . "</td>";
						echo "</tr>";
					
					
				}

													
				
				echo "</table></br>";	
				
				//}
        }
    
    ?>
 
      
        
</div></br></br></br>
          
 <div id = "wrapper">         
      
    
    <!--------------------- (2) Form for searching up Pokemon that has the desired type -------------------------->
         <h2> Filter Results </h2>
        		<fieldset id="sortMethod">
			<legend>Pick Sorting Method</legend>
		<form>
			
		<input type="radio" name="sort" value="pokemonId" checked>Numerical</input>  
		<input type="radio" name="sort" value="name" >Alphabetical </input> 
		<input type="radio" name="sort" value="weight" >Size </input> 
				
		


		 </fieldset></br></br>
		
        <fieldset>
        	
		<legend>Pick Filter(s)</legend>
     
         <select name="typeId">
           <option value="-1"> - Select Type -</option>
           <?php
                foreach ($typeName as $type) {
                	$type['typeName'] = ucfirst(strtolower($type['typeName'])); //capitalizes first letter of each pokemon
                    echo "<option  value=" . $type['typeId'] . ">" . $type['typeName'] . "</option>";                
                }                     
           ?>   
         </select>
         
 
         


		
	

	
         <!--------------------- (3) Form for searching up Pokemon that has the desired size (weight)-------------------------->    

        	


         <select name="size">
           <option value="-1"> - Select Size -</option>
           <?php
				 echo "<option value='1'>Small</option>"; 
				 echo "<option value='2'>Medium</option>"; 
				 echo "<option value='3'>Large</option>"; 
				 echo "<option value='4'>Gigantic</option>";                 
           ?>   
         </select>
         
         <select name="exp">
           <option value="-1"> - Select Level -</option>
           <?php
				 echo "<option value='1'>Weak</option>"; 
				 echo "<option value='2'>Average</option>"; 
				 echo "<option value='3'>Strong</option>"; 
                 
           ?>   
         </select>
         
         <input type="submit" value="Search" /></br>
     
     
     
     
     
     </form> 
     
     </fieldset>    
     


 <!--------------------- (2) .php code for data retrieval and printing out Pokemon that has the desired type -------------------------->
     <?php
     
		$typeArr = array();
		$sizeArr = array();
		$expArr = array();
		$sizeTypeArr = array();
		$sizeChoice = array(0,"SMALL", "MEDIUM", "LARGE", "GIGANTIC");
		$expChoice = array(0,"WEAK", "AVERAGE", "STRONG");
		

	 
		if (isset ($_GET['typeId'])) {
     		
   			$typeId = $_GET['typeId'];
			
			$sort = $_GET['sort'];
			
			switch ($sort)
			{
			
				case 'pokemonId': 
  			$sql = "SELECT *
            		FROM pokemon_type, pokemon_name, pokemon_match          
            		WHERE typeId = :typeId 
		    		AND typeId = typeNum
		    		AND pokemon_name.pokemonId = pokemon_match.pokemonId    		
		    		"  ;		
					echo "</br><fieldset><legend>Sorted by ID:</legend>";
					break;
				
				
				case 'name': 
  			$sql = "SELECT *
            		FROM pokemon_type, pokemon_name, pokemon_match          
            		WHERE typeId = :typeId 
		    		AND typeId = typeNum
		    		AND pokemon_name.pokemonId = pokemon_match.pokemonId    		
		    		ORDER BY pokemon_name.name ASC	
		    		"  ;	
					echo "</br><fieldset><legend>Sorted by ALPHABET:</legend>";
				break;
				
				case 'weight': 
  			$sql = "SELECT *
            		FROM pokemon_type, pokemon_name, pokemon_match          
            		WHERE typeId = :typeId 
		    		AND typeId = typeNum
		    		AND pokemon_name.pokemonId = pokemon_match.pokemonId    		
		    		ORDER BY pokemon_name.weight ASC	
		    		"  ;	
				echo "</br><fieldset><legend>Sorted by WEIGHT:</legend>";
				break;
				
				default:
					break;
			}

        

     		$stmt = $dbConn -> prepare($sql);
   			$stmt -> execute( array (':typeId' => $typeId));
			
			$stmt2 = $dbConn -> prepare($sql);
   			$stmt2 -> execute( array (':typeId' => $typeId));

			if ($tableName = $stmt2->fetch()){					
						echo "<fieldset><legend align = 'center'>" .  strtoupper($tableName['typeName']) . "</legend>";
						if ($_GET['size'] >= 0 AND $_GET['exp'] >= 0){
									echo "<fieldset><legend align = 'center'>" .  $expChoice[$_GET['exp']] . "</legend>";
									echo "<fieldset><legend align = 'center'>" .  $sizeChoice[$_GET['size']] . "</legend>";
							}
							if ($_GET['size'] >= 0 AND $_GET['exp'] < 0){
								echo "<fieldset><legend align = 'center'>" .  $sizeChoice[$_GET['size']] . "</legend>";
							}
							elseif($_GET['exp'] >= 0 AND $_GET['size'] < 0){
								echo "<fieldset><legend align = 'center'>" .  $expChoice[$_GET['exp']] . "</legend>";
							}
							
							echo "<table id =" . "'".$tableName['typeName']. "'" . ", align = 'center', width = '100', height = '100'>";
									
						
				
							}

			
			
						$i = 0;
						
   						while ($pokemonName = $stmt->fetch()){  //type
   							
							if ($_GET['size'] >= 0 or $_GET['exp'] >= 0) {
							$typeArr[$i] = $pokemonName['name'];
							$i=$i+1;
							
							
							}
							
							else{
								
							echo "<tr>";	
							echo "<td><a href = http://pokemondb.net/pokedex/" . $pokemonName['name'] . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($pokemonName['name'])). "'</a></td>";		
							echo "</tr>";
						   		}				
						
					};
					
					
			
		
	}
		
		
		if(isset ($_GET['size']) and $_GET['size'] >=0) {
				
				$size = $_GET['size'];
				$sort = $_GET['sort'];
				

			
					switch ($size){
															
					case '1':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE weight < 66
					   	ORDER BY ". $sort;
						
						

					   
						break;
						
					case '2':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE weight > 66 AND weight < 400
					   	ORDER BY ". $sort;
					   
						break;
						
					case '3':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE weight > 400 AND weight < 1000
					   	ORDER BY ". $sort;
						

						break;
						
					case '4':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE weight > 1000
					   	ORDER BY ". $sort;

					   
						break;
				
					default:
						break;
				
					}
					
					

					
					$stmt3 = $dbConn -> prepare($sql);
   					$stmt3 -> execute();
					
					
					$i = 0;
					

					
				if ($_GET['typeId'] >= 0 AND $_GET['exp'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //size
														
							$sizeArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
						};		
				
														
					$i = 0;
					foreach($sizeArr as $value){
						
							if (in_array($value, $typeArr)){
							$sizeTypeArr[$i] = $value;
							$i=$i+1;																																														
							
							}															
								
						}
						
					}
					
				elseif($_GET['typeId'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //size
														
							$sizeArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
						};
						
							
					$i = 0;
					foreach($sizeArr as $value){
						
							if (in_array($value, $typeArr)){
								echo "<tr>";	
								echo "<td><a href = http://pokemondb.net/pokedex/" . $value . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($value)). "'</a></td>";		
								echo "</tr>";																			
							
							}
					
					}
					if (!array_intersect($typeArr, $sizeArr)){
							echo "<tr>";	
							echo "<td>No matching results.</td>";
							echo "</tr>";
							
						}
				}
				
				elseif($_GET['exp'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //size
														
							$sizeArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
							
						};
						
					echo "<fieldset><legend align = 'center'>" .  $expChoice[$_GET['exp']] . "</legend>";
					echo "<fieldset><legend align = 'center'>" .  $sizeChoice[$_GET['size']] . "</legend>";
						
					echo "<table id='size' align = 'center' width = '100'>";
					
					
				}
				
				else {
					echo "<fieldset><legend align = 'center'>" .  $sizeChoice[$_GET['size']] . "</legend>";				
					echo "<table id='size' align = 'center' width = '100'>";
					
					while ($pokemonName2 = $stmt3->fetch()){
					
					echo "<tr>";	
					echo "<td><a href = http://pokemondb.net/pokedex/" . $pokemonName2['name'] . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($pokemonName2['name'])). "'</a></td>";		
					echo "</tr>";
					}
				}

			
				
					
						
											
								
				}



	if(isset ($_GET['exp']) and $_GET['exp'] >=0) {
				
				$exp = $_GET['exp'];
				$sort = $_GET['sort'];
				

				
				switch ($exp){
															
					case '1':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE base_experience < 70
					   	ORDER BY ". $sort;
					   
						break;
						
					case '2':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE base_experience > 70 and base_experience < 175
					   	ORDER BY ". $sort;


						break;
						
					case '3':
						$sql = "SELECT *
					   	FROM pokemon_name
					   	WHERE base_experience > 175
					   	ORDER BY ". $sort;


						break;

				
					default:
						break;
				
					}
				
				$stmt3 = $dbConn -> prepare($sql);
   				$stmt3 -> execute();
				
				$i = 0;
				
				if ($_GET['size'] >= 0 AND $_GET['typeId'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //exp
														
							$expArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
						};	
						
					foreach ($sizeTypeArr as $value){
						if (in_array($value, $expArr)){
							echo "<tr>";	
							echo "<td><a href = http://pokemondb.net/pokedex/" . $value . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($value)). "'</a></td>";		
							echo "</tr>";
							
						}
						
					
							
						}	
					
					if (!array_intersect($sizeTypeArr, $expArr)){
							echo "<tr>";	
							echo "<td>No matching results.</td>";
							echo "</tr>";
					}
				}

				elseif ($_GET['size'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //exp
														
							$expArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
							
						};
						
					foreach ($expArr as $value){
						if (in_array($value, $sizeArr)){
							echo "<tr>";	
							echo "<td><a href = http://pokemondb.net/pokedex/" . $value . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($value)). "'</a></td>";		
							echo "</tr>";
						}
						
	
					}
					
					if (!array_intersect($expArr, $sizeArr)){
							echo "<tr>";	
							echo "<td>No matching results.</td>";
							echo "</tr>";
							
						}
					
					
				}
				
				elseif ($_GET['typeId'] >= 0){
					while ($pokemonName2 = $stmt3->fetch()){  //exp
														
							$expArr[$i] = $pokemonName2['name'];
							$i=$i+1;
							
						};
						
					foreach ($expArr as $value){
						if (in_array($value, $typeArr)){
							echo "<tr>";	
							echo "<td><a href = http://pokemondb.net/pokedex/" . $value . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($value)). "'</a></td>";		
							echo "</tr>";
						}

	
					}
					
					if (!array_intersect($expArr, $typeArr)){
							echo "<tr>";	
							echo "<td>No matching results.</td>";
							echo "</tr>";
							
						}
					
					
				}
				else{
					echo "<fieldset><legend align = 'center'>" .  $expChoice[$_GET['exp']] . "</legend>";
					echo "<table id='size' align = 'center' width = '100'>";	
					while ($pokemonName2 = $stmt3->fetch()){
					
						echo "<tr>";	
						echo "<td><a href = http://pokemondb.net/pokedex/" . $pokemonName2['name'] . "><input id = 'link' type= 'submit' value=". "'" . ucfirst(strtolower($pokemonName2['name'])). "'</a></td>";		
						echo "</tr>";
					}
				
				
				}
				
				
				
				
				
				
				
					
				
				
	}
			
		echo "</table>";
		//echo "</fieldset>";
		
		

		    
?>
		
  		





</fieldset>


</div></br></br></br></br></br></br>

<footer>
	<fieldset><legend>Team One Bit</legend> <a href= "homePage.html">Huy Nguyen</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="http://hosting.otterlabs.org/classes/dizo9615/CST336/Labs/index.html">Lawrence Dizon</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="http://hosting.otterlabs.org/khan1736/CST336/Assignments/Assign1/assign1.html">Seema Khan</a></fieldset>
	
</footer>

</body>
</html>