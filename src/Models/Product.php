<?php namespace Models;
class Product extends Model
{

    protected $table = 'products';

    protected $order = 'published_at'; // id default

    protected $limit = 20;


}