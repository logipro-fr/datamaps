Feature: Visualize points on map
    Map consists of an aerial map with or without marker.
    Map is initialized by 5 values that are the
        latitude and longitude of the bottom left corner,
        latitude and longitude of the top right corner,
        and its id.
    Markers are defined by 5 values that are 
        its position (latitude, longitude), its description,
        its color and the id of the map it needs to be
        rendered on

    Background:
        Given markers to display are located at :
            | latitude  | longitude | mapid         | description               | color     |
            | 48.86     | 2.35      | france        | Paris                     | red       |
            | 45.73     | 4.84      | france        | Lyon                      | blue      |
            | 47.22     | -1.54     | france        | Nantes                    | blue      |
            | 45.04134  | 3.87990   | hauteloire    | Logipro                   | green     |
            | 45.04308  | 3.88361   | hauteloire    | Mairie du Puy en Velay    | green     |
        And some maps are defined by:
            | bottomleftlatitude    | bottomleftlongitude   | toprightlatitude  | toprightlongitude | id            |
            | 42                    | -5                    | 51                | 10                | france        |
            | 44.71                 | 3.11                  | 45.40             | 4.50              | hauteloire    |

    # See the map
    Scenario Outline: Display map
        When the map of <mapid> is asked for
        Then user can see a map of <mapidexpected>

        Examples:
            | mapid         | mapidexpected |
            | "france"      | "france"      |
            | "hauteloire"  | "hauteloire"  |
    
    # See points as markers
    Scenario Outline: Display markers
        When the map of <mapid> is asked for
        Then user can see markers on <mapidexpected>

        Examples:
            | mapid         | mapidexpected |
            | "france"      | "france"      |
            | "hauteloire"  | "hauteloire"  |