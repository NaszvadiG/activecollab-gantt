<?php

/**
 * Grab the following controllers from AngieApplication
 * AKA activeCollab Core
 */
AngieApplication::useController('projects', SYSTEM_MODULE);
AngieApplication::useController('tasks', TASKS_MODULE);
AngieApplication::useController('milestones', SYSTEM_MODULE);
AngieApplication::useController('categories', SYSTEM_MODULE);

/**
 * Gantt controller
 *
 * @package gantt
 * @subpackage controllers
 */
class GanttController extends ProjectsController {
  
    /**
     * Active module
     *
     * @var string
     */
    var $active_module = GANTT_MODULE;
    
    /**
	 * Construct GanttController controller
	 *
	 * @param Request $parent
	 * @param null $context
	 */
	public function __construct(Request $parent, $context = null) 
	{
		parent::__construct($parent, $context);
	} // __construct

    /**
	 * Prepare Controller
	 */
	public function __before() 
	{
		parent::__before();

	  $this->wireframe->breadcrumbs->add('charts', lang('Gantt'), Router::assemble('gantt'));
	  $this->wireframe->tabs->setCurrentTab('gantt');
      $this->smarty->assign(array(
        'active_user'  => $this->active_user,
        'logged_user'  => $this->logged_user,
        'active_file'  => $this->active_file,
      ));
    } // __construct
	
	private function _sendResponse($code, $data) 
	{
		header('Content-Type: application/json');
		if ($code == 404) header("HTTP/1.0 404 Not Found");
		if ($code == 403) header("HTTP/1.0 403 Forbidden");
		die(json_encode($data));
	}
	
	private function _get_categories() 
	{
		$categories_array = array();
		$categories = Categories::getIdNameMap(null, 'ProjectCategory');
		foreach ($categories as $id => $category) {
			$categories_array[$id] = $category;
		}
		
		return $categories_array;
	}
	
	private function _get_companies()
	{
		$companies_array = array();
		$companies = Companies::findActive($this->logged_user);
		foreach ($companies as $company) {
			$companies_array[$company->getId()] = $company->getName();
		}
		
		return $companies_array;
	}
	
	private function _get_project_custom_fields()
	{
		$custom_fields = array();
		$i=1;
		foreach(CustomFields::getEnabledCustomFieldsByType('Project') as $field_name => $details) {
			$custom_fields['field_' . $i] = array('name' => $details['label'], 'values' => array());
			$i++;
		}
		
		$projects = Projects::findActiveByUser($this->logged_user, true);
		$projects_array = array();
		foreach ($projects as $project) {
			if (isset($custom_fields['field_1'])) {
				if (!in_array($project->getCustomField1(), $custom_fields['field_1']['values'])) {
					$custom_fields['field_1']['values'][] = $project->getCustomField1();
				}
			}
			if (isset($custom_fields['field_2'])) {
				if (!in_array($project->getCustomField2(), $custom_fields['field_2']['values'])) {
					$custom_fields['field_2']['values'][] = $project->getCustomField2();
				}
			}
			if (isset($custom_fields['field_3'])) {
				if (!in_array($project->getCustomField3(), $custom_fields['field_3']['values'])) {
					$custom_fields['field_3']['values'][] = $project->getCustomField3();
				}
			}
		}
		
		return $custom_fields;
	}
	
	public function get_filters()
	{
		$data = array('categories' => $this->_get_categories(), 'companies' => $this->_get_companies(), 'custom_fields' => $this->_get_project_custom_fields());
		
		$this->_sendResponse(200, $data);
	}
	
