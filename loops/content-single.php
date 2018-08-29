<?php
/*
The Single Posts Loop
=====================
*/
?>
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
<ARTICLE role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
<DIV class="card border-light <?php if(!get_theme_mod( 'ptibogxivtheme_shadowcontent' )): ?>shadow-lg<?php endif; ?>" style="background-color: rgba(256, 256, 256, 0.8)">
<?php if ( has_post_thumbnail() ){ ?><CENTER><IMG class="card-img-top" src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id( $post ), 'ptibogxiv_large' ); ?>" alt="<?php the_title()?>"></CENTER><?php } ?>
<DIV class="card-body">
<HEADER><TABLE width="100%"><TR><TD><H1><?php the_title()?> <?php edit_post_link('<I class="fas fa-edit"></I>', '<SPAN class="edit-link">', '</SPAN>' ); ?></H1>
<H5><EM><SPAN class="text-muted author"><?php _e('By', 'ptibogxivtheme'); echo " "; the_author() ?>, </SPAN>
<TIME class="text-muted" datetime="<?php the_time()?>"> <?php the_time('d F Y') ?></TIME>
</EM></H5></TD><TD align="right"><DIV class="fa-4x text-muted"><SPAN class="fa-layers fa-fw"><I class="fas fa-comment"></I><SPAN class="fa-layers-text fa-inverse" data-fa-transform="shrink-8" style="font-weight:900"><?php comments_number('0', '1', '%'); ?></SPAN></SPAN></DIV></P>
</TD></TR></TABLE>
<HR><P class="text-muted" style="margin-bottom: 30px;">
        <I class="fas fa-folder"></I>&nbsp;
        <?php the_category(', ') ?></P>
        <?php echo ptibogxiv_social(); ?>    
</HEADER>
    <SECTION>
      <?php the_content()?>
      <?php wp_link_pages(); ?>
    </SECTION>
<?php the_terms( $post->ID, 'post_tag', '<HR><I class="fas fa-tags"></I> ', ' ', '<BR><BR>'); ?>
<?php echo ptibogxiv_social()."<BR>"; ?> 
<DIV class="jumbotron"><DIV class="row">
<DIV class="col-1 col-md-2 text-center"><?php echo get_avatar(get_the_author_meta('ID'));?></DIV><DIV class="col-11 col-md-10"><H5><?php echo _e('About', 'ptibogxivtheme'); echo ": "; the_author() ?></H5><H6><?php the_author_meta( 'description' ); ?></H6></DIV>
</DIV></DIV></DIV></DIV></ARTICLE>
  <?php comments_template('/loops/comments.php'); ?>
  <?php endwhile; ?>
  <?php else: ?>
  <?php wp_redirect(get_bloginfo('url').'/404', 404); exit; ?>
  <?php endif; ?>
