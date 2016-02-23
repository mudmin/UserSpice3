<?php
/*
UserSpice 3
by Dan Hoover at http://UserSpice.com
Major code contributions by Astropos

a modern version of
UserCake Version: 2.0.2
UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels
*/

class Task {

  private $_db, $_data, $_taskLists, $_tasks;

  public function __construct($task=null){
    $this->_db = DB::getInstance();
  }

  public function taskById($id){
    $taskQ = $this->_db->query("SELECT * FROM tasks WHERE id = ?",array($id));
    $this->_data = $taskQ->first();
    return $_data;
  }

  public function taskListsByAccountId($id,$template = false){
    $table = ($template)?'template_task_lists':'task_lists';
    $taskTable = ($template)?'template_tasks':'tasks';
    $tlQ = $this->_db->query("SELECT * FROM {$table}
      WHERE account_id = {$id}
      ORDER BY sort",array($id));
    $this->_taskLists = $tlQ->results();
    foreach($this->_taskLists as $tl){
      $taskQ = $this->_db->query("SELECT * FROM {$taskTable} WHERE task_list_id = {$tl->id} ORDER BY sort");
      $tl->tasks = $taskQ->results();
    }
    return $this->_taskLists;
  }

  public function taskListsById($id,$template = false){
    $table = ($template)?'template_task_lists':'task_lists';
    $taskTable = ($template)?'template_tasks':'tasks';
    $tlQ = $this->_db->query("SELECT * FROM {$table}
      WHERE id = ?",array($id));
    $this->_taskLists = $tlQ->first();
      $taskQ = $this->_db->query("SELECT * FROM {$taskTable} WHERE task_list_id = {$this->_taskLists->id} ORDER BY sort");
      $this->_taskLists->tasks = $taskQ->results();
    return $this->_taskLists;
  }

  public function completeTask($task_id,$user_id,$complete=1){
    global $tz;
    $format = 'Y-m-d H:i:s';
    $dt = DateTime::createFromFormat($format,date($format));
    $this->_db->update('tasks',$task_id,array('completed'=>$complete,'complete_date'=>$dt->format($format),'completed_by'=>$user_id));
  }

  public static function renderTaskList($id,$template = false){
    $db = DB::getInstance();
    global $tz;
    $taskTable = ($template)?'template_tasks':'tasks';
    $taskListTable = ($template)?'template_task_lists':'task_lists';
    $taskListQ = $db->query("SELECT * FROM {$taskListTable} WHERE id = ?",array($id));
    $tl = $taskListQ->first();
    $tasksQ = $db->query("SELECT * FROM {$taskTable} WHERE task_list_id = {$tl->id} ORDER BY sort");
    $tasks = $tasksQ->results();
    ob_start();?>
    <ul class="task-list" id="task-list">
      <h4><?=$tl->name;?></h4>

      <?php foreach($tasks as $task):
          if(!$template){
            $dueT = new Carbon\Carbon($task->due_date);
            $dueT->tz($tz);
            if($dueT->isPast()){
              $status_class = ' color-band-red';
            }
            if($dueT->isToday()){
              $status_class = ' color-band-yellow';
            }
            if($dueT->isFuture()){
              $status_class = ' color-band-green';
            }
          }else{
            $status_class = ' color-band-green';
          }

        ?>
        <li class="task" id="ID_<?=$task->id;?>">
          <div class="task-handle">
            <div class="color-band<?=$status_class;?>"></div>
          </div>
          <div class="task-details">
            <div class="task-title"><a href="#" onclick="taskDetails('<?=$task->id;?>');return false;"><?=$task->name;?></a></div>
            <?php if(!$template):?>
              <div class="task-assigned">Curtis</div>
              <div class="task-due"><?=($template)?abrev_date($task->due_date,$tz):'';?></div>
              <div class="task-checkbox"><input type="checkbox" data-id="<?=$task->id;?>"></div>
            <?php else: ?>
              <div class="text-right">
                <a href="templates.php?task=edit&task_id=<?=$task->id;?>&task_list_id=<?=$tl->id;?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-pencil"></i></a>
                <a href="templates.php?task=delete&task_id=<?=$task->id;?>&task_list_id=<?=$tl->id;?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
              </div>
            <?php endif; ?>
            <div class="task-description">
              <?=$task->description;?>
            </div>
          </div>
          <div class="clearfix"></div>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php return ob_get_clean();
  }

}
