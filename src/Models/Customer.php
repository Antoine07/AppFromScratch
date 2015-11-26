<?php namespace Models;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = ['email', 'number_card', 'address', 'number_command'];
}