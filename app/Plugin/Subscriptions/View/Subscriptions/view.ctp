<section class="fancy_container">    
    <section class="fancy_contain">
        <h2 class="poph2backgrnd"><?php echo "Subscription Plan Details";?></h2>
    </section>
    <section class="fancy_contain">
        <label>Name</label>
        <span><?php echo $data['Subscription']['name']; ?></span>
    </section>
    <section class="fancy_contain">
        <label>Description</label>
        <span><?php echo nl2br($data['Subscription']['description']); ?></span>
    </section>
    <section class="fancy_contain">
        <label>Frequency</label>
        <span><?php
                            $frequencyArray = array('1'=>'Weekly','2'=>'Monthly','3'=>'Yearly');
                            $freqIndex = $data['Subscription']['frequency'];
                       echo     $currFreq = $frequencyArray[$freqIndex];
                        ?></span>
    </section>
    <section class="fancy_contain">
        <label>Amount</label>
        <span><?php echo '$'.$data['Subscription']['amount']; ?></span>
    </section>
    
       
</section>