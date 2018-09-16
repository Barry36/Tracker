<?php

Class greaterthan extends operator{
        protected  $operator_name = 'Greater Than';
        public function getOperator(){
                return $this->operator_name;
        }
        public function operation($actual_value, $target_value){
                if ($actual_value > $target_value){
                        return true;
                }else{
                        return false;
                    }
        }

}
