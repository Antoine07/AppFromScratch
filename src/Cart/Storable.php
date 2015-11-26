<?php namespace Cart;

interface Storable
{
    function setValue($name, $price);

    function getValue($name);

    function restore($name);

    function reset();

    function total();
}