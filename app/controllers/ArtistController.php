<?php
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Artist.php";
require_once __DIR__ . "/../models/Album.php";
require_once __DIR__ . "/../models/Merchandise.php";


class ArtistController extends Controller {
    public static function index() {
        $artist = new Artist();
        $artists = $artist->all();
        self::view("artists/index", ["artists" => $artists]);
    }

    public static function show() {
        if (!isset($_GET['id'])) return self::redirect("/artists/index");

        $id = (int) $_GET['id'];

        $artistM = new Artist();
        $albumM  = new Album();
        $merchM  = new Merchandise();

        $artist = $artistM->find($id);
        if (!$artist) return self::view("404");

        $albums = $albumM->findByArtist($id);
        $merch  = $merchM->findByArtist($id);

        self::view("artists/show", [
            "artist" => $artist,
            "albums" => $albums,
            "merch" => $merch
        ]);
    }
}
