Feature: currentDate api
  Scenario: I can get the current date of the server
    Given the database is purged
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale |
      | 123-user | jean      | paul     | +33123213123 | en     |
    And the api token for this user is "api-token-user"
    And I add "Authorization" header equal to "Bearer api-token-user"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { currentDate }", "variables": null}
      """
    Then the response status code should be 200