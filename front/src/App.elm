module App exposing (init, update, view)

import Html exposing (..)
import Html.Attributes exposing (href)
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
    div []
        [ h1 [] [ text "Elm-fullstack by Simon Hamptonn" ]
        , p [] [ text message ]
        , if working then
            div []
                [ text "At this stage, many people head over to "
                , a [ href "https://github.com/simonh1000/elm-fullstack-starter" ]
                    [ text "Github" ]
                , text " to star this repo!"
                ]
          else
            text ""
        ]



-- COMMANDS


loadData : Cmd Msg
loadData =
    Http.get "http://bugitor.dev/v1/hello?access-token=100-token" (Json.field "message" Json.string)
        |> Http.send FetchResponse
