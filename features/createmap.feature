Feature: Create a map

    Scenario Outline: Create map
        Given there is a map repository
        When user asks for a new map from <filename>
        Then the map is created according to <filenameexpected>
        And user gets a response with id <expectedid>

        Examples:
            | filename              | filenameexpected      | expectedid    |
            | "france.json"         | "france.json"         | "france"      |
            | "hauteloire.json"     | "hauteloire.json"     | "hauteloire"  |