# Mastermind server

Mastermind game server API Rest

ROUTES:

`api_root          ANY      ANY      ANY    /           `       ->  App root. Contains some information abut this
api           
`list_games        GET      ANY      ANY    /game/list  `        ->List all gamens with status 'playing'        
`game_details      GET      ANY      ANY    /game/{id}   `       -> Gets game details and moves given game id     
`app_game_create   POST     ANY      ANY    /game/create `        ->Creates a new game. Pass name as post parameter (
Optional)    
`play              POST     ANY      ANY    /play  `              ->Play a round of the game. POST Valid parameters:
- gameId -> Id of the game in play
- proposedCode -> String with four of the allowe colors (red,green,blue,yellow,orange,purple) separated by a comma.

**Enjoy!**