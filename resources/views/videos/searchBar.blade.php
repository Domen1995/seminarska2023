<form action="{{BASEURL}}/" class="searchBar">
    <input type="text" name="limitations" style="border-radius:.5rem">

    <button type="submit">
        <i class="material-icons" style="">search</i>
    </button>
    {{-- user can select a genre of videos --}}
    <div class="genreSelection">
        <input type="submit" name="genre" id="music" value="music">
        <input type="submit" name="genre" id="entertainment" value="entertainment">
        <input type="submit" name="genre" id="education" value="education">
    </div>
</form>
