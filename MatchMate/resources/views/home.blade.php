@extends('layouts.app')
@section('title', 'Home');
@section('content')
    <div class="container fs-5">
        <div class="row justify-content-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <h1>Welcome to MatchMate.</h1>
                        <p>
                            This is a page where you can find the latest
                            results, information about football teams and the status of the competition.The main purpose of
                            the site is to help visitors easily follow the results of their favourite teams and sporting
                            events.
                        </p>
                        <p> You have the following menu options:</p>
                        <div class="list-group d-flex">

                            <a class="btn btn-dark mt-4 fs-5" onclick="toggleInfoBox(1)">Games
                                <i id="icon1" class="fa-solid fa-chevron-down fa-xs"> </i></a>
                            <div id="infoBox1" style="display:none">
                                <p class="mt-3">
                                    Here you will find all sporting events for up-to-date information. A list of matches
                                    where you can easily find the games that interest you, view results, statistics and
                                    other information about the games.
                                </p>
                            </div>
                            <a class="btn btn-dark mt-4 fs-5" onclick="toggleInfoBox(2)">Teams
                                <i id="icon2" class="fa-solid fa-chevron-down fa-xs"> </i></a>
                            <div id="infoBox2" style="display:none">
                                <p class="mt-3">
                                    In this section, users can find information about the teams,
                                    including their name, shortname, and logo. On their details page,users can also view the
                                    players'
                                    performance statistics, recent matches, and upcoming games.
                                </p>
                            </div>
                            <a class="btn btn-dark mt-4 fs-5" onclick="toggleInfoBox(3)">Standings
                                <i id="icon3" class="fa-solid fa-chevron-down fa-xs"> </i></a>
                            <div id="infoBox3" style="display:none">
                                <p class="mt-3">
                                    Here you can find the standings and results of the games. The site regularly updates the
                                    tables, so you can always get up-to-date information about the competitions.
                                </p>
                            </div>
                            <a class="btn btn-dark mt-4 fs-5" onclick="toggleInfoBox(4)">Favourites
                                <i id="icon4" class="fa-solid fa-chevron-down fa-xs"> </i></a>
                            <div id="infoBox4" style="display:none">
                                <p class="mt-3"> As a logged in user, you can see your favourite teams and their games
                                    here.</p>
                            </div>

                        </div>
                        <p class="mt-5">We hope you enjoy our site and use the features mentioned above to easily find the
                            sporting events and results that interest you the most.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleInfoBox(id) {
            var infoBox = document.getElementById("infoBox" + id);
            var icon = document.getElementById("icon" + id);
            if (infoBox.style.display === "none") {
                infoBox.style.display = "block";
                icon.classList.remove("fa-chevron-down");
                icon.classList.add("fa-chevron-up");
            } else {
                infoBox.style.display = "none";
                icon.classList.remove("fa-chevron-up");
                icon.classList.add("fa-chevron-down");
            }
        }
    </script>
@endsection
