<?php

/**
 * Gantt module on_project_tabs event handler
 *
 * @package gantt
 * @subpackage handlers
 */

/**
 * Handle on projects tabs event
 *
 * @param NamedList $tabs
 * @param Project $project
 * @return null
 */
function gantt_handle_on_projects_tabs(WireframeTabs &$tabs, IUser &$logged_user) 
{
	$tabs->add('gantt', lang('Gantt'), Router::assemble('gantt'));
} // gantt_handle_on_project_tabs

?>