<form action="{{BASEURL}}/courses/search" class="searchForm">
    {{-- searchBar = bar for typing search limitations and clicling search icon --}}
    <div class="flexboxCenterer">
        <div class="searchBar">
            <input type="text" name="limitations" placeholder="Search courses or teachers ...">
            <button type="submit">
                <i class="material-icons" style="">search</i>
            </button>
        </div>
    </div>
    {{-- user can select a genre of videos, form is then submitted without other parameters --}}
    {{--
    <div class="genreSelection">
        <input type="submit" name="genre" id="music" value="music">
        <input type="submit" name="genre" id="entertainment" value="entertainment">
        <input type="submit" name="genre" id="education" value="education">
    </div>
    --}}
</form>
