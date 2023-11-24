Feature: Search maps

    Background:
        Given some maps are defined by id and creation date:
            | id        | creation_time         |
            | second    | 17/10/2023 15:32:16   |
            | first     | 23/11/2024 01:02:03   |
            | fifth     | 01/01/2000 00:00:00   |
            | third     | 01/01/2023 00:00:01   |
            | fourth    | 01/01/2023 00:00:00   |
    
    Scenario: Sort only first map
        When user asks for the youngest map
        Then user receives it

    Scenario Outline: Sort only limited maps
        When user asks only for the <limit> youngest maps
        Then user receives <amount> maps in creation order

        Examples:
            | limit | amount    |
            | "2"   | "2"       |
            | "1"   | "1"       |
            | "4"   | "4"       |
            | "5"   | "5"       |
    