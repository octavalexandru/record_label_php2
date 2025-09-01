<?php
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Merchandise.php";
require_once __DIR__ . "/../models/Artist.php";


class MerchController extends Controller {
    public static function index() {
        $merch = new Merchandise();
        $items = $merch->all();
        self::view("merch/index", ["items" => $items]);
    }

    public static function show() {
        if (!isset($_GET['id'])) return self::redirect("/merch/index");

        $merchM = new Merchandise();
        $artistM = new Artist();
        $item = $merchM->find($_GET['id']);

        $artist = !empty($item['artist_id']) ? $artistM->find($item['artist_id']) : null;

        self::view("merch/show", [
            "item" => $item,
            "artist" => $artist
        ]);
    }

}
