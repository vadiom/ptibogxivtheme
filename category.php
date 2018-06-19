<?php get_header(); ?>

<DIV class="<?php echo esc_attr(get_theme_mod( 'ptibogxivtheme_container_type')); ?> site-content">
<DIV class="row">
<?php dynamic_sidebar('top-widget-area');?>
 </DIV>
 <DIV class="row">   
    <DIV class="<?php if(get_theme_mod( 'ptibogxivtheme_sidebar_position' )=='none'): ?>col-12<?php
    elseif(get_theme_mod( 'ptibogxivtheme_sidebar_position' )=='both'): ?>col-12 col-md-6<?php
    elseif(is_active_sidebar('sidebar-left-widget-area') OR is_active_sidebar('sidebar-right-widget-area')): ?>col-12 col-md-8<?php 
    else: ?>col-12<?php endif; ?>">
      <DIV id="content" role="main">
        <header>
          <h1>Category: <?php echo single_cat_title(); ?></h1>
          <hr>
        </header>
        <?php get_template_part('loops/content', get_post_format()); ?>
      </div><!-- /#content -->
    </div>
<?php if(is_active_sidebar('sidebar-right-widget-area') && (get_theme_mod( 'ptibogxivtheme_sidebar_position' )=='right' or get_theme_mod( 'ptibogxivtheme_sidebar_position' )=='both')){ ?>    
    <DIV class="<?php if(get_theme_mod( 'ptibogxivtheme_sidebar_position' )=='both'): ?>col-12 col-md-3<?php
    else: ?>col-12 col-md-4<?php endif; ?>" id="rightsidebar" role="navigation">
<BR><DIV class="card border-light shadow-lg" style="background-color: rgba(256, 256, 256, 0.8)"><DIV class="card-body"><?php dynamic_sidebar('sidebar-right-widget-area'); ?></DIV></DIV><BR>
    </DIV>
<?php } ?>     
<!--<div class="col-sm-4" id="sidebar" role="navigation">
       <?php //get_sidebar(); ?>
    </div> -->
    
  </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>
