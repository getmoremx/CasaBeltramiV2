<?php
    class image{

        static $size = array(
            'tsmall,tcomments'      => array( 70  , 70   , true ),
            'tmedium'               => array( 600 , 9999 , 300  ),
            'tlarge'                => array( 930 , 9999 , 300  ),
            'tvertical_style'       => array( 360, 870, true),
            'thorizontal_style'     => array( 600, 300, true),
            'releted_thumb'         => array( 360, 300, true),
            'tsingle_style'         => array( 1140, 400, true),
            'tsingle_gallery'       => array( 9999, 400, false),
        );

/*        static function add_size(){
            $size =array();
            if( function_exists( 'add_image_size' ) ){
                foreach( self::$size as $label => $args ){
                    $labels = explode( ',' , $label );
                    if( (int)$args[2] > 1 ){
                        add_image_size( $labels[0]  , $args[0] , $args[1] );
                    }else{
                        if ($label == 'tsingle_style') {
                            $args[0] = 9999;
                        }                        
                        add_image_size( $labels[0]  , $args[0] , $args[1] , $args[2] );
                    }
                }
            }
        }*/

        static function asize( $type ){
            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $type == $label ){
                            if( $args[1] == 9999 ){
                                return array( $args[0] , $args[2] );
                            }else{
                                return array( $args[0] , $args[1] );
                            }
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $type == $label ){
                        if( $args[1] == 9999 ){
                            return array( $args[0] , $args[2] );
                        }else{
                            return array( $args[0] , $args[1] );
                        }
                    }
                }
            }
        }

        static function tsize( $type ){
            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $type == $label ){
                            if( $args[1] == 9999 ){
                                return $args[0] . 'x' . $args[2];
                            }else{
                                return $args[0] . 'x' . $args[1];
                            }
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $type == $label ){
                        if( $args[1] == 9999 ){
                            return $args[0] . 'x' . $args[2];
                        }else{
                            return $args[0] . 'x' . $args[1];
                        }
                    }
                }
            }
        }

        static function size( $post_id , $template , $type = '' ){
            $size = array();

            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $template == $label ){
                            return $labels[0];
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $template == $label ){
                        return $label;
                    }
                }
            }

            if( isset( $size[ $type ]  ) ){
                return self::size( 0 , $type );
            }

            if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                return 'tlarge';
            }

            if( layout::length( $post_id , $template ) == layout::$size['medium'] ){
                return 'tmedium';
            }

            echo 'not defined size or error';
        }

        static function caption( $post_id ){
            $result = '';
            $args = array(
                'numberposts' => -1,
                'post_type' => 'attachment',
                'status' => 'publish',
                'post_mime_type' => 'image',
                'post_parent' => $post_id
            );

            $images = get_children( $args );

            if( isset( $images[ get_post_thumbnail_id( $post_id ) ] ) ){
                $result = $images[ get_post_thumbnail_id( $post_id ) ] -> post_excerpt;
            }else{
                $args = array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'status' => 'publish',
                    'post_mime_type' => 'image',
                    'post_parent' => 0
                );

                $images = get_children($args);

                if( isset( $images[  get_post_thumbnail_id( $post_id ) ] ) ){
                    $result = $images[ get_post_thumbnail_id( $post_id ) ] -> post_excerpt;
                }else{
                    $result = '';
                }
            }

            return $result;
        }

        static function thumbnail( $post_id , $template , $size = '' ){
            if( $size == 'full'){
                return wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , 'full' );
            }else{
                return wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , self::size( $post_id , $template , $size ) );
            }
        }

        static function mis( $post_id , $template , $size = '' , $classes = '' , $side = 'no.image' ){
            return '<img src="' . get_template_directory_uri() . '/images/' . $side . '.' . self::tsize( self::size( $post_id , $template , $size ) ) . '.png" class="' . $classes . '" />';
        }
    }
?>