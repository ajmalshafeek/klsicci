<?php
$config=parse_ini_file(__DIR__."/../../jsheetconfig.ini");
if(!isset($_SESSION))
{
    session_name($config['sessionName']);
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/store.php");
    if(!isset($_GET['oid'])){
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }
    $oid=$_GET['oid'];
    $detail= getOrderDetailById($oid);

    if(sizeof($detail)==0){
        echo sizeof($detail);
        header("Location:  https://" . $_SERVER['HTTP_HOST'] . $config['appRoot'] . "/client/store/store.php");
    }
} ?>
<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Invoice No <?php echo $_GET['oid']; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>

</head>
<body id="invoice">
  <center style="padding: 10px;font-weight: 700;">Invoice No <?php echo $_GET['oid']; ?></center>
<table width="100%" border="0" cellpadding="2" cellspacing="4">
  <tbody>
    <tr border="1px">
      <td width="50%"><img src="<?php echo "https://".$_SERVER['HTTP_HOST'].$config['appRoot']."/resources/".$_SESSION['orgLogo'].".png"; ?>" width="250px" alt=""/>
      </td>
      <td  width="50%" align="right">&nbsp;</td>
      <tr>
      </tr>
      <td colspan="2">
    <table width="100%"  border="1" cellpadding="4" cellspacing="0">
    <tr bgcolor="#6dff90">
      <td colspan="12">&nbsp;Order Details</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;Order No.</td>
      <td colspan="3">&nbsp;Order Date</td>
      <td colspan="3">&nbsp;Total Amount Total Amount</td>
      <td colspan="3">&nbsp;Status</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;<?php echo $detail['id']; ?></td>
<td colspan="3">&nbsp;<?php
    $date = date_create($detail['orderdate']);
    echo date_format($date, 'l d m Y');
    ?></td>
<td colspan="3">&nbsp;RM <?php echo $detail['grandtotal']?></td>
<td colspan="3">&nbsp;Order Received</td>
</tr>
</table>
</td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td width="50%" valign="top">
        <table width="100%"  border="1" cellpadding="4" cellspacing="0">
            <tr>
                <td colspan="2" bgcolor="#6be3fc" width="50%">&nbsp;Order Details</td>
            </tr>
            <tr>
                <td>&nbsp;Order Time</td>
                <td>&nbsp;<?php echo date_format($date, 'h:ia d-m-Y'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;Delivery Location</td>
                <td>&nbsp;<?php echo $detail['address']?></td>
            </tr>
        </table>
    </td>
    <td width="50%" valign="top">
        <table width="100%"  border="1" cellpadding="4" cellspacing="0">
            <tr>
                <td colspan="2" bgcolor="#fd7291" width="50%">&nbsp;Amount Details</td>
            </tr>
            <tr>
                <td>&nbsp;Total Item</td>
                <td>&nbsp;<?php echo getOrderItemCount($oid); ?></td>
            </tr>
            <tr>
                <td>Total(Incl. VAT)</td>
                <td>&nbsp;RM <?php echo $detail['grandtotal']?></td>
            </tr>
        </table></td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2"><table width="100%"  border="1" cellpadding="4" cellspacing="0">
            <tr bgcolor="#3665d2" style="color:#ffffff">
                <td width="50px">Sr. No.</td>
                <td width="80px" >Product</td>
                <td>Name</td>
                <td width="80px">Price</td>
                <td width="50px">Qty</td>
                <td width="80px">Total</td>
            </tr>
            <?php $itemlist=getOrderItemDetails($oid); ?>
            <?php
            $count=1;
            foreach($itemlist as $oitem){ ?>
                <tr>
                    <td>
                        <h5><?php echo $count; ?></h5>
                    </td>
                    <td><img src="<?php echo $oitem['img1'] ?>" alt="product" width="30px"></td>
                    <td>
                        <h5><?php echo $oitem['title']; ?></h5>
                    </td>
                    <td>
                        <h5><?php echo $oitem['price']?></h5>
                    </td>
                    <td>
                        <h5><?php echo $oitem['quantity']?></h5>
                    </td>
                    <td>
                        <h5>RM <?php echo $oitem['total']?></h5>
                    </td>
                </tr>
                <?php $count++; } ?>
        </table></td>
</tr>
</tbody>
</table>
<footer class="footer-part" style="margin-top: 20px">
    <center>This is a computer generated invoice does not required any signature</center>
    <center style="margin-bottom: 10px"> Copyright @ Jsoft Solution Sdn Bhd </center>
</footer>
<script type="text/javascript">
    $(document).ready(function(){
        //function printScreen() {
            window.print().setTime(500);
        window.history.back();
        //}
    });
</script>
</body>
</html>