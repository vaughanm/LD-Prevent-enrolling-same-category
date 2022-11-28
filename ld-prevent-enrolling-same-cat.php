<?php
add_filter( 'learndash_payment_button', function( $join_button, $payment_params ) {
    $enrolled_courses = ld_get_mycourses( get_current_user_id() );
    $c_course_terms = get_the_terms( $payment_params['post']->ID, 'ld_course_category' );
    $c_terms = json_decode(json_encode($c_course_terms), true);

    $is_restricted = false;
    foreach( $enrolled_courses as $enrolled_course => $val ) {
        $terms = get_the_terms( $val, 'ld_course_category' );
        $r_terms = json_decode(json_encode($terms), true);

        foreach($r_terms as $rkey) {
            foreach($rkey as $rk => $rv) {
                if($rk !== 'term_id') {
                    unset($rk);
                } else {
                $b_terms[] = $rv;
                }
             }
        }
        foreach($c_terms as $ckey) {
            foreach($ckey as $ck => $cv) {
                if($ck !== 'term_id') {
                    unset($ck);
                } else {
                $a_terms[] = $cv;
                }
             }
        }
        if( is_array( $a_terms ) && is_array( $b_terms ) ) {
            $c = array_intersect($a_terms, $b_terms);
            if( count( $c ) > 0 ) {
                   $is_restricted = true;
            }
        }
    }
//    var_dump($c);
    if( $is_restricted ) {
        return '<div style="text-align: center;" class="learndash_join_button">You are unable to Enroll into this course as you are already enrolled into a similar course</div>';
    }
    
    return $join_button;
},999,2);