	public function get_projects() 
	{
		$projects = Projects::findActiveByUser($this->logged_user, true);
		$projects_array = array();
		foreach ($projects as $project) {
			$early_bound = null;
			$late_bound = null;
			
			$category_id = $project->getCategoryId();
			$category = Categories::findById($category_id);
			if ($category_id == null) {
				$category_id = 0;
			}
			$company_id = $project->getCompanyId();
			$company = Companies::findById($company_id);
			
			$customField1 = preg_replace("/[^A-Za-z0-9]/", '_', $project->getCustomField1());
			if (isset($_POST['custom_field_1'])) {
				if ($customField1 == null) $customField1 = 'None';
				if (!in_array($customField1, $_POST['custom_field_1'])) {
					continue;
				}
			}
				
			$customField2 = preg_replace("/[^A-Za-z0-9]/", '_', $project->getCustomField2());
			if (isset($_POST['custom_field_2'])) {
				if ($customField2 == null) $customField2 = 'None';
				if (!in_array($customField2, $_POST['custom_field_2'])) {
					continue;
				}
			}
			
			$customField3 = preg_replace("/[^A-Za-z0-9]/", '_', $project->getCustomField3());
			if (isset($_POST['custom_field_3'])) {
				if ($customField3 == null) $customField3 = 'None';
				if (!in_array($customField3, $_POST['custom_field_3'])) {
					continue;
				}
			}
			
			if (!in_array($category_id, $_POST['categories']) || !in_array($company_id, $_POST['companies'])) {
				continue;
			}
			
			$milestones = Milestones::findByProject($project, $this->logged_user);
			foreach ($milestones as $milestone) {
				if ($milestone->getDateField1() != null) {
					if ($milestone->getDateField1()->getTimestamp() < $early_bound || $early_bound == null) {
						$early_bound = $milestone->getDateField1()->getTimestamp();
					}
					$milestone_start_date = date('d-m-Y', $milestone->getDateField1()->getTimestamp());
				} else {
					$milestone_start_date = date('d-m-Y');
				}
				if ($milestone->getDueOn() != null) {
					if ($milestone->getDueOn()->getTimestamp() > $late_bound || $late_bound == null) {
						$late_bound = $milestone->getDueOn()->getTimestamp();
					}
					$milestone_end_date = date('d-m-Y', $milestone->getDueOn()->getTimestamp());
				} else {
					$milestone_end_date = date('d-m-Y');
				}
				
				$theMilestone = array(
					'id' => 'M' . $milestone->getId(), 
					'text' => 'Milestone: ' . $milestone->getName(), 
					'project_category_id' => $category_id, 
					'project_category' => (isset($category)) ? $category->getName() : null, 
					'parent' => 'P' . $project->getId(),
					'company' => $company->getName(), 
					'company_id' => $company_id, 
					'start_date' => $milestone_start_date, 
					'end_date' => $milestone_end_date, 
					'progress' => 0, 
					'type' => 'task', 
					'open' => $milestone->getCompletedOn() instanceof DateTimeValue ? false : true
				);
				
				if ($_POST['view_level'] == 'project-milestone' || $_POST['view_level'] == 'everything') {
					$projects_array[] = $theMilestone;
				}
			}
			
			if ($early_bound == null) {
				$early_bound = time();
			}
			if ($late_bound == null) {
				$late_bound = time();
			}
			
			$start_date = date('d-m-Y', $early_bound);
			$end_date = date('d-m-Y', $late_bound);
			$duration = ($late_bound - $early_bound) / 86400;
						
			$custom_fields = array();
			$i=1;
			foreach(CustomFields::getEnabledCustomFieldsByType('Project') as $field_name => $details) {
				if ($i == 1) {
					$custom_fields[] = array($details['label'] => $project->getCustomField1());
				} else if ($i == 2) {
					$custom_fields[] = array($details['label'] => $project->getCustomField2());
				} else if ($i == 3) {
					$custom_fields[] = array($details['label'] => $project->getCustomField3());
				}
				$i++;
			}
			
			$theProject = array(
				'id' => 'P' . $project->getId(), 
				'text' => $project->getName(), 
				'project_category_id' => $category_id, 
				'project_custom_fields' => $custom_fields, 
				'project_category' => (isset($category)) ? $category->getName() : null, 
				'company' => $company->getName(), 
				'company_id' => $company_id, 
				'start_date' => $start_date, 
				'duration' => $duration, 
				'end_date' => $end_date, 
				'progress' => $project->getPercentsDone(), 
				'type' => 'project', 
				'open' => $project->getCompletedOn() instanceof DateTimeValue ? false : true
			);
			
			$projects_array[] = $theProject;
			
			$ptasks = Tasks::findByProject($project, $this->logged_user);
			foreach($ptasks as $task) {
				$milestone = Milestones::findById($task->getMilestoneId());
				
				$task_start_date = $start_date;
				$task_end_date = $end_date;
				// We use the milestone to determine the start date... lets see if it exists
				// Lets set the end date if we have it as well, in case the task due date isn't set
				if ($milestone != null) {
					if ($milestone->getDateField1() != null) {
						$task_start_date = date('d-m-Y', $milestone->getDateField1()->getTimestamp());
					}
					if ($milestone->getDueOn() != null) {
						$task_end_date = date('d-m-Y', $milestone->getDueOn()->getTimestamp());
					}
				}
				
				if ($task->getDueOn() != null) {
					$end_date = date('d-m-Y', $task->getDueOn()->getTimestamp());
				}
				
				$theTask = array(
					'id' => 'T' . $task->getId(), 
					'text' => $task->getName(), 
					'project_category_id' => $category_id, 
					'project_custom_fields' => $custom_fields, 
					'project_category' => (isset($category)) ? $category->getName() : null, 
					'parent' => (($task->getMilestoneId() > 0) ? ('M' . $task->getMilestoneId()) : ('P' . $project->getId())),
					'company' => $company->getName(), 
					'company_id' => $company_id, 
					'start_date' => $task_start_date, 
					'end_date' => $task_end_date, 
					'progress' => 0, 
					'type' => 'task', 
					'open' => $task->getCompletedOn() instanceof DateTimeValue ? false : true
				);
				
				if ($_POST['view_level'] == 'everything') {
					$projects_array[] = $theTask;
				}
			}
		}
		
		$whole = array('data' => $projects_array);
		
		$this->_sendResponse(200, $whole);
	}
	
