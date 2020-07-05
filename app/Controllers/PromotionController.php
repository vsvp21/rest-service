<?php

namespace App\Controllers;

use App\Models\Promotion;
use Core\Http\Response;
use Core\Support\Collection;

class PromotionController
{
    public function index()
    {
        $promotions = (new Promotion())
            ->select([
                'id',
                'name',
                'starts_at',
                'ends_at',
                'status'
            ])
            ->fetch();
        
        $urls = new Collection();
        foreach($promotions as $promotion) {
            $urls->push(url('promotions?promotion=' . $promotion->buildUrl()));
        }

        echo $urls;
    }

    public function show()
    {
        if(!isset($_GET['promotion'])) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['message' => 'Promotion not found']);
            die();
        }

        $id = explode('-', $_GET['promotion'])[0];

        $promotion = (new Promotion())
            ->find("id = $id")
            ->get(0);

        if(is_null($promotion)) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['message' => 'Promotion not found']);
            die();
        }

        echo $promotion;
    }

    public function randomUpdate() 
    {
        $promotion = (new Promotion())
            ->select([
                'id',
                'name',
                'starts_at',
                'ends_at',
                'status'
            ])
            ->orderBy('', 'RAND()')
            ->limit(1)
            ->fetch()
            ->get(0);

        $promotion->status = $promotion->status == 0 ? 1 : 0;
        $promotion->save($update = true);
        
        echo $promotion;
    }

    public function lol()
    {
        $collection = new Collection([
            'a' => 111,
            'b' => 222
        ]);

        $response = new Response($collection, 201, [
            'Content-Type' => 'application/json',
            'Charset' => 'UTF-8',
        ]);

        return $response->send();
    }
}