<?php
    require_once(__DIR__ . '/lib/traits/SampleTrait.pre.php');

    class SamplePre extends XYTurn {
        use Sample\lib\traits\SampleTraitPre;
    }
?>