	public function get_saved_filters()
	{
		$this->_sendResponse(200, GanttFilters::findByUserId($this->logged_user->getId()));
	}
	
	public function get_filter_data()
	{	
		$filter_id = $this->request->getId('filter_id');
		$filter = GanttFilters::findById($filter_id);
		
		if ($filter == false) 
		{
			$this->_sendResponse(404, array('status' => 'error', 'message' => 'Filter not found'));
		}
		
		if ($filter->getUserId() != $this->logged_user->getId() && $filter->getVisibility() == 'private') 
		{
			$this->_sendResponse(403, array('status' => 'error', 'message' => 'Cannot get data about a filter that you do not own'));
		}
		
		$this->_sendResponse(200, array('visibility' => $filter->getVisibility(), 'data' => unserialize($filter->getData())));
	}
	
	function save_filter()
	{
		$filter_id = $this->request->getId('filter_id');
		
		$name = $_POST['name'];
		$data = serialize($_POST['data']);
		
		if ($filter_id == 0) 
		{
			$filter = new GanttFilters();
			
			if ($_POST['visibility'] == 'private') $filter->setPrivate();
			else $filter->setPublic();
			
			$filter->setName($name);
			$filter->setData($data);
			$filter->setUserId($this->logged_user->getId());
			
			$filter->save();
			
			$this->_sendResponse(200, array('status' => 'success', 'message' => 'Added new filter successfully'));
		} 
		else 
		{
			$filter = new GanttFilters($filter_id);
			
			if ($filter->getUserId() != $this->logged_user->getId()) 
			{
				$this->_sendResponse(403, array('status' => 'error', 'message' => 'Cannot save data about a filter that you do not own'));
			}
			
			$filter->setName($name);
			$filter->setData($data);
			
			if ($_POST['visibility'] == 'private') $filter->setPrivate();
			else $filter->setPublic();

			$filter->save();
			$this->_sendResponse(200, array('status' => 'success', 'message' => 'Updated filter successfully'));
		}
	}
	
	function delete_filter()
	{
		$filter_id = $this->request->getId('filter_id');
		$filter = GanttFilters::findById($filter_id);
		
		if ($filter == false) 
		{
			$this->_sendResponse(404, array('status' => 'error', 'message' => 'Filter not found'));
		}
		
		if ($filter->getUserId() != $this->logged_user->getId()) 
		{
			$this->_sendResponse(403, array('status' => 'error', 'message' => 'Cannot delete a filter that you do not own'));
		}
		
		$filter->delete();
		
		$this->_sendResponse(200, array('status' => 'success', 'message' => 'Deleted filter successfully'));
	}
	
    /**
     * Show index page
     *
     * @param void
     * @return null
     */
    function index() 
	{
        $this->response->assign(array('page_tab' => 'charts'));
	} // index
	
} // GanttController
