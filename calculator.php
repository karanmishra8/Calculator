<?php
$currentValue = 0;

$input = [];


function getInputAsString($values){
	$o = "";
	foreach ($values as $value){
		$o .= $value;
	}
	return $o;
}


function calculateInput($userInput){
    // format user input
    $arr = [];
    $char = "";
    foreach ($userInput as $num){
        if(is_numeric($num) || $num == "."){
            $char .= $num;
        }else if(!is_numeric($num)){
            if(!empty($char)){
                $arr[] = $char;
                $char = "";
            }
            $arr[] = $num;
        }
    }
    if(!empty($char)){
        $arr[] = $char;
    }
    // calculate user input

    $current = 0;
    $action = null;
    for($i=0; $i<= count($arr)-1; $i++){
        if(is_numeric($arr[$i])){
            if($action){
                if($action == "+"){
                    $current = $current + $arr[$i];
                }
                if($action == "-"){
                    $current = $current - $arr[$i];
                }
                if($action == "x"){
                    $current = $current * $arr[$i];
                }
                if($action == "/"){
                    $current = $current / $arr[$i];
				}
				if($action == "%"){
                    $current = $current % $arr[$i];
				}
                $action = null;
            }else{
                if($current == 0){
                    $current = $arr[$i];
                }
            }
        }else{
            $action = $arr[$i];
        }
    }
    return $current;

}

$rep="";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['input'])){
        $input = json_decode($_POST['input']);
	}


    if(isset($_POST)){
		
        foreach ($_POST as $key=>$value){
			if($key == 'squareroot'){
				$currentValue = sqrt(floatval(getInputAsString($input)));
				$input = [];
				$input[] = $currentValue;
			 }
			 elseif($key == 'square'){
				$currentValue = pow(floatval(getInputAsString($input)),2);
				$input = [];
				$input[] = $currentValue;
			 }
            elseif($key == 'equal'){
               $currentValue = calculateInput($input);
               $input = [];
               $input[] = $currentValue;
            }elseif($key == "c"){
                $input = [];
                $currentValue = 0;
            }elseif($key == "back"){
                $lastPointer = count($input) -1;
                if(is_numeric($input[$lastPointer])){
                    array_pop($input);
                }
            }elseif($key != 'input'){
                $input[] = $value;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title>Simple Calculator</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

	<style>
	body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light gray background */
    margin: 0;
}

.main {
    margin:auto;
    width: 400px; /* Adjust the width of the calculator */
    padding: 20px;
    background-color: #fff; /* White background for calculator */
    border: 2px solid #ccc; /* Darker border for calculator */
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

input[type="text"], input[type="submit"], button {
    width: 60px; /* Standard width for buttons */
    height: 60px; /* Standard height for buttons */
    font-size: 18px;
    margin:5px;
    padding: 10px;
    border: none; /* Remove borders from buttons and input */   
    border-radius: 5px;
    cursor: pointer;
    background-color: gray; /* Background color same as body */
}

input[type="text"] {
    width: calc(100% - 20px); /* Adjusted width to accommodate padding */
    font-size: 18px;
    padding: 15px 10px; /* Increased padding for better spacing */
    margin-bottom: 10px;
    border: none; /* Remove border from input field */
    border-radius: 5px;
    box-sizing: border-box;
    text-align: right; /* Align text to the right for numerical input */
    outline: none; /* Remove outline when focused */
    transition: border-color 0.2s ease-in-out; /* Smooth transition for border color */
}

input[type="text"]:focus {
    border-color: #007bff; /* Change border color on focus for better visibility */
}

input[type="submit"]:hover, button:hover {
    background-color: #007bff; /* Blue color for hover state */
    color: white;
}

button {
    background-color: #28a745; /* Green color for operator buttons */
    width: 100%; /* Full width for operator buttons */
}

input[type="submit"][name="equal"] {
    background-color: #dc3545; /* Red color for equal button */
}

table {
    width: 100%;
    margin-top: 10px;
    text-align: center;
    border-collapse: collapse; /* Collapse borders between table cells */
}

table td {
    padding: 5px;
    border: none; /* Remove borders from table cells */
}
#op{
background-color:skyblue;
}
#c{ 
    background-color: green;
    color: white;
}
#red{
    background-color: red;
    color: #f8f9fa;
}
	</style>
</head>
<body>
	<div class="main">


<h3>Simple Calculator</h3>
<div style="border: 1px solid #ccc; border-radius: 3px; padding: 5px; display: inline-block ">
    <form method="post" id="form">
	<input style="padding: 3px; margin: 3px; min-height: 20px;" value="<?php echo getInputAsString($input);?>">
    <input class="form-control" type="hidden" name="input" value='<?php echo json_encode($input);?>'/>
	
    <input class="form-control" type="text" value="<?php echo $currentValue;?>"/>
    <table style="width:100%;">
        <tr>
            <td><input class="form-control" id="red" type="submit" name="c" value="C"/></td>
            <td><button type="submit" id="op"  name="modulus" value="%">&#37;</button></td>
			<td><button type="submit" id="op" name="divide" value="/">&#247;</button></td>
			<td><input class="form-control" id="op" type="submit" name="squareroot" value="√"/></td>
        </tr>
        <tr>
            <td><input class="form-control" type="submit" name="7" value="7"/></td>
            <td><input class="form-control" type="submit" name="8" value="8"/></td>
			<td><input class="form-control" type="submit" name="9" value="9"/></td>
			<td><input class="form-control" id="op" type="submit" name="square" value="^"/></td>
        </tr>
        <tr>
            <td><input class="form-control" type="submit" name="4" value="4"/></td>
            <td><input class="form-control" type="submit" name="5" value="5"/></td>
            <td><input class="form-control" type="submit" name="6" value="6"/></td>        
            <td><input class="form-control" id="op" type="submit" name="multiply" value="x"/></td>
        </tr>
        <tr>
            <td><input class="form-control" type="submit" name="1" value="1"/></td>
            <td><input class="form-control" type="submit" name="2" value="2"/></td>
            <td><input class="form-control" type="submit" name="3" value="3"/></td>
			<td><input class="form-control" id="op" type="submit" name="minus" value="-"/></td>
        </tr>
        <tr>
            <!-- <td><button class="btn btn-primary" type="submit" name="plusminus" value="plusminus">&#177;</button></td> -->
            <td><input class="form-control" type="submit" name="zero" value="0"/></td>
            <td><input class="form-control" type="submit" name="." value="."/></td>
			<td><input class="form-control"  id="c" type="submit" name="equal" value="="/></td>
			<td><input class="form-control" type="submit" id="op" name="add" value="+"/></td>
        </tr>
    </table>
    </form>
</div>
</div>

</body>
</html>
