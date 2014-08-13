<?php

/**
 * GanttModule module definition
 * @package gantt
 */
class GanttModule extends AngieModule 
{
	/**
	 * Plain module name
	 * @var string
	 */
	var $name = 'gantt';

	/**
	 * Is system module flag
	 * @var boolean
	 */
	var $is_system = false;

	/**
	 * Module version
	 * @var string
	 */
	var $version = '1.0';

	/**
	 * Define module routes
	 * @param Router $r
	 * @return null
	 */
	function defineRoutes() 
	{
		Router::map('gantt', 'projects/gantt', array('controller' => 'gantt', 'action' => 'index'));
		Router::map('get_projects', 'projects/gantt/get_projects', array('controller' => 'gantt', 'action' => 'get_projects'));
		Router::map('get_filters', 'projects/gantt/get_filters', array('controller' => 'gantt', 'action' => 'get_filters'));
		Router::map('get_saved_filters', 'projects/gantt/get_saved_filters',  array('controller' => 'gantt', 'action' => 'get_saved_filters'));
		Router::map('save_filter', 'projects/gantt/save_filter/:filter_id', array('controller' => 'gantt', 'action' => 'save_filter'), array('filter_id' => Router::MATCH_ID));
		Router::map('get_filter_data', 'projects/gantt/get_filter_data/:filter_id', array('controller' => 'gantt', 'action' => 'get_filter_data'), array('filter_id' => Router::MATCH_ID));
		Router::map('delete_filter', 'projects/gantt/delete_filter/:filter_id', array('controller' => 'gantt', 'action' => 'delete_filter'), array('filter_id' => Router::MATCH_ID));
	} // defineRoutes

	/**
	 * Define event handlers
	 *
	 * @param EventsManager $events
	 * @return null
	 */
	function defineHandlers() 
	{
		EventsManager::listen('on_projects_tabs', 'on_projects_tabs');
	} // defineHandlers

	/**
	 * Get module display name
	 *
	 * @return string
	 */
	function getDisplayName() 
	{
		return lang('Gantt');
	} // getDisplayName

	/**
	 * Return module description
	 *
	 * @param void
	 * @return string
	 */
	function getDescription() 
	{
		return lang('A better Gantt chart for activeCollab!');
	} // getDescription

	/**
	 * Create the tables needed for saving filters
	 * in the Gantt Module
	 */
	function createTableGanttModuleFilters() 
	{
		$user_id = DBIntegerColumn::create('user_id');
		$newTable = DB::createTable(TABLE_PREFIX . 'gantt_filters')->addColumns(array(
			DBIdColumn::create(),
			DBStringColumn::create('name'),
			DBEnumColumn::create('visibility', array('public', 'private')),
			DBBinaryColumn::create('data'),
			$user_id
		));

		$userIdIndex = DBIndex::create('user_id', DBIndex::KEY, array($user_id));
		$newTable->addIndex($userIdIndex);
		$newTable->save();
	} // createTableGanttModuleFilters

	/**
	 * Execute after module installation (through the interface)
	 *
	 * @param User $user
	 */
	function postInstall(User $user)
	{
		parent::postInstall($user);
		$user->setSystemPermission('can_manage_gantt', true, false);
		$user->save();

		$this->createTableGanttModuleFilters();
	} // postInstall

	/**
	 * Uninstall the module
	 */
	function uninstall()
	{
		parent::uninstall();

		if (DB::tableExists(TABLE_PREFIX . 'gantt_filters')) 
		{
			DB::dropTable(TABLE_PREFIX . 'gantt_filters');
		}
	} // uninstall

	/**
	 * Return module uninstallation message
	 *
	 * @param void
	 * @return string
	 */
	function getUninstallMessage() 
	{
		return lang('Gantt module will be deactivated. You will lose any saved filters.');
	} // getUninstallMessage
}

?>
