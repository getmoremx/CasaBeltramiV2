<?php
    /**
    * The template for displaying Comments.
    *
    * The area of the page that contains both current comments
    * and the comment form.  The actual display of comments is
    * handled by a callback to de_comment which is
    * located in the functions.php file.
    *
    */
   

    function de_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) {
            case '' : {
        ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment-body">
                        <?php if (get_avatar(get_the_author_meta('ID',$comment -> user_id)) != '') {?>
                        <div class="red-comment-thumb"><?php echo get_avatar( $comment, $size='75', $default='' ); ?></div>
                        <?php } ?>
                        <div class="red-comment-leftpointer"></div>
                        <div class="red-comment-quote">
                            <header class="red-comment-textinfo st">
                                <span class="user"><?php _e( 'by' , 'redcodn'); ?> <?php echo get_comment_author_link($comment->comment_ID); ?></span>
                                <span class="time"><?php _e( 'on' , 'redcodn'); ?> <?php printf( __( '%1$s&nbsp;&nbsp;%2$s', 'redcodn' ), get_comment_date() , get_comment_time() );  ?></span>
                                
                                <?php if ( $comment->comment_approved == '0' ) : ?>
                                    <br/><em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'redcodn' ); ?></em>
                                <?php endif; ?>
                                <span class="gray reply fr"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
                            </header>
                            <p> <?php
                                    $order   = array("\r\n", "\n", "\r");
                                    $replace = '<br />';
                                    echo str_replace($order, $replace, get_comment_text());
                                ?>
                            </p>
                        </div>
                    </article>
                </li>
        <?php
                break;
            }
            case 'pingback'  : {}
            case 'trackback' : {
        ?>
                <li class="pingback">
                    <p>
                        <?php
                            _e( 'Pingback' , 'redcodn' ); ?> : <?php comment_author_link(); ?><?php edit_comment_link( '(' . __( 'Edit' , 'redcodn' ) . ')' , ' ' );
                        ?>
                    </p>
                </li>
        <?php
                break;
            }
        }
    }

?>
<div id="comments">
<?php
    if ( post_password_required() ) {
?>
            <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'redcodn' ); ?></p>
        </div><!-- #comments -->
<?php
        /* Stop the rest of comments.php from being processed,
         * but don't kill the script entirely -- we still have
         * to fully load the template.
         */
        return;
    }
?>

<?php
    // You can start editing here -- including this comment!
?>

<?php 
    if ( have_comments() && comments_open()) { 
        $pgn = paginate_comments_links( array('prev_text' => '&laquo; Prev', 'next_text' => 'Next &raquo;' , 'format' => 'array' , 'echo' => false) );

?>
        <h3 class="comments-title" id="comments-title"><span><?php echo get_comments_number().' '; if(get_comments_number() == 1) {_e('Comment','redcodn');} else {_e('Comments','redcodn');} ?></span></h3>
<?php 
        if( strlen( $pgn ) > 0 ) {
            echo '<ul class="b_pag center p_b">';
            echo str_replace( 'next' , 'no_link' , str_replace('prev' , 'no_link' , str_replace('<a' , '<li><a' , str_replace('</a>' , '</a></li>' , str_replace( '<span' , '<li class="active"><span' , str_replace('</span>', '</span></li>' , $pgn ) ) ) ) ) );
            echo '</ul>';
        }
?>
        

        <ol class="red-comment-list red-comment-plain">
            <?php
                /* Loop through and list the comments. Tell wp_list_comments()
                 * to use de_comment() to format the comments.
                 * If you want to overload this in a child theme then you can
                 * define de_comment() and that will be used instead.
                 * See de_comment() function for more.
                 */
                wp_list_comments( array( 'callback' => 'de_comment' ) );
            ?>
        </ol>
            

<?php 
     
        if( strlen( $pgn ) > 0 ) {
            echo '<ul class="b_pag center p_b">';
            echo str_replace( 'next' , 'no_link' , str_replace('prev' , 'no_link' , str_replace('<a' , '<li><a' , str_replace('</a>' , '</a></li>' , str_replace( '<span' , '<li class="active"><span' , str_replace('</span>', '</span></li>' , $pgn ) ) ) ) ) );
            echo '</ul>';
        }

    }else{

        /* If there are no comments and comments are closed,
         * let's leave a little note, shall we?
         */
        if ( ! comments_open() ) {

        }
    }

    $commenter = wp_get_current_commenter();
    
    $fields =  array(
        'author' => '<div class="twelve columns"><p class="comment-form-author input">' . '<input class="required" placeholder="' . __( 'Your name','redcodn' ) . '" id="author" name="author" type="text" value="" size="30"  />' .
                    '</p>',
        'email'  => '<p class="comment-form-email input"><input  class="required" id="email" name="email" placeholder="' . __( 'Your email','redcodn' ) . '" type="text" value="" size="30" />' .
                    '</p>',
    );

    if( is_user_logged_in () ){
        $u_id = get_current_user_id();
    }else{
        $u_id = 0;
    }
    
    
    $leave_a_reply = __("Leave a reply",'redcodn');
    $add_comment = __("Add comment",'redcodn' );

    /*add URL for comments*/
    $fields['url'] = '<p class="comment-form-url input"><input id="url" name="url" type="text" value="" placeholder="' . __( 'Website','redcodn' ) . '" size="30" />' .
                '</p></div>';
    
    $args = array(  
        'title_reply' => '<span>'. $leave_a_reply .'</span>' ,
        'comment_notes_after' =>'',
        'comment_notes_before' =>'<div class="twelve columns"><p class="comment-notes st">' . __( 'Your email address will not be published.' , 'redcodn' ) . '</p></div>',
        //'comment_notes_before' =>'',
        'logged_in_as' =>'<div class="twelve columns"><p class="logged-in-as">' . __( 'Logged in as' ,'redcodn' ) . ' <a href="' . home_url('/wp-admin/profile.php') . '">' . get_the_author_meta( 'nickname' , get_current_user_id() ) . '</a>. <a href="' . wp_logout_url( get_permalink( $post -> ID ) ) .'" title="' . __( 'Log out of this account' , 'redcodn' ) . '">' . __( 'Log out?' , 'redcodn' ) . ' </a></p></div>',        
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        'comment_field' => '<div class="twelve columns"><p class="comment-form-comment textarea"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div>',
        'label_submit' => $add_comment
    );
    
    echo '<div class="row">';
    comment_form( $args );
    echo '</div>';
    
?>

</div><!-- #comments -->