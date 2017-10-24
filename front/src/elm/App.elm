module App exposing (init, update, view)

import Bootstrap.Grid as Grid
import Html exposing (..)
import Html.Attributes exposing (class, href)
import Http
import Json.Decode as Json


-- MODEL


type alias Model =
    { message : String
    , working : Bool
    }


init : ( Model, Cmd Msg )
init =
    ( Model "Loading..." False
    , loadData
    )



-- UPDATE


type Msg
    = FetchResponse (Result Http.Error String)


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        FetchResponse (Result.Ok str) ->
            Model str True ! []

        FetchResponse (Result.Err (Http.BadStatus err)) ->
            if err.status.code == 404 then
                Model message404 True ! []
            else
                Model (toString err) False ! []

        FetchResponse (Result.Err err) ->
            Model (toString err) False ! []


message404 =
    "I got a 404. This is the correct response if you ran serverless. Otherwise you need to check your configuration"



-- VIEW


view : Model -> Html Msg
view { message, working } =
    Grid.container []
        [ Grid.row []
            [ Grid.col []
                [ div [ class "jumbotron" ] [ text "JUmbo!" ]
                ]
            ]
        , Grid.row [] [ Grid.col [] [ h1 [] [ text "Heading" ] ] ]
        , Grid.row []
            [ Grid.col [] [ text message ]
            ]
        , Grid.row
            []
            [ Grid.col
                []
                [ text "Hi!" ]
            ]
        ]



-- COMMANDS


loadData : Cmd Msg
loadData =
    Http.get "http://bugitor.dev/v1/hello?access-token=100-token" (Json.field "message" Json.string)
        |> Http.send FetchResponse
