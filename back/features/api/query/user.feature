Feature: User api
  Scenario: I can get all info of the logged user
    Given the database is purged
    And there is following users
      | uuid     | firstName | lastName | phoneNumber  | locale | multiLogin |
      | 123-user | jean      | paul     | +33123213123 | en     | 0          |
    And the api token for this user is "token1"
    And I add "Authorization" header equal to "Bearer token1"
    When I add "Content-Type" header equal to "application/json"
    And I send a POST request to "/api/graphql/" with body:
      """
      {"query": "query { user { uuid, firstName, lastName, country, countryCode, locale, phoneNumber }}", "variables": null}
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      {
          "data": {
              "user": {
                  "uuid": "123-user",
                  "firstName": "jean",
                  "lastName": "paul",
                  "country": "Ghana",
                  "countryCode": "GH",
                  "locale": "en",
                  "phoneNumber": "+33123213123"
              }
          }
      }
    """
