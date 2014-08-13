<?php

/**
 * GanttFilters model
 *
 * @package gantt
 * @subpackage models
 */
 
class GanttFilters extends ApplicationObject
{
	const PUBLIC_VISIBILITY = 'public';
	const PRIVATE_VISIBILITY = 'private';
	
	const TABLE_NAME = 'gantt_filters';
	
	/**
	 * Name of the filters table
	 * @var string
	 */
	protected $table_name = 'gantt_filters';
	
	/**
	 * Table fields of gantt_filters
	 * @var array
	 */
	protected $fields = array('id', 'name', 'visibility', 'data', 'user_id');
	
	/**
	 * Primary key
	 * @var array
	 */
	protected $primary_key = array('id');
	
	/**
	 * Auto increment fields
	 * @var string
	 */
	protected $auto_increment = 'id';
	
	/**
	 * Return name of the model
	 *
	 * @param boolean $underscore
	 * @param boolean $singular
	 * @return string
	 */
	function getModelName($underscore = false, $singular = false)
	{
		return $underscore ? 'gantt_filters' : 'GanttFilters';
	} // getModelName
	
	/**
	 * Return name of the table where system will persist model instances
	 *
	 * @param boolean $with_prefix
	 * @return string
	 */
	function getTableName($with_prefix = true)
	{
		return $with_prefix ? TABLE_PREFIX . $this->table_name : $this->table_name;
	} // getTableName
	
	/**
     * Return class name of a single instance
     *
     * @return string
     */
    static function getInstanceClassName()
    {
        return 'GanttFilters';
    } // getInstanceClassName

    /**
     * Return whether instance class name should be loaded from a field, or based on table name
     *
     * @return string
     */
    static function getInstanceClassNameFrom()
    {
        return DataManager::CLASS_NAME_FROM_TABLE;
    } // getInstanceClassNameFrom

    /**
     * Return name of the field from which we will read instance class
     *
     * @return string
     */
    static function getInstanceClassNameFromField()
    {
        return '';
    } // getInstanceClassNameFrom

    /**
     * Return name of this model
     *
     * @return string
     */
    static function getDefaultOrderBy()
    {
        return '';
    } // getDefaultOrderBy

	/**
	 * Get a filter object by the user ID
	 * 
	 * @param integer $user_id
	 * @return array or boolean
	 */
	static function findByUserId($user_id)
	{
		$filters = DB::execute('SELECT * FROM ' . TABLE_PREFIX . self::TABLE_NAME . ' WHERE (user_id=? AND visibility = "private") OR visibility = "public"', $user_id);
		if ($filters) {
			return $filters->toArray();
		} else {
			return false;
		}
	} // findByUserId
	
	/**
	 * Get a filter object by the ID
	 * 
	 * @param integer $id
	 * @return GanttFilter object or boolean
	 */
	static function findById($id)
	{
		$filters = DB::execute('SELECT * FROM ' . TABLE_PREFIX . self::TABLE_NAME . ' WHERE id=?', $id);
		if ($filters) {
			$filter = $filters->toArray();
			return new self($filter[0]['id']);
		} else {
			return false;
		}
	} // findById
	
	/**
	 * Set the GanttFilter to public visiblity
	 *
	 * @return boolean
	 */
	public function setPublic()
	{
		return $this->setFieldvalue('visibility', self::PUBLIC_VISIBILITY);
	} // setPublic
	
	/**
	 * Set the GanttFilter to private visiblity
	 *
	 * @return boolean
	 */
	public function setPrivate()
	{
		return $this->setFieldValue('visibility', self::PRIVATE_VISIBILITY);
	} // setPrivate
	
	/**
	 * Get the ID of the GanttFilter object
	 *
	 * @return integer
	 */
    public function getId()
    {
        return $this->getFieldValue('id');
    } // getId

	/**
	 * Set the ID of the GanttFilter object
	 *
	 * @param integer $value
	 * @return boolean
	 */
    public function setId($value)
    {
        return $this->setFieldValue('id', $value);
    } // setId

	/**
	 * Get the user ID of the GanttFilter object
	 *
	 * @return integer
	 */
	public function getUserId()
    {
        return $this->getFieldValue('user_id');
    } // getUserId

	/**
	 * Set the user ID of the GanttFilter object
	 *
	 * @param integer $value
	 * @return boolean
	 */
    public function setUserId($value)
    {
        return $this->setFieldValue('user_id', $value);
    } // setUserId
	
	/**
	 * Get the name of the GanttFilter object
	 *
	 * @return string
	 */
    public function getName()
    {
        return $this->getFieldValue('name');
    } // getName

	/**
	 * Set the name of the GanttFilter object
	 *
	 * @param string $value
	 * @return boolean
	 */
    public function setName($value)
    {
        return $this->setFieldValue('name', $value);
    } // setName
	
	/**
	 * Get the visibility of the GanttFilter object
	 *
	 * @return string
	 */
	public function getVisibility()
    {
        return $this->getFieldValue('visibility');
    } // getVisibility

	/**
	 * Set the visibility of the GanttFilter object
	 *
	 * @param string $value
	 * @return boolean
	 */
    public function setVisibility($value)
    {
        return $this->setFieldValue('visibility', $value);
    } // setVisibility
	
	/**
	 * Get the data of the GanttFilter object
	 *
	 * @return string
	 */
	public function getData()
    {
        return $this->getFieldValue('data');
    } // getData

	/**
	 * Set the data of the GanttFilter object
	 *
	 * @param string $value
	 * @return boolean
	 */
    public function setData($value)
    {
        return $this->setFieldValue('data', $value);
    } // setData
}
	