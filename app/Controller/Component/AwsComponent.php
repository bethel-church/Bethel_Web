<?php
/**
 * Amazon S3 services Comonent.
 */
  
require 'aws/aws-autoloader.php';
use Aws\Common\Exception\RuntimeException;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;
use Aws\S3\S3Client;
use Aws\S3\Enum\Group;
use Aws\S3\Model\AcpBuilder;

class AwsComponent  extends Component {

/**
 * @var : name of bucket in which we are going to operate
 */ 	
	public $bucket = 'betheltripreceipts-bucket-s3.amazonaws.com';

/**
 * @var : Amazon S3Client object
 */ 	
	private $s3 = null;
	
	
	public function __construct(){
		
		$this->s3 = S3Client::factory(array(
			'key' => 'AKIAIUHULZOFGFXO7HSQ',
			'secret' => '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',
			'region' => 'us-west-1',
		));
		
	}
	
	
/**
 * @desc : to upload file on bucket with specified path
 * @param : keyname > path of file which need to be uploaded
 * @return : uploaded file object 
 * @created on : 14.03.2014
 */	

	public function uploadimg($keyname=null, $dest){
		try {
			$ret = $this->s3->putObject(array(
			    'Bucket'     => $this->bucket,
			    'Key'        => $dest,
			    'SourceFile' => $keyname,
			    'ACL'        => 'public-read'
			));

			return  $ret;
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;
		}
		
		return false; 	
	}

	public function getlistObjects($dest){
		$objects = $this->s3->getListObjectsIterator(array(
		    'Bucket' => $this->bucket,
		    'Prefix' => $dest.'/'
		));

		$filelist = array();
		foreach ($objects as $object) {
			array_push($filelist, $object);
		}
		
		return $filelist;
	}

	public function upload($keyname=null){
		try {
			$uploader = UploadBuilder::newInstance()
						->setClient($this->s3)
						->setSource($keyname)
						->setBucket($this->bucket)
						->build();
						
			return  $uploader->upload();

		} catch (MultipartUploadException $e) {
			if(Configure::read('debug')) echo 'S3 Exception :'.$e->getMessage() ;
			$uploader->abort();
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;
		}
		
		return false; 	
	}
	
	
/**
 * @desc : to delete multiple objects from bucket
 * @param : array(
				array('Key' => $keyname1),
				array('Key' => $keyname2),
				array('Key' => $keyname3),
			)
 * @return : boolean
 * @created on : 14.03.2014   
 */
	public function delete($objects=array()){
		try{
			return $this->s3->deleteObjects(array(
				'Bucket' => $this->bucket,
				'Objects' => $objects
			));
		} catch (RuntimeException $e) {
			if(Configure::read('debug')) echo 'RuntimeException Exception :'.$e->getMessage() ;
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;			
		}
		return false ;
	}
	
	
 /**
 * @desc : to empty specified folder
 * @param : folder to which you want to empty
 * @return : deleted file count
 * @created on :14.03.2014
 */    
   public function emptyFolder($folder=null,$regexp='/\.[0-9a-z]+$/'){
		try{
			return $this->s3->deleteMatchingObjects($this->bucket, $folder, $regexp);
			
		} catch (RuntimeException $e) {
			if(Configure::read('debug')) echo 'RuntimeException Exception :'.$e->getMessage() ;	
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;			
		}
		return false ;
	}
			
}
