<?php

//exchange the selection
if (isset($_POST["swap"])) {
    $temp = $_POST["select2"];
    $_POST["select2"] = $_POST["select1"];
    $_POST["select1"] = $temp;
}

if (isset($_POST["convert"])) {
    $num = $_POST["num"];
    $select1 = $_POST["select1"];
    $select2 = $_POST["select2"];
    if ($num == 0) {
        $res = 0;
    } elseif (strpos($num, "/") or strpos($num, ".")) { //check if num is float or has . or /
        $floatnum = "please enter an integer number";
    } elseif (empty($num)) {
        $errempty = "please fill out the blank";
    } elseif ($select1 == "bin" and !preg_match("/^[0-1]*$/", $num)) {
        $errformat = "please enter the valid binary number";
    } elseif ($select1 == "oct" and !preg_match("/^[0-8]*$/", $num)) {
        $errformat = "please enter the valid octal number";
    } elseif ($select1 == "dec" and !preg_match("/^[0-9]*$/", $num)) {
        $errformat = "please enter the valid decimal number";
    } elseif ($select1 == "hex" and !preg_match("/^[0-9A-Fa-f]*$/", $num)) {

        $invalidhex = "please enter the vallid hex number";
    }
    //convert dec to bin
    elseif ($select1 == "dec" and $select2 == "bin") {
        $res = [];
        while (floor($num / 2) > 0) {
            $res[] = $num % 2;
            $num = floor($num / 2);
        }
        $res[] = 1; //adding 1 to end of the result
        $res = array_reverse($res);
        $res = implode("", $res);
    }
    //convert bin to dec
    elseif ($select1 == "bin" and $select2 == "dec") {
        $len = strlen($num);
        $num = str_split($num);
        $num = array_reverse($num);
        $res = 0;
        for ($i = $len - 1; $i >= 0; $i--) {
            $res += $num[$i] * pow(2, $i);
        }
    }
    //convert bin to oct
    elseif ($select1 == "bin" and $select2 == "oct") {
        $num = str_split($num);
        $numlen = count($num);
        $num = array_reverse($num);
        while ($numlen % 3 != 0) { //تا زماني كه طول عدد بر 3 بخش پذير نباشد به سمت چپ عدد صفر اضافه ميكند
            array_push($num, "0");
            $numlen = count($num);
        }
        $num = array_reverse($num);
        $num = implode("", $num);
        $pattern = ["/000/i", "/001/i", "/010/i", "/011/i", "/100/i", "/101/i", "/110/i", "/111/i"];
        $replacements = ["0", "1", "2", "3", "4", "5", "6", "7"];
        $res = preg_replace($pattern, $replacements, $num);
    } elseif ($select1 == "oct" and $select2 == "bin") { //convert oct to bin
        $pattern = ["/0/i", "/1/i", "/2/i", "/3/i", "/4/i", "/5/i", "/6/i", "/7/i"];
        $replacements = ["000", "001", "010", "011", "100", "101", "110", "111"];
        $res = preg_replace($pattern, $replacements, $num);
    }

    // convert bin to hex
    elseif ($select1 == "bin" and $select2 == "hex") {
        $res = [];
        $num = str_split($num);
        $numlen = count($num);
        if ($numlen % 4 != 0) {
            $num = array_reverse($num);
            while ($numlen % 4 != 0) { //تا زماني كه طول عدد بر 4 بخش پذير نباشد به سمت چپ عدد صفر اضافه ميكند0
                array_push($num, "0");
                $numlen = count($num);
            }
            $num = array_reverse($num);
        }
        $num = implode("", $num);

        $num = str_split($num, 4);
        foreach ($num as $key => $value) { //معادل هر 4 عدد را قرار ميدهد
            $pattern = ["/0000/i", "/0001/i", "/0010/i", "/0011/i", "/0100/i", "/0101/i", "/0110/i", "/0111/i", "/1000/i", "/1001/i", "/1010/i", "/1011/i", "/1100/i", "/1101/i", "/1110/i", "/1111/i"];
            $replacements = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"];
            $res[] = preg_replace($pattern, $replacements, $value);
        }
        $res = implode("", $res);

        if ($res[0] == "0") { //اگر عدد ما مثلا 
            //0A 
            //بود صفر را حذف ميكند
            $res = str_replace("0", "", $res);
        }
    }

    //convert hex to bin
    elseif ($select1 == "hex" and $select2 == "bin") {
        $numlen = strlen($num);
        $num = str_split($num); //convert $num to an array
        foreach ($num as $key => $value) {
            if ($value == "0") {
            } else { //if $value !=0 then exit the loop and save the $value key to the flag variable so the flag variable is the index of our first number
                $flag = $key;
                break;
            }
        }
        $newnum = [];
        for ($i = $flag; $i < $numlen; $i++) { //start from the flag and continue to the end of the $num and save them to the $newnum
            $newnum[] = $num[$i];
        }

        $newnum = implode("", $newnum);
        $pattern = ["/0/i", "/1/i", "/2/i", "/3/i", "/4/i", "/5/i", "/6/i", "/7/i", "/8/i", "/9/i", "/A/i", "/B/i", "/C/i", "/D/i", "/E/i", "/F/i"];
        $replacement = ["0000", "0001", "0010", "0011", "0100", "0101", "0110", "0111", "1000", "1001", "1010", "1011", "1100", "1101", "1110", "1111"];
        $res = preg_replace($pattern, $replacement, $newnum);
    }

    //convert dec to oct
    elseif ($select1 == "dec" and $select2 == "oct") {
        $res = [];
        while (ceil($num / 8) != 0) {
            $res[] = $num % 8;
            $num = floor($num / 8);
        }

        $res = array_reverse($res);
        $res = implode("", $res);
    }

    //convert oct to dec
    elseif ($select1 == "oct" and $select2 == "dec") {
        $res = [];
        $num = str_split($num);
        $num = array_reverse($num);
        foreach ($num as $key => $value) {
            $res[] = $value * pow(8, $key);
        }
        $res = array_sum($res);
    }

    //convert dec to hex
    elseif ($select1 == "dec" and $select2 == "hex") {
        $res = [];
        while (ceil($num / 16) != 0) {
            $res[] = $num % 16;
            $num = floor($num / 16);
        }

        foreach ($res as $key => $value) {
            if (in_array($value, ["10", "11", "12", "13", "14", "15"])) {
                $pattern = ["/10/i", "/11/i", "/12/i", "/13/i", "/14/i", "/15/i"];
                $replacement = ["A", "B", "C", "D", "E", "F"];
                $newvalue = preg_replace($pattern, $replacement, $value);
                $res[$key] = $newvalue;
            }
        }
        $res = array_reverse($res);
        $res = implode("", $res);
    }


    //convert hex to dec
    elseif ($select1 == "hex" and $select2 == "dec") {

        $num = str_split($num);

        foreach ($num as $key => $value) {
            if (in_array($num[$key], ["A", "B", "C", "D", "E", "F", "a", "b", "c", "d", "e", "f"])) {
                $pattern = ["/A/i", "/B/i", "/C/i", "/D/i", "/E/i", "/F/i"];
                $replacement = ["10", "11", "12", "13", "14", "15"];
                $num[$key] = preg_replace($pattern, $replacement, $value);
            }
        }

        $len = count($num);
        $num = array_reverse($num);
        $res = [];
        for ($i = 0; $i < $len; $i++) {
            $res[] = $num[$i] * pow(16, $i);
        }

        $res = array_sum($res);
    }

    //convert oct to hex
    elseif ($select1 == "oct" and $select2 == "hex") {

        $num = str_split($num);
        $res = [];

        //convert oct to bin
        foreach ($num as $key => $value) {
            $pattern = ["/0/i", "/1/i", "/2/i", "/3/i", "/4/i", "/5/i", "/6/i", "/7/i"];
            $replacement = ["000", "001", "010", "011", "100", "101", "101", "110", "111"];
            $res[] = preg_replace($pattern, $replacement, $value);
        }




        $res = implode($res);
        $len = strlen($res);
        $result = [];
        if ($len % 4 != 0) { //if $res not divisible on 4 add zero until it divisible
            $nzero = ceil($len / 4);
            for ($i = 0; $i < $nzero; $i++) {
                $result[] = 0;
            }
        }

        $result = implode($result);
        $result .= $res; //combine the zeros with binary number
        $res = $result;
        $res = str_split($res, 4);
        $num = [];

        // convert bin to hex
        foreach ($res as $key => $value) {
            $pattern = ["/0000/i", "/0001/i", "/0010/i", "/0011/i", "/0100/i", "/0101/i", "/0110/i", "/0111/i", "/1000/i", "/1001/i", "/1010/i", "/1011/i", "/1100/i", "/1101/i", "/1110/i", "/1111/i"];
            $replacements = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"];
            $num[] = preg_replace($pattern, $replacements, $value);
        }

        $flag = 0;
        if ($num[0] == "0") {  //delete the zeros before the numbers
            foreach ($num as $key => $value) {
                if ($value == "0" and $flag == 0) {
                    $num[$key] = "";
                }
                if ($value != "0") {
                    $flag = 1;
                }
            }
        }

        $res = implode($num);
    }

    //convert hex to oct
    elseif ($select1 == "hex" and $select2 == "oct") {

        //convert hex to bin
        $pattern = ["/0/i", "/1/i", "/2/i", "/3/i", "/4/i", "/5/i", "/6/i", "/7/i", "/8/i", "/9/i", "/A/i", "/B/i", "/C/i", "/D/i", "/E/i", "/F/i"];
        $replacement = ["0000", "0001", "0010", "0011", "0100", "0101", "0110", "0111", "1000", "1001", "1010", "1011", "1100", "1101", "1110", "1111"];
        $num = preg_replace($pattern, $replacement, $num);
        $num = str_split($num);
        if ($num[0] == "0") {
            foreach ($num as $key => $value) {
                if ($value == "0") {
                    $num[$key] = "";
                }
                if ($value != "0") {
                    break;
                }
            }
        }

        $num = implode($num);

        // convert bin to oct

        if (strlen($num) % 3 != 0) {
            $num = str_split($num);
            $num = array_reverse($num);
            while (count($num) % 3 != 0) {
                $num[] = "0";
            }
            $num = array_reverse($num);
            $num = implode($num);
        }

        $num = str_split($num, 3);
        $res = [];
        foreach ($num as $key => $value) {
            $pattern = ["/000/i", "/001/i", "/010/i", "/011/i", "/100/i", "/101/i", "/110/i", "/111/i"];
            $replacements = ["0", "1", "2", "3", "4", "5", "6", "7"];
            $res[] = preg_replace($pattern, $replacements, $value);
        }


        $res = implode($res);
    } else {
        $res = $num;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>تبدیل مبنای دهدهی به دودویی</title>
    <style>
        .container {
            width: 200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 50%;
            padding: 5px;
            margin-bottom: 10px;
            outline-color: green;
            border: 1px solid grey;
        }

        button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .swap {
            outline: none;
            border: none;
            background-color: white;
            width: 30px;
            height: 30px;

        }

        img {

            width: 45px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <h2 style="font-weight: bold;">تبدیل مبنا</h2>
        <?php if (isset($invalidhex)) { ?>
            <div class="alert alert-danger" style="width: 500px; margin: auto;">
                <?php echo $invalidhex; ?>
            </div>
            <br>
        <?php } ?>
        <?php if (isset($errempty)) { ?>
            <div class="alert alert-danger" style="width: 500px; margin: auto;">
                <?php echo $errempty; ?>
            </div>
            <br>
        <?php } ?>
        <?php if (isset($errformat)) { ?>
            <div class="alert alert-danger" style="width: 500px; margin: auto;">
                <?php echo $errformat; ?>
            </div>
            <br>
        <?php } ?>
        <?php if (isset($floatnum)) { ?>
            <div class="alert alert-danger" style="width: 500px; margin: auto;">
                <?php echo $floatnum; ?>
            </div>
            <br>
        <?php } ?>
        <form method="post">
            <input type="text" id="decimal" name="num" placeholder="عدد خود را وارد کنید">
            <br>
            <label for="select1">از :</label>
            <select id="select1" name="select1" class="form-select form-select-sm mt-3" style="width:47.5%;display: inline-block;">
                <option value="dec" <?php if (isset($_POST["select1"]) and $_POST["select1"] == "dec") {
                                        echo "selected";
                                    } ?>>مبناي ده</option>
                <option value="bin" <?php if (isset($_POST["select1"]) and $_POST["select1"] == "bin") {
                                        echo "selected";
                                    } ?>>مبناي دو</option>
                <option value="oct" <?php if (isset($_POST["select1"]) and $_POST["select1"] == "oct") {
                                        echo "selected";
                                    } ?>>مبناي هشت</option>
                <option value="hex" <?php if (isset($_POST["select1"]) and $_POST["select1"] == "hex") {
                                        echo "selected";
                                    } ?>>مبناي شانزده</option>
            </select>
            <br>


            <div>

                <button class="swap" name="swap"><img src="swap.png"></button>

            </div>

            <br>
            <label for="select2">به :</label>
            <select id="select2" name="select2" class="form-select form-select-sm mt-3" style="width: 47.5%;display: inline-block;">
                <option value="dec" <?php if (isset($_POST["select2"]) and $_POST["select2"] == "dec") {
                                        echo "selected";
                                    } ?>>مبناي ده</option>
                <option value="bin" <?php if (isset($_POST["select2"]) and $_POST["select2"] == "bin") {
                                        echo "selected";
                                    } ?>>مبناي دو</option>
                <option value="oct" <?php if (isset($_POST["select2"]) and $_POST["select2"] == "oct") {
                                        echo "selected";
                                    } ?>>مبناي هشت</option>
                <option value="hex" <?php if (isset($_POST["select2"]) and $_POST["select2"] == "hex") {
                                        echo "selected";
                                    } ?>>مبناي شانزده</option>
            </select>
            <br>
            <br>
            <button name="convert" style="width:50%;" class="btn btn-block btn-success">تبدیل</button>
        </form>

        <?php if (isset($res)) { ?>
            <div class="alert alert-primary" style="width: 500px; margin: auto;">
                <?php echo $res; ?>
            </div>
        <?php } ?>
    </div>
    </div>
</body>

</html>