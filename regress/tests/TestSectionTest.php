<?php
/**
 * @author Rainer Spittel (rainer at silverstripe dot com)
 * @package openlayers
 * @subpackage tests
 */

class TestSectionTest extends SapphireTest {

	static $fixture_file = 'regress/tests/TestSectionTest.yml';
	
	/**
	 * Initiate the controller and page classes and configure GeoNetwork service
	 * to use the mockup-controller for testing.
	 */
	function setUp() {
		parent::setUp();
	}

	/**
	 * Remove test controller from global controller-stack.
	 */
	function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test duplicate with children (scenarios/steps).
	 */
	function testDuplicate_NoScenarios() {
		$page = new TestSection();
		$page->setField('Title','Test Page');
		$page->write();
		
		// check original configuration
		$this->assertEquals($page->Steps()->count(),0);
		$this->assertEquals($page->ID,1);
		
		$clone = $page->duplicateWithChildren();
		
		$this->assertEquals($clone->Steps()->count(),0);
		$this->assertEquals($clone->ID,2);
		
		// Expecting an empty array
		$cloneSteps = $clone->Steps()->toArray();
		$this->assertEquals($cloneSteps,Array());
	}


	/**
	 * Test duplicate with children (scenarios/steps).
	 */
	function testDuplicate() {
		$page = new TestSection();
		$page->setField('Title','Test Page');
		$page->write();
		
		// add first step
		$step1 = new TestStep();
		$step1->setField('Step','Scenario 1');
		$step1->write();		
		$page->Steps()->add($step1);

		// add second step
		$step2 = new TestStep();
		$step2->setField('Step','Scenario 1');
		$step2->write();
		$page->Steps()->add($step2);		
		$page->write();
		
		// check original configuration
		$this->assertEquals($page->Steps()->count(),2);
		$this->assertEquals($page->ID,1);
		$this->assertEquals($step1->ID,1);
		$this->assertEquals($step2->ID,2);
		
		$clone = $page->duplicateWithChildren();
		
		// Expecting an array with two scenarios (clones of the original one).
		$this->assertEquals($clone->Steps()->count(),2);
		$this->assertEquals($clone->ID,2);
		
		$cloneSteps = $clone->Steps()->toArray();
		$this->assertEquals($cloneSteps[0]->ID,3);
		$this->assertEquals($cloneSteps[1]->ID,4);
	}

}