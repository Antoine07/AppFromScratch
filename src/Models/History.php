<?php namespace Models;
class History extends Model
{

    protected $table = 'histories';

    protected $fillable = ['quantity', 'price', 'total', 'customer_id', 'product_id', 'commanded_at'];

}