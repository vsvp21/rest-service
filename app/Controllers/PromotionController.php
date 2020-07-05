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

        $response = new Response($urls, 200);

        return $response->send();
    }

    public function show()
    {
        $response = new Response();

        if(!isset($_GET['promotion'])) {
            $response->setStatusCode(404);
            $response->setContent(
                json_encode(['message' => 'Promotion Not Found'])
            );

            return $response->send();
        }

        $id = explode('-', $_GET['promotion'])[0];

        $promotion = (new Promotion())
            ->find("id = $id")
            ->get(0);
        
        if(is_null($promotion)) {
            $response->setStatusCode(404);
            $response->setContent(
                json_encode(['message' => 'Promotion Not Found'])
            );

            return $response->send();
        }

        $response->setStatusCode(200);
        $response->setContent($promotion);
        $response->setHeader('Content-Type', 'application/json');
        
        return $response->send();
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
        
        $response = new Response($promotion, 200, [
            'Content-Type' => 'application/json'
        ]);

        return $response->send();
    }
}