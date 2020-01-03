    <!DOCTYPE html>
    <html lang="en">
        <head>
          
            <?php echo $this->Html->charset('UTF-8'); ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="">        
            <title>Bethel Trips</title>
            <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />                
            <?php
                echo $this->Html->css('bootstrap');   
                //echo $this->Html->css('font/css/font-awesome.min');
                echo $this->Html->script('jquery-1.7.2.min');
                //echo $this->Html->script('jquery.validate');
            ?>                
        </head>    
        <body>
            <section class="container wrapper">
                <div class="row">
                   
                    <div class="col-md-5 col-md-offset-4">
                        <div class="login-panel panel panel-default">
                            
                            <?php echo $this->fetch('content'); ?>
                        </div>
                    </div>
                </div>
                <div class="push"></div>
            </section>
            <?php //echo $this->element('admin/footer'); ?>  
        </body>
    </html>