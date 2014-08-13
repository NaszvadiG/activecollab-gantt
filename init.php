<?php

/**
 * Init gantt module
 * @package gantt
*/

define('GANTT_MODULE', 'gantt');
define('GANTT_MODULE_PATH', __DIR__);

AngieApplication::usePackage('database');

AngieApplication::setForAutoload(array(
	'GanttFilters' => GANTT_MODULE_PATH . '/models/gantt/GanttFilters.class.php'
));