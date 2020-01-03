<?php    
    class Transaction extends AppModel {
        var $name = 'Transaction';      
        
        
        
    
    
    
        
        public function checkUnique(){
            if(!empty($this->data['User']['id'])){
                $data = $this->find('first',array('conditions'=>array('User.email'=>$this->data['User']['email'],'User.id !='=>$this->data['User']['id'],'User.is_deleted'=>0)));
            }else{
                $data = $this->find('first',array('conditions'=>array('User.email'=>$this->data['User']['email'],'User.is_deleted'=>0)));    
            }
            
            if(!empty($data) && count($data) > 0){
                return false;
            }else{
                return true;
            }
        }
        /*
        public $belongsTo = array(
          'Trip' => array(
            'className' => 'Trip',
            
            'fields' => array('id', 'name', 'user_passcode', 'leader_passcode', 'edit_blocked','archived')
          )
        );*/
        
        public function saveMembers($trip_id, $data){
          $valstring = "";
          $total = count($data);
          $created = Date('Y-m-d H:i:s');
          $pp = 0;
          
          for($i=0;$i<$total;$i++){
            //echo "<br>Type".$data[$i]['type'];
            
            if(strtolower($data[$i]['type']) == "leader"){
              $data[$i]['type'] = 1;
            }else{
              $data[$i]['type'] = 0;
            }
            $string = implode ("','", $data[$i]);
            $string = "('".$string . "', $trip_id, '$created')";
            if($i == ($total-1)){
              
            }else{
              $string .= ",";
            }
            $valstring .= $string;
          }
          //echo "PP found".$pp;
         $this->query("DELETE FROM users where trip_id=$trip_id");
         $this->query("INSERT INTO users (first_name, middle_name, last_name, type, trip_id, created) values $valstring");
       // exi();
        }
        
    }
    
?>