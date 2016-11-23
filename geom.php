<?php
    // tag::determinant[]
    function determinant($a, $b, $c, $d)
    {
        return $a * $d - $b * $c;
    }
    // end::determinant[]

    // tag::Point[]
    class Point {
        public $x, $y;
        public function __construct($x, $y) {
            $this->x = $x;
            $this->y = $y;
        }
        static public function stdCtr(stdClass $sc) {
            $newPoint = new self($sc->x, $sc->y);
            return $newPoint;
        }
    }
    // end::Point[]

    // tag::SegmentLine[]
    class Segment {
        public $src, $tgt;
        public function __construct(stdClass $sc) {
            $this->src = Point::stdCtr($sc->src);
            $this->tgt = Point::stdCtr($sc->tgt);
        }
    }

    class Line {
        public $a, $b, $c;
        public function __construct($seg) {
            $this->a = + determinant($seg->src->y,          1.0,
                                     $seg->tgt->y,          1.0);
            $this->b = - determinant($seg->src->x,          1.0,
                                     $seg->tgt->x,          1.0);
            $this->c = + determinant($seg->src->x, $seg->src->y,
                                     $seg->tgt->x, $seg->tgt->y);
        }
    }
    // end::SegmentLine[]

    // tag::Side[]
    function oriented_side($line, $point) {
        return $line->a * $point->x + $line->b * $point->y + $line->c;
    }

    function straddle($line, $segment) {
        $src_side = oriented_side($line, $segment->src);
        $tgt_side = oriented_side($line, $segment->tgt);
        $s1 = ($src_side >= 0.0) && ($tgt_side <  0.0);
        $s2 = ($src_side <  0.0) && ($tgt_side >= 0.0);
        return $s1 || $s2;
    }
    // end::Side[]

    // tag::intersect[]
    function intersect($seg1, $seg2) {
        $l1 = new Line($seg1);
        $l2 = new Line($seg2);
        if (straddle($l1, $seg2) && straddle($l2, $seg1)) {
            $denom = determinant($l1->a, $l1->b, $l2->a, $l2->b);
            if ($denom != 0.0) {
                $detx = + determinant($l1->b, $l1->c, $l2->b, $l2->c);
                $dety = - determinant($l1->a, $l1->c, $l2->a, $l2->c);
                return array("intersection" => true,
                             "x" => $detx/$denom,
                             "y" => $dety/$denom);
            }
        }
        return array("intersection" => false);
    }        
    // end::intersect[]
?>
