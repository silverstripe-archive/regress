<?php

class TestSectionPermissionTest extends FunctionalTest {
	
	static $fixture_file = 'regress/tests/TestSectionTest.yml';
	static $use_draft_site = true;

	function setUp() {
		parent::setUp();
	}
	
	function testTestSecionPermission_NotAuthenticated() {
		$page = new TestSection();
		$page->Title = 'Functional Test';
		$page->write();
		
		// get new created page
		// expect login page because user has not been logged in.
		$ret = $this->get('/feature/perform/'.$page->ID);
		
		$expectedFragment = '<form  id="MemberLoginForm_LoginForm" action="Security/LoginForm';
		$this->assertContains($expectedFragment, $this->content());

		$expectedFragment = '<label class="left" for="MemberLoginForm_LoginForm_Email">Email</label>';
		$this->assertContains($expectedFragment, $this->content());
	}

	function testTestSecionPermission_Administrator() {
		$page = new TestSection();
		$page->Title = 'Functional Test';
		$page->write();

		// log in as admin, expect perform test page
		$this->logInAs('admin');
		$this->assertTrue((bool)Permission::check("ADMIN"));
		
		$ret = $this->get('/feature/perform/'.$page->ID);
		
		$expectedFragment = '<title>Perform a Test: \' Functional Test\' (feature test)</title>';
		$this->assertContains($expectedFragment, $this->content());
	}

	function testTestSecionPermission_Tester1() {

		$page = new TestSection();
		$page->Title = 'Functional Test';
		$page->CanViewType = 'OnlyTheseUsers';
		$page->write();
		
		// log in as tester1, expect permission denied (wrong username for that test)
		$this->logInAs('tester1');
		
		$ret = $this->get('/feature/perform/'.$page->ID);
		
		$expectedFragment = 'Permission denied.';
		$this->assertContains($expectedFragment, $this->content());
	}

	function testTestSecionPermission_Tester1_test2() {
		$group = $this->objFromFixture('Group','tester');

		$page = new TestSection();
		$page->Title = 'Functional Test';
		$page->CanViewType = 'OnlyTheseUsers';
		$page->write();

		$page->ViewerGroups()->add($group);
		$page->ViewerGroups()->write();
		
		// log in as tester1, expect permission denied (wrong username for that test)
		$this->logInAs('tester1');
		
		$ret = $this->get('/feature/perform/'.$page->ID);
		
		$expectedFragment = '<title>Perform a Test: \' Functional Test\' (feature test)</title>';
		$this->assertContains($expectedFragment, $this->content());
	}

}