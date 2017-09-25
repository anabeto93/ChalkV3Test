Feature: hasUpdates api

  Scenario: test
    Given the database is purged
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale | token           |
      | 123-user | jean      | paul     | +33123213123 | en     | jean-paul-token |
    And there is a course with the following info
      | uuid      | uuid-course-1    |
      | title     | Course 1         |
      | updatedAt | 2017-07-20 10:00 |
      | size      | 889              |
    And this user is assigned to this course on "2017-07-20 10:00:00"
    And there is a course with the following info
      | uuid      | uuid-course-2    |
      | title     | Course 2         |
      | updatedAt | 2017-01-01 08:00 |
      | size      | 200              |
    And this user is assigned to this course on "2017-07-20 10:00:00"
    When I add "Authorization" header equal to "Bearer jean-paul-token"
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {
        "query": "query hasUpdates($dateLastUpdate: DateTime) { hasUpdates(dateLastUpdate: $dateLastUpdate) { hasUpdates, size } }",
        "variables": {"dateLastUpdate": "2017-07-21 12:00:00"}
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
        "data": {
          "hasUpdates": {
            "hasUpdates": false,
            "size": 0
          }
        }
      }
    """
    When I add "Authorization" header equal to "Bearer jean-paul-token"
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {
        "query": "query hasUpdates($dateLastUpdate: DateTime) { hasUpdates(dateLastUpdate: $dateLastUpdate) { hasUpdates, size } }",
        "variables": {"dateLastUpdate": "2017-07-12 12:00:00"}
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
        "data": {
          "hasUpdates": {
            "hasUpdates": true,
            "size": 1089
          }
        }
      }
    """
    When I add "Authorization" header equal to "Bearer jean-paul-token"
    And I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {
        "query": "query hasUpdates($dateLastUpdate: DateTime) { hasUpdates(dateLastUpdate: $dateLastUpdate) { hasUpdates, size } }",
        "variables": {"dateLastUpdate": null}
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
        "data": {
          "hasUpdates": {
            "hasUpdates": true,
            "size": 1089
          }
        }
      }
    """
