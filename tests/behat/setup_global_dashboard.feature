@local @local_dash_by_role
Feature: Add blocks to the global dashboard page
  As an admin, I can add a block for a specific role that will only be visible for this
  role.

  Background:
    Given the following "roles" exist:
      | name       | shortname |
      | Global student| globalstudent |
      | Global teacher| globalteacher |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | user1 | Student | 1 | student1@example.com |
      | user2 | Teacher | 1 | teacher1@example.com |
    And the following "role assigns" exist:
      | user    | role           | contextlevel | reference |
      | user1 | globalstudent        | System       |           |
      | user2 | globalteacher        | System       |           |
    Given the following config values are set as admin:
      | forcedefaultmymoodle    | 1 |

  @javascript
  Scenario: Add blocks to global dashboard
    When I log in as "admin"
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
    And I select "Global teacher" from the "roleid" singleselect
    And I should see "For teacher"
    And I should not see "For student"
    And I should see "For all"
    And I select "Global student" from the "roleid" singleselect
    And I should not see "For teacher"
    And I should see "For student"
    And I should see "For all"
    And I select "All roles" from the "roleid" singleselect
    And I should not see "For teacher"
    And I should not see "For student"
    And I should see "For all"
