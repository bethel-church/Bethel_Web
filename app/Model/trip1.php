<?php    
    class Trip extends AppModel {
        var $name = 'Trip';      
        
        public $validate = array(
        'trip_organization' => array(
            'rule'    => 'notEmpty',
            'required' => true,
            'message' => 'Please select organization.'
        ),
         
        'name' => array(
            'rule1' => array(
                'rule' => 'notEmpty',                
                'message'    => 'Please enter trip name.'                
            ),
            'rule2' => array(
                'rule' => 'alphaNumeric',                
                'message'    => 'Only characters and numbers are allowed within trip name.'
            ),
            'rule2' => array(
                'rule' => 'isUnique',                
                'message'    => 'There is already an existing trip with this Trip Name, please choose another name.'
            )
            
        ),  
        'leader_email' => array(
            'rule1' => array(
                'rule' => 'notEmpty',                
                'message'    => 'Please enter leader email.'                
            ),
            'rule2' => array(
                'rule' => 'email',                
                'message'    => 'Please enter valid leader email.'
            )
            
        ), 
        'user_passcode' => array(
            'rule'    => array('minLength', '4'),
            'message' => 'Please enter at least 4 characters passcode for students.'
        ),
               
        'leader_passcode' => array(
            'rule'    => array('minLength', '4'),
            'message' => 'Please enter at least 4 characters passcode for leaders.'
        ),
        'main_leader' => array(
          'rule' =>'notEmpty',
          'required' => true,
          'message' => 'Please select main leader of the trip.',
        )
        ,
        'csv' => array(
          'extension' => array(
                'rule' => array('chkFile'),
                'message' => 'Please select a valid csv file.',
                
            )
    ),
        /*,
        'csv' => array(
          'extension' => array(
                'rule' => array('extension', array('csv')),
                'message' => 'Please supply a valid csv file.',
                'required' => true,
                'on' => 'update'
            ),
            'extension2' => array(
                'rule' => array('extension', array('csv')),
                'message' => 'Please supply a valid csv file.',
                'required' => true,
                'on' => 'create'
            ),
    ),*/
        
    );
    
    public function checkEmptyFile(){
      if (!empty($this->data['Trip']['csv']['error'])){
        //pr($this->data);
      }
    }
    
    public function chkFile(){
      //pr($this->data);
      if (($this->data['Trip']['csv']['error']) == 0){
        $validfiles = array('application/octet-stream','application/csv','text/plain','text/csv','text/comma-separated-values');
          $type = $this->data['Trip']['csv']['type'];
          if($this->data['Trip']['csv']['error'] == 0){
            if(in_array($type, $validfiles)){
              $name = $this->data['Trip']['csv']['name'];
              $dd = explode(".",$name);
              $ext = end($dd);
              if($ext == "csv"){
                return true;
              }else{
                //echo "not valid file";exit();
                return false;
              }
            }
            return false;
          }else{
            return false;
          }
      }else{
        return true;
      }
    }
        
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
        
        public $hasMany = array(
          'User' => array(
            'className' => 'User',
            'conditions' => array('User.type' => 1),
            'fields' => array('id', 'first_name', 'middle_name', 'last_name', 'type','created')
          )
        );
        
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
            unset($data[$i]['temp_id']);
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
         public function archive($id){
          if($id !=""){
            $this->query("UPDATE trips set `archived`='1' where id='".$id."'");
            return true;
          }
        }
        public function unarchive($id){
          if($id !=""){
            //echo "UPDATE trips set `archived`='0' where id='".$id."'";exit();
            $this->query("UPDATE trips set `archived`='0' where id='".$id."'");
            return true;
          }
        }
        
        public function remove_validation(){
          $validator = new ModelValidator($this->Trip);
          $validator->remove("user_passcode");
          $validator->remove("leader_passcode");
          $validator->remove("main_leader");
          $validator->remove("csv");
        }
    }
    
?>