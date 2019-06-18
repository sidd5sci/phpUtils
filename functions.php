<?
require '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
$DB = new DB;

$DB->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$DB->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$DB->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$DB->bootEloquent();


class phpUtils {

        /**
         * Call a multilevel item display
         * @param string $table
         * @param int|null  $parent_id
         * 
         */
		private function multilevelItem($list,$parent_id){
			$data = [];
			
			if(!$parent_id){
				$lists = DB::select('*')->where('parent',0)->get();
			}
			else{
				$lists = DB::select('*')->where('parent',$parent_id)->get();
			}
			foreach($lists as $item){
				if(!$parent_id){
					$block = [
						"name" => $item->name,
						"path" => $item->path,
						"indent" => 0,
						"id" => $item->id,
					];
				}
				elseif($item->parent == $parent_id){
					$block = [
						"name" => $item->name,
						"path" => $item->path,
						"indent" => 3,
						"id" => $item->id,
					];
				}
				$block["childs"] = $this->multilevelItem($item->id);
				$data[] = $block;
				// dd($c);
			}
			return $data;
		}
		/**
		 * file upload
		 */
		private function uploadFile($t_dir,$basename,$tempname,$name){
			
			// $c = explode('.',basename($_FILES['resume']["name"]);
			$target_dir = $t_dir;
			$target_file = "file_".time().'_'.$this->random_().'.'.$basename->getClientOriginalExtension();
			$uploadOk = 1;
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				// echo "<br>Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($tempname, $t_dir.$target_file)) {
				//   echo "<br>The file ". $basename. " has been uploaded.";
				return $target_file;
				} else {
				//   echo "<br>Sorry, there was an error uploading your file.";
				}
			}
		}
		/**
		 *  send mail
		 */
		private function sendMail($to,$from,$custom_from,$subject,$content,$senderName){
			
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// fetching email first
			$name = strval($senderName);
			// Create email headers
			$headers .= 'From: '.$name.' <'.$from.'>'."\r\n".
					'Reply-To: '.$custom_from."\r\n".
					'X-Mailer: PHP/' . phpversion();
			$headers .= "Content-Type: multipart/mixed"."\r\n"; // Defining Content-Type 
			$headers .= "Sender: ".$custom_from."\r\n";
			// $headers .= "boundary = $boundary\r\n"; //Defining the Boundary
			
			// Compose a simple HTML email message
			
			// Sending email
			if(mail($to, $subject, $content, $headers)){
				// echo '<br>Your mail has been sent successfully.';
				return true;
			} else{
				//     echo '<br>Unable to send email. Please try again.';
				return false;
			}
		
		}
		/**
		 *  Checkbox group itrator
		 */
		function itirator($aDoor){
			$sequence = '';
			if(empty($aDoor)) {
				// echo("You didn't select any buildings.");
			} 
			else{
				$N = count($aDoor);
				//echo("You selected $N door(s): ");
				for($i=0; $i < $N; $i++){
					// echo($aDoor[$i] . " ");
					$sequence .= $aDoor[$i].', ';
				}
			}
			return $sequence;
		}
		/**
		 *  Random
		 */
		public function random_(){
			// Available alpha caracters
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

			// generate a pin based on 2 * 7 digits + a random character
			$pin = mt_rand(1000000, 9999999)
				. mt_rand(1000000, 9999999)
				. $characters[rand(0, strlen($characters) - 1)];

			// shuffle the result
			$string = str_shuffle($pin);
			return $string;
		}
		public function protectImgUrl($url,$id){
			$name = random_str(10);
			$name .= '.jpe';
			// https://www.codeproject.com/Articles/6814/Securing-image-URLs-in-a-website
			// https://stackoverflow.com/questions/5487116/restricting-images-from-direct-url-download
			// https://css-tricks.com/techniques-for-fighting-image-theft/
		}

		/**
		 * display number rows count ant anchor
		 * @param number | integer  
		 * @param anchor | integer
		 */
		// $data = array(
		// 	'conn' => $link,
		// 	'table' => $tbalename,
		// 	'abc' =>  $abc,
		// );
		public function displayRecords($start = 0,$number = 1,$anchor = 0,$args){
			if($anchor == 0){
				// select * from table limit $number
				// $q = "select * from $args[table] limit $number"
				$records = DB::select('*')->limit($number)->get();
				return $records;
			}
			elseif($anchor == 1){
				// select * from tbl_name order by id desc limit N
				$records = DB::select('*')->orderby('id','DESC')->limit($number)->get();
				return $records;
			}
			else{
				//SELECT * FROM `student` WHERE id > $start limit $number
				$records = DB::select('*')->where('id','>',$start)->limit($number)->get();
				return $records;
			}
		}
		
}