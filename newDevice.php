
<?php session_start();?>

<!DOCTYPE html >
<html>
<head>
    <meta charset="UTF-8" />

    <style>

        label {
            display: inline-block;
            width:150px;
            height: 25px;

        }

        #part1  {
            margin:20px;
            border:20px solid purple;
            padding:20px;
            width: 550px;
            float: left;

        }


    </style>
    <script>

        function validateForm1() {

            document.getElementById("errorMessage").innerHTML = "";
            var valadd = valAddress("nAddress");

            //Insert you code here!!!

            if(!valadd)
            {
                document.getElementById("errorMessage").innerHTML="Network Address Wrong format";
                document.getElementById("nAddress").focus();
                return false;
            }

            var x=confirm("You are about to submit this form, continue?");
            if (x==true) {
                return true;
            }
            else {
                return false;
            }
        }
        function valAddress(a)
        {
            var format_address=/^(\d{1,3})[.](\d{1,3})[.](\d{1,3})[.](\d{1,3})$/;
            var address=document.getElementById(a).value;

            if (format_address.test(address) == false)
            {
                return false; //validation failed
            }
            return true;
        }
    </script>
</head>

<body>
<h1>New Device</h1>


<div id="part1">
    <form method="post" action="addDevice.php" onsubmit="return validateForm1()">

        <legend> New Device information </legend>

        <label>Device Name</label>
        <input type="text" name="dAddress" id="dAddress" size="40" maxlength="40"><br>

        <label>Device Address</label>
        <input type="text" name="dName" id="dName" size="40" maxlength="40"><br>

        <button type="submit" name="add_Device">Add Network</button>
        <button type="button" onclick="window.location.href='devices.php';">Cancel</button>

        <p id="errorMessage" style="color:red"></p>
    </form>
</div>


</body>
</html>


