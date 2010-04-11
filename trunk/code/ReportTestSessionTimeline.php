<?php
require_once 'Zend/Date.php';

/**
 * Shows {@link TestSession} along a monthly time axis,
 * to ease review for module maintainers and release managers.
 * 
 * @see https://agilezen.com/project/5888/story/43
 */
class ReportTestSessionTimeline extends Controller {

	static $allowed_actions = array(
		'index',
		'dofilter',
		'FilterForm'
	);
	
	/**
	 * Relative date statement suitable for strtotime()
	 */
	static $default_timespan = '-6 months';
	
	function init() {
		parent::init();
		
		if(!Permission::check('ADMIN')) return Security::permissionFailure($this);
		
		Requirements::css('regress/css/ReportTestSessionTimeline.css');
	}
	
	function Link($action = null) {
		return Controller::join_links('ReportTestSessionTimeline', $action);
	}
	
	function index($request) {
		return $this->renderWith($this->class);
	}
	
	/**
	 * @return DataObjectSet
	 */
	function getTestPlans() {
		$currentPlan = $this->getCurrentTestPlanHolder();
		$plans = ($currentPlan) ? DataObject::get('TestPlan', sprintf('"ParentID" = %d', $currentPlan->ID)) : null;
		
		// TODO canView()

		return $plans;
	}
	
	function getTestPlansByInterval() {
		$intervals = $this->getIntervals();
		$startDate = $this->getStartDate();
		$currentPlan = $this->getCurrentTestPlanHolder();
		$plansByInterval = new DataObjectSet();
		
		// Chop up plans by interval
		foreach($intervals as $interval) {
			$plansForInterval = DataObject::get('TestPlan', 
				sprintf(
					'"ParentID" = %d', 
					$currentPlan->ID
				)
			);
			
			// Get customized session listing for all plans
			if($plansForInterval) foreach($plansForInterval as $plan) {
				$sessions = $plan->getAllSubmittedTestSessions(
					sprintf(
						'"Created" >= \'%s\'  AND "Created" < \'%s\'', 
						$interval->StartDate,
						$interval->EndDate
					),
					'"Created" DESC'
				);
				// $sort doesn't work that great...
				$sessions->sort('Created', 'DESC');
				$plan->TestSessionsByInterval = $sessions;
			}
			
			$plansByInterval->push(new ArrayData(array(
				'Interval' => $interval,
				'TestPlansForInterval' => $plansForInterval
			)));
		}

		return $plansByInterval;
	}
	
	/**
	 * @return new DataObjectSet
	 */
	function getIntervals($intervalType = null) {
		if(!$intervalType) $intervalType = $this->getIntervalType();
		
		$dateStart = $this->getStartDate();
		$dateNow = new Zend_Date();

		$intervals = new DataObjectSet();
		while($dateStart->compare($dateNow) == -1) {
			$oldDateStart = clone $dateStart;
			$dateStart->add(1, $intervalType);
			$intervals->push(new ArrayData(array(
				'StartTimestamp' => $oldDateStart->get(),
				'StartDate' => $oldDateStart->get(Zend_Date::ISO_8601),
				'EndTimestamp' => $dateStart->get(),
				'EndDate' => $dateStart->get(Zend_Date::ISO_8601),
				'Title' => $oldDateStart->get('M/Y')
			)));
		}
		// Its a waterfall, show newest first
		$intervals->sort('StartTimestamp', 'DESC');
		
		return $intervals;
	}
	
	/**
	 * @return TestPlan
	 */
	function getCurrentTestPlanHolder() {
		$planID = $this->request->getVar('TestPlanHolderID');
		if($planID) {
			$plan = DataObject::get_by_id('TestPlanHolder', $planID);
		} else {
			// Default to first
			$plan = DataObject::get_one('TestPlanHolder');
		}
		
		return $plan;
	}
	
	/**
	 * @return Zend_Date constant
	 */
	protected function getIntervalType() {
		return Zend_Date::MONTH;
	}
	
	/**
	 * Returns first of the month, rather than the actual current date.
	 * 
	 * @todo Make more flexible towards different intervals
	 * 
	 * @return Zend_Date
	 */
	protected function getStartDate() {
		$start = $this->request->getVar('StartDate');
		$startTs = ($start) ? $start : strtotime(self::$default_timespan);
		
		// Default to the first of the month (assuming monthly intervals)
		return new Zend_Date(array('day' => 1, 'month' => date('m', $startTs), 'year' => date('Y', $startTs)));
	}
	
	function FilterForm() {
		$currentPlan = $this->getCurrentTestPlanHolder();

		$holders = DataObject::get('TestPlanHolder');
		$holdersArr = ($holders) ? $holders->toDropdownMap() : false;
		$parentPlanDropdown = new DropdownField('TestPlanHolderID', false, $holdersArr);
		if($currentPlan) $parentPlanDropdown->setValue($currentPlan->ID);
		
		$form = new Form(
			$this,
			'FilterForm',
			new FieldSet(
				$parentPlanDropdown
			),
			new FieldSet(
				new FormAction('dofilter', 'Filter')
			)
		);
		$form->setFormMethod('GET');
		
		return $form;
	}
	
	function dofilter($data, $form) {
		return $this->index($this->request);
	}
}