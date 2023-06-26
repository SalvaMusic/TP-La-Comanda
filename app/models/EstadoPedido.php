<?php

enum DetallePedido
{
    case Esperando = 'Con cliente esperando pedido';
    case Cmiendo = 'Con cliente comiendo';
    case Pagando = 'Con cliente pagando';
    case Cerrada = 'Cerrada';
}