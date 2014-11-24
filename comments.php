<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
$page = $_REQUEST['pages'];
$post_id = get_the_ID();
$comment = wp_count_comments($post_id);
$post = get_post($post_id);
$number_of_parents = c_parent_comment_counter($post_id);
$number_of_children = $post->comment_count - $number_of_parents;
$total_comment = $number_of_parents;
$total_page = ceil($total_comment/COMMENT_PER_PAGE);
include_once dirname(__FILE__) . '/inc/' . 'Custom_Walker_Comment.php';
//include_once '';


if(empty($page))
	$page = 1;
?>
<style>
.form-submit{display: none;}
</style>

	<?php if ( have_comments() ) : ?>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyfourteen' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyfourteen' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>
	
	<ol class="comment_list">
            <?php
			wp_list_comments( array(
				'walker' => new Custom_Walker_Comment(),
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 80,
				'per_page'=>COMMENT_PER_PAGE,
				'page'=>$page,
				
				'reverse_top_level'=>true,
				'callback'=>'bookstore_comment_list',
			) );
		?>
        </ol><!-- .comment-list -->
	<?php if ( $total_page>1) : ?>
	<div class="paging">
            <ul>
                <li><?php previous_comments_link( __( '&larr; Older Comments', 'twentyfourteen' ) ); ?></li>
                <?php for($i=1;$i<=$total_page;$i++):?>
                <?php 
                //$link = bloginfo('url');
                ?>
                <li><a href="<?php echo $permalink = get_permalink( $post_id ); ?>?pages=<?php echo $i?>"><?php echo $i?></a></li>
                <?php endfor;?>
                <li><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyfourteen' ) ); ?></li>
            </ul>
            <div class="clear"></div>
        </div>
	
<?php endif?>
	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'bookstore' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>
<div id="comment_form">
<?php
//$commenter = wp_get_current_commenter(); 
//$req = get_option( 'require_name_email' );
$comment_args = array( 'title_reply'=>'<h3>Leave your comment</h3>',

'fields' => apply_filters( 'comment_form_default_fields', array(

'author' => '<div class="form_row">' . '<label for="author">' . __( 'Name (* required)' ) . '</label><br/>' .

        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',   

    'email'  => '<div class="form_row">' .

                '<label for="email">' . __( 'Email (* required)' ) . '</label><br/>' .

                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />'.'</div>',

    'url'    => '' ) ),

    'comment_field' => '<div class="form_row">' .

                '<label for="comment">' . __( 'Comment' ) . '</label><br/>' .

                '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' .

                '</div>',
	'label_submit'=>'Submit',
	'id_submit'=>'submit',

    'comment_notes_after' => '<input type="submit" class="submit_btn" value="Submit" name="Submit">',

);

comment_form($comment_args); ?>

	<?php //comment_form(); ?>
</div>
