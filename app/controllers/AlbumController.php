<?php
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Album.php";
require_once __DIR__ . "/../models/Artist.php";


class AlbumController extends Controller {
    public static function index() {
        $album = new Album();
        $albums = $album->all();
        self::view("albums/index", ["albums" => $albums]);
    }

    public static function show() {
        if (!isset($_GET['id'])) return self::redirect("/albums/index");

        $albumM = new Album();
        $artistM = new Artist();
        $album = $albumM->find($_GET['id']);

        $artist = !empty($album['artist_id']) ? $artistM->find($album['artist_id']) : null;

        self::view("albums/show", [
            "album" => $album,
            "artist" => $artist
        ]);
    }

}
