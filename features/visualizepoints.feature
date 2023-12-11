Feature: Visualize points on map
    Map is initialized by 5 values that are the
        latitude and longitude of the bottom left corner,
        latitude and longitude of the top right corner,
        and its id.
    Markers are defined by 5 values that are 
        its position (latitude, longitude), its description,
        its color and the id of the map it needs to be
        rendered on

    Background:
        Given some maps are defined by:
            | bllatitude    | bllongitude   | trlatitude  | trlongitude | id            | markers   |
            | 42            | -5            | 51          | 10          | france        | 48.86 : 2.35 : Paris : red && 45.73 : 4.84 : Lyon : blue  |
            | 44.71         | 3.11          | 45.40       | 4.50        | hauteloire    | 45.04134 : 3.87990 : Logipro : green  |

    Scenario Outline: Display map
        When the map of <mapid> is asked for
        Then user can see a map of <mapidexpected>

        Examples:
            | mapid         | mapidexpected |
            | "france"      | "france"      |
            | "hauteloire"  | "hauteloire"  |
    
    Scenario Outline: Display markers
        When the map of <mapid> is asked for
        Then user should see <markers> 

        Examples:
            | mapid         | markers  |
            | "france"      | "48.86 : 2.35 : Paris : red && 45.73 : 4.84 : Lyon : blue"  |
            | "hauteloire"  | "45.04134 : 3.87990 : Logipro : green"  |