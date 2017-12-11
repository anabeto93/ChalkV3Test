Feature: Question api
  Scenario: I can get questions of a session
    Given the database is purged
    And there is a course with the uuid "30575fe6-0bb6-4dfc-a38a-899e39bdf911" and the title "First course"
    And there is a session with the uuid "1231-123-123" and the title "Session title" for this course
    And there is a question with the title "What is the color of Henri IV white horse?" for this session
    And there is a correct answer with the title "white" for this question
    And there is an incorrect answer with the title "black" for this question
    And there is an incorrect answer with the title "brown" for this question
    And there is a question with the title "What is the capital city of Iceland?" for this session
    And there is an incorrect answer with the title "Paris" for this question
    And there is a correct answer with the title "Reykjavik" for this question
    And there is an incorrect answer with the title "Accra" for this question
    And there is an incorrect answer with the title "Berlin" for this question
    And there is a question with the title "What movies are Disney movies?" for this session
    And there is an incorrect answer with the title "Chihiro" for this question
    And there is a correct answer with the title "Frozen" for this question
    And there is an incorrect answer with the title "My Neighbor Totoro" for this question
    And there is a correct answer with the title "Mulan" for this question
    And there is following users
      | uuid       | firstName | lastName | phoneNumber    | locale  |
      | "123-user" | "jean"    | "paul"   | "+33123213123" | "en"    |
    And the api token for this user is "api-token-user"
    And this user is assigned to this course
    And I add "Authorization" header equal to "Bearer api-token-user"
    And I add "Content-Type" header equal to "application/json"
    When I send a POST request to "/api/graphql/" with body:
      """
      {
        "query":"query test($sessionUuid: String!) {session(uuid: $sessionUuid) {title, questions { title, isMultiple, answers { title }} }}",
        "variables":{"sessionUuid":"1231-123-123"}
      }
      """
    Then the response status code should be 200
    And the JSON should be equal to:
    """
        {
            "data": {
                "session": {
                    "title": "Session title",
                    "questions": [{
                            "title": "What is the color of Henri IV white horse?",
                            "isMultiple": false,
                            "answers": [{
                                    "title": "white"
                                },
                                {
                                    "title": "black"
                                },
                                {
                                    "title": "brown"
                                }
                            ]
                        },
                        {
                            "title": "What is the capital city of Iceland?",
                            "isMultiple": false,
                            "answers": [{
                                    "title": "Paris"
                                },
                                {
                                    "title": "Reykjavik"
                                },
                                {
                                    "title": "Accra"
                                },
                                {
                                    "title": "Berlin"
                                }
                            ]
                        },
                        {
                            "title": "What movies are Disney movies?",
                            "isMultiple": true,
                            "answers": [{
                                    "title": "Chihiro"
                                },
                                {
                                    "title": "Frozen"
                                },
                                {
                                    "title": "My Neighbor Totoro"
                                },
                                {
                                    "title": "Mulan"
                                }
                            ]
                        }
                    ]
                }
            }
        }
    """
