<section class="fancy_container">    
    <div class="fancy_contain">
        <h4 class="poph2backgrnd"><?php echo "User Details";?></h4>
    </div>
    <section class="fancy_contain">
        <label>First Name</label>
        <span><?php echo $getData['User']['first_name']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Last Name</label>
        <span><?php echo $getData['User']['last_name']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Email</label>
        <span><?php echo $getData['User']['email']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Address 1</label>
        <span><?php echo $getData['User']['address_1']; ?></span>
    </section>
    <?php if($getData['User']['address_2'] != ""){ ?>
    <section class="fancy_contain">
        <label>Address 2</label>
        <span><?php echo $getData['User']['address_2']; ?></span>
    </section>
    <?php } ?>
    <section class="fancy_contain">
        <label>Country</label>
        <span><?php echo $getData['Country']['name']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>State</label>
        <span><?php echo $getData['State']['name']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>City</label>
        <span><?php echo $getData['User']['city']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Zip</label>
        <span><?php echo $getData['User']['zip']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Phone Number</label>
        <span><?php echo $getData['User']['phone_no']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Gender</label>
        <span><?php
                    $gender = ($getData['User']['gender'] == 0) ? 'female' : 'male';
                    echo $this->Html->image("admin/".$gender.".png")." ".ucwords($gender);
                ?>
        </span>
    </section>
       
</section>
<script type="text/javascript">
$(document).ready(function(){
$(".fancy_container section:even").css("background-color", "#dedede");
$(".fancy_container section:odd").css("background-color", "#ffffff");
 

});
</script>