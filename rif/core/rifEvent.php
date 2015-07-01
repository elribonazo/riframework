<?
class rifEvent
{
    public $events = array();

    public function run($event, $args = array())
    {
        if(isset($this->events[$event]))
        {
            foreach($this->events[$event] as $func)
            {
                call_user_func($func, $args);
            }
        }

    }
    public function on($event, Closure $func)
    {
        $this->events[$event][] = $func;
    }
}
?>