<?php
namespace App;
class Upload{
	public function __construct($uploads,$uploadDir,$pathss){
		
		// Split the string containing the list of file paths into an array 
		$paths = explode("###",rtrim($pathss,"###"));
		$uploadDir=$uploadDir."/";
		// Loop through files sent
		foreach($uploads as $key => $current)
		{
			// Stores full destination path of file on server
			$this->uploadFile=$uploadDir.rtrim($paths[$key],"\.");
			// Stores containing folder path to check if dir later
			$this->folder = substr($this->uploadFile,0,strrpos($this->uploadFile,"/"));
			$this->folder=$this->folder."/";
			// Check whether the current entity is an actual file or a folder (With a . for a name)
			if(strlen($current->getClientOriginalName())!=1)
				// Upload current file
				// if($this->upload($current,$this->uploadFile))
				// echo $this->folder;
				// 	// echo "The file ".$paths[$key]." has been uploaded\n";
				// else 
				// 	echo $this->folder;
				$this->upload($current,$this->uploadFile);
		}
	}
	
	private function upload($current,$uploadFile){
		// Checks whether the current file's containing folder exists, if not, it will create it.
		if(!is_dir($this->folder)){
			mkdir($this->folder,0700,true);
		}
		// Moves current file to upload destination
		// $curfile=pathinfo($current,PATHINFO_FILENAME);
		if($current->move($this->folder,$current->getClientOriginalName()))
			return true;
		else 
			return false;
	}
}
?>