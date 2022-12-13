<p>Copyright @ Jsoft Solution Sdn Bhd</p>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  <?php /*  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/62986c9e7b967b1179928176/1g4hmgjr6';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })(); */ ?>
<?php if(isset($_SESSION['role'])&&$_SESSION['role']==1){?>
    setTimeout(function() {
        $('#mymodal').modal("show");
    }, 3000);
<?php } ?>
</script>


<!--End of Tawk.to Script-->
<?php if(isset($_SESSION['role'])&&$_SESSION['role']==1){?>
<div class="modal fade" id="mymodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close css-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img src="./img/popup.png" style="height: 80vh" ><br />
                <a href="https://gkacca.com/contact-us/" target="_blank" class="btn btn-dark popup-btn center" >JOIN NOW</a>
            </div>
        </div>
    </div>
</div>

<?php
    $_SESSION['role']=2;
} ?>