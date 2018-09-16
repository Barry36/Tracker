<?php

abstract class operator{
        abstract protected function operation($actual_value, $target_value);
        abstract public function getOperator();
}
