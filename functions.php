<?

use Illuminate\Database\Capsule\Manager as DB;
$DB = new DB;

$DB->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => 'password',
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
$capsDBule->bootEloquent();


class phpUtils {

        /**
         * Call a multilevel item display
         * @param string $table
         * @param int|null  $parent_id
         * 
         */
        public function multilevelItem($table,$parent_id){

                if(is_null($parent_id)){
                        $lists = DB::table('users')->select('*')->where('parent',null)->get();
                }
                else{
                        $lists = DB::table('users')->select('*')->where('parent',$parent_id)->get();
                }
                foreach($lists as $item){
                        if(is_null($parent_id)){
                                echo '<li>'.$item->name.'</li>';
                        }
                        else{
                                echo '<li class="indent">'.$item->name.'</li>';
                        }
                        $this->multilevelItem($table,$parent_id);
                }
        }
        public function sendMail(){}
        public function itirator(){}
        public function randomNumStr(){}

}