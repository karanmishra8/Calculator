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
            } elseif($key == 'square'){
                $currentValue = pow(floatval(getInputAsString($input)),2);
                $input = [];
                $input[] = $currentValue;
            } elseif($key == 'equal'){
                $currentValue = calculateInput($input);
                $input = [];
                $input[] = $currentValue;
            } elseif($key == "c"){
                $input = [];
                $currentValue = 0;
            } elseif($key == "back"){
                $lastPointer = count($input) -1;
                if(is_numeric($input[$lastPointer])){
                    array_pop($input);
                }
            } elseif($key != 'input'){
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <div class="main">
        <h3>Simple Calculator</h3>
        <div style="border: 1px solid #ccc; border-radius: 3px; padding: 5px; display: inline-block">
            <form method="post" id="form">
                <input style="padding: 3px; margin: 3px; min-height: 20px;" value="<?php echo getInputAsString($input); ?>">
                <input class="form-control" type="hidden" name="input" value='<?php echo json_encode($input); ?>'/>
                <input class="form-control" type="text" value="<?php echo $currentValue; ?>"/>
                <table style="width:100%;">
                    <tr>
                        <td><input class="form-control" id="red" type="submit" name="c" value="C"/></td>
                        <td><button type="submit" id="op" name="modulus" value="%">&#37;</button></td>
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
                        <td><input class="form-control" type="submit" name="zero" value="0"/></td>
                        <td><input class="form-control" type="submit" name="." value="."/></td>
                        <td><input class="form-control" id="c" type="submit" name="equal" value="="/></td>
                        <td><input class="form-control" type="submit" id="op" name="add" value="+"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
