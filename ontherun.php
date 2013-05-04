<?php
include('auth/config.php');
include('lib/antixss.php');
include('includes/common.php');

if (isset($_GET['t']) && isset($_GET['u'])) {
    $token=AntiXSS::setFilter(mysql_real_escape_string($_GET['t']), 'whitelist', 'string');
    $user=AntiXSS::setFilter(mysql_real_escape_string($_GET['u']), 'whitelist', 'string');
    $sql=sprintf(
        "SELECT uid, utoken, ulogin FROM cs_users
         WHERE ulogin='%s' AND utoken='%s'",
        $user, $token);
    $result=mysql_query($sql);
    $row=mysql_fetch_assoc($result);
    $token=$row['utoken'];
    $user=$row['ulogin'];
    $profileid=$row['uid'];
}

if (!isset($token) || !isset($user)) {
    redirect_to('auth/login.php');
}

if (isset($_POST['coffeetime']) && !empty($_POST['coffeetime'])) {
    $coffeedate=mysql_real_escape_string($_POST['coffeetime']);
    $coffeedate=AntiXSS::setFilter($coffeedate, "whitelist", "string");
    $sql=sprintf(
        "SELECT cid, cdate
         FROM cs_coffees
         WHERE cdate > (NOW() - INTERVAL '5:00' MINUTE_SECOND)
           AND (NOW() + INTERVAL '45:00' MINUTE_SECOND) > (cdate + INTERVAL '45' MINUTE_SECOND)
           AND cuid = %d",
        $profileid);
    $result = mysql_query($sql);
    if ($row = mysql_fetch_array($result)) {
        $message = array(
            'warning',
            sprintf(
                'Error: Your last coffee was at least not 5 minutes ago at %s. O_o',
                $row['cdate']));
    }
    else {
        $sql=sprintf(
            "INSERT INTO cs_coffees (cuid, cdate)
             VALUES (%d, '%s')",
            $profileid, $coffeedate);
        $result=mysql_query($sql);
        $message = array(
            'success',
            sprintf('Your coffee at %s has been registered!', $coffeedate));
    }
}
elseif (isset($_POST['matetime']) && !empty($_POST['matetime'])) {
    $matedate=mysql_real_escape_string($_POST['matetime']);
    $matedate=AntiXSS::setFilter($matedate, "whitelist", "string");
    $sql=sprintf(
        "SELECT mid, mdate
         FROM cs_mate
         WHERE mdate > (NOW() - INTERVAL '5:00' MINUTE_SECOND)
           AND (NOW() + INTERVAL '45:00' MINUTE_SECOND) > (mdate + INTERVAL '45' MINUTE_SECOND)
           AND cuid = %d",
        $profileid);
    $result = mysql_query($sql);
    if ($row = mysql_fetch_array($result)) {
        $message = array(
            'warning',
            sprintf(
                "Error: Your last mate was at least not 5 minutes ago at %s. O_o",
                $row['mdate']));
    }
    else {
        $sql=sprintf(
            "INSERT INTO cs_mate (cuid, mdate)
             VALUES (%d, '%s')",
            $profileid, $matedate);
        $result=mysql_query($sql);
        $message = array(
            'success',
            sprintf('Your mate at %s has been registered!', $matedate));
    }
}

include("header.php");

if (isset($message)) {
?>
<div class="white-box">
    <p class="<?php echo $message[0]; ?>"><?php echo $message[1]; ?></p>
</div>
<?php
}
?>
<script type="text/javascript">
    function pad(n) {
        return n<10 ? '0'+n : n;
    }
    function AddPostDataCoffee() {
        function coffeetime(d) {
            return d.getFullYear() + '-' +
               pad(d.getMonth() + 1) +'-' +
               pad(d.getDate()) + ' ' +
               pad(d.getHours()) + ':' +
               pad(d.getMinutes()) +':' +
               pad(d.getSeconds());
        }
        var d = new Date();
        document.getElementById('coffeetime').value = coffeetime(d);
        document.getElementById("coffeeform").submit();
    }
    function AddPostDataMate() {
        function coffeetime(d) {
            return d.getFullYear() + '-' +
                pad(d.getMonth() + 1) + '-' +
                pad(d.getDate()) + ' ' +
                pad(d.getHours()) + ':' +
                pad(d.getMinutes()) + ':' +
                pad(d.getSeconds());
        }
        var d = new Date();
        document.getElementById('matetime').value = coffeetime(d);
        document.getElementById("mateform").submit();
    }
</script>

<div class="white-box">
    <h2>On the run?</h2>
    <center>
        <form action="" method="post" id="coffeeform">
            <input class="imadecoffee" type="submit" value="Coffee!" id="coffee_plus_one" onclick="AddPostDataCoffee();" /><br />
            <input type='hidden' id='coffeetime' name='coffeetime' value='' />
        </form>
        <form action="" method="post" id="mateform">
            <input class="imademate" type="submit" value="Mate!" id="coffee_plus_one" onclick="AddPostDataMate();" /><br />
            <input type='hidden' id='matetime' name='matetime' value='' />
        </form>
    </center>
</div>
<?php
include('footer.php');
?>
