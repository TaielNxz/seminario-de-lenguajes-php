<?php
    class menuController{
        
        public function __construct($depValidator){
            $this->validator = $depValidator;
        }

        public function createItem(Request $request, Response $response, array $args){
            $valor = write("Se crea algo aqui");
            return $valor;
        }
    }
?>