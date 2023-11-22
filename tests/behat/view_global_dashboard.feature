@local @local_dash_by_role
Feature: View block on the global dashboard that are visible for my role.
  I can only view a block on the global dashboard that is visible for my current role.

  Background:
    Given the following "roles" exist:
      | name           | shortname     |
      | Global student | globalstudent |
      | Global teacher | globalteacher |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | student  | Student   | 1        | student1@example.com |
      | teacher  | Teacher   | 1        | teacher1@example.com |
    And the following "role assigns" exist:
      | user    | role          | contextlevel | reference |
      | student | globalstudent | System       |           |
      | teacher | globalteacher | System       |           |
    Given the following config values are set as admin:
      | enabledashbyrole | 1 |
      | forcedefaultmymoodle | 1 |

    And I log in as "admin"
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I select "Global student" from the "roleid" singleselect
    And I add the "Text" block
    And I configure the "(new text block)" block
    And I set the field "Content" to "First block content"
    And I set the field "Text block title" to "For student"
    And I press "Save changes"
    And I select "Global teacher" from the "roleid" singleselect
    And I add the "Text" block
    And I configure the "(new text block)" block
    And I set the field "Content" to "First block content"
    And I set the field "Text block title" to "For teacher"
    And I press "Save changes"
    And I select "All roles" from the "roleid" singleselect
    And I add the "Text" block
    And I configure the "(new text block)" block
    And I set the field "Content" to "First block content"
    And I set the field "Text block title" to "For all"
    And I press "Save changes"
    When I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    And I log out

  Scenario: I can only see block for my student role
    When I log in as "student"
    And I should not see "For teacher"
    And I should see "For student"
    And I should see "For all"

  Scenario: I can only see block for my teacher role
    When I log in as "teacher"
    And I should see "For teacher"
    And I should not see "For student"
    And I should see "For all"

  Scenario: I can only see block for all roles
    When I log in as "admin"
    And I should not see "For teacher"
    And I should not see "For student"
    And I should see "For all"
