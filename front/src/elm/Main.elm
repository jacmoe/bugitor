module Main exposing (..)

import Html exposing (Html, a, div, h1, h2, h3, header, p, section, text)
import Html.Attributes exposing (class, href)


main : Html a
main =
    view model


type alias Model =
    { version : String
    , contributors : List String
    }


model : Model
model =
    { version = "1.0.0"
    , contributors = [ "Pietro Grandi", "Andrei Toma" ]
    }


view : Model -> Html a
view model =
    div [ class "container" ]
        [ header [ class "row" ]
            [ div [ class "col-xs-12 col-sm-12 col-md-12 menu" ]
                [ h1 [] [ text "Elm Quickstart" ] ]
            ]
        , section [ class "row" ]
            [ div [ class "col-xs-12 col-sm-10 col-md-8 col-lg-6" ]
                [ h2 [] [ text "Elm with Gulp and SASS configured" ]
                , h3 [] [ text ("Version " ++ model.version) ]
                , p []
                    [ text "Have a look at the "
                    , a [ href "https://github.com/pietro909/elm-quickstart/blob/master/README.md" ] [ text "readme" ]
                    , text " for more information."
                    ]
                ]
            ]
        ]
