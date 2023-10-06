<?php if( have_rows('content') ): ?>
    <?php while( have_rows('content') ): the_row(); ?>
    <?php $index = get_row_index(); ?>   
        <!--IMAGE LOOP-->            
        <?php if( get_row_layout() == 'text_with_image' ): 
            $title = get_sub_field('title');
            $slug = sanitize_title($title);
            $content = get_sub_field('content');
            $image = get_sub_field('image');
            $direction = get_sub_field('image_align');
            $color = get_sub_field('color');
            $order_left = ' order-first ';
            $order_right = ' order-last ';
            if($direction == 'right'){
                $order_left = ' order-last ';
                $order_right = ' order-first ';
            }
            ?>
        <div class='row topic-row <?php echo $color;?> d-flex align-items-center'>
				<div class='col-md-5<?php echo $order_left;?>'>    
                    <figure>
                        <?php echo wp_get_attachment_image( $image['ID'], 'large', '', array('class'=>'img-fluid aligncenter') ); ?>
                        <figcaption><?php echo $image['caption']; ?></figcaption>
                    </figure>
                </div>
            <div class='col-md-1 order-2'></div>
            <div class='col-md-5 <?php echo $order_right;?>'>
                <?php if($title) :?>
                    <h2 id="<?php echo $slug;?>"><?php echo $title; ?></h2>
                <?php endif;?>
                <?php echo $content; ?>
			</div>
        </div>
        <?php endif; ?>
        <!--full block loop-->
         <?php if( get_row_layout() == 'full_block' ): 
            $title = get_sub_field('title');
            $content = get_sub_field('content');
            $color = get_sub_field('color');
            $slug = sanitize_title($title);
        ?>
            <div class='row topic-row full-width-row <?php echo $color;?> d-flex align-items-center'>
				<div class='col-md-6 offset-md-3'>
                    <?php if($title):?>
                        <h2 id="<?php echo $slug?>"><?php echo $title;?></h2>
                    <?php endif;?>
                    <?php echo $content;?>
                </div>
            </div>
        <?php endif;?>
        <!--timeline loop-->
        <?php if( get_row_layout() == 'timeline' ): 
            $events = get_sub_field('events');
        ?>
            <div class="timeline">
                <?php 
                foreach ($events as $key => $event) {
                    // code...                   
                        $title = $event['title'] ? "<h2>{$event['title']}</h2>" : '';
                        $content = $event['content'];                        
                        $year = ($event['year'] > 0)  ? $event['year'] : 1;
                        $month = ($event['month'] > 0) ? $event['month'] : 1;
                        $day = ($event['day'] > 0) ? $event['day'] : 1;
                        $era = (get_field('show_era', 'options') == 'yes') ? $event['era'] : '';
                        $color = $event['color'];
                        $align = ($key % 2 == 0) ? 'right' : 'left';
                        $datetime = new DateTime();
                        $date_format = get_field('date_format', 'options');                   
                        $new_date = $datetime->createFromFormat('d/m/Y', "{$day}/{$month}/{$year}");
                        $formatted_date = $new_date->format($date_format);
                        echo "
                             <div class='block'>
                                <div class='block-content {$align} {$color}'>
                                    <div class='date'>{$formatted_date} {$era}</div>
                                    <div class='icon'></div>
                                    <div class='content'>
                                        {$title}
                                        {$content}                  
                                    </div>
                              </div>
                            </div>
                        ";
                    }                
                ?>

            </div>
        <?php endif;?>
        <!--Big Quote loop-->
         <?php if( get_row_layout() == 'big_quote' ): 
            $content = get_sub_field('quote');
            $source = get_sub_field('quote_source')
        ?>
            <div class='row topic-row full-width-row d-flex align-items-center'>
                <div class='col-md-6 offset-md-3'>                   
                    <blockquote class="big-quote">
                        <?php echo $content;?>
                        <footer><?php echo $source;?></footer>
                    </blockquote>
                </div>
            </div>
        <?php endif;?>
  
         <!--person loop-->
         <?php if( get_row_layout() == 'people' ): 
            $persons = get_sub_field('individuals');
            $title = get_sub_field('title');
            $slug = sanitize_title($title);
        ?>
            <div class='row topic-row full-width-row d-flex justify-content-around people-row'>
            <?php if($title):?>
                <div class="col-md-12">
                    <h2 id="<?php echo $slug?>"><?php echo $title;?></h2>
                </div>
            <?php endif;?>
				<?php                   
                    foreach($persons as $person){
                        $post_id = $person;
                        $name = get_the_title($post_id);
                        $title = get_field('job_title', $post_id);
                        $img = dlinq_person_thumb_check($post_id, 'medium', 'free-bio-pic img-fluid');
                        $email_html = '';
                        if(get_field('email', $post_id)){
                            $email = get_field('email', $post_id);
                            $email_html = "<a href='mailto:{$email}' aria-lable='Email to {$name}'>✉️ Connect</a>";
                        }
                        $link = get_permalink( $post_id);
                        echo "
                        <div class='col-md-4 person-holder'>
                            <div class='person-block'>
                                {$img}
                                <a href='{$link}'><h2 class='small-name'>{$name}</h2></a>
                                <div class='title'>{$title}</div>
                                <div class='small-contact'>
                                    {$email_html}
                                </div>
                            </div>
                        </div>
                        ";
                    }
                ?>
            </div>
        <?php endif;?>
         
        <!--CUSTOM POSTS BY CATEGORY-->
        <?php if( get_row_layout() == 'posts' ):
        $title = 'Learn more';
        if(get_sub_field('title')){
             $title =get_sub_field('title');
        }
        $slug = sanitize_title( $title);
        $color = get_sub_field('color');
            echo "<div class='row topic-row full-width-row {$color}'>
                    <div class='col-md-8 offset-md-2'>
                        <h2 id='{$slug}'>{$title}</h2>
                    </div>
                        ";
         
            $cats = get_sub_field('category');
            $type = get_sub_field('post_type');
            $args = array(
                'category__and' => $cats,
                'post_type' => $type,
                'posts_per_page' => 10,
                'paged' => get_query_var('paged')
            );
            $the_query = new WP_Query( $args );

            // The Loop
            if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                // Do Stuff
                $title = get_the_title();
                $url = get_the_permalink();
                $name = get_field('name');
                $class =  substr(get_field('class'), -2);
                $class_span = get_field('class') ? "<span class='class-year'>'{$class}</span>" : '';
                if(get_the_content()){
                     $excerpt = get_the_content();
                }
                if(get_field('project_summary')){
                   $excerpt =  wp_trim_words(get_field('project_summary'), 30); 
                }
                if(in_category('tree-house-memory')){
                      echo "
                            <div class='col-md-8 offset-md-2'>
                                <div class='post-block memory class-of-{$class}'>
                                        <div class='memory-blurb'><p>{$excerpt}</p></div>
                                        <div class='memory-giver'>{$name} {$class_span}</div>
                                </div>
                            </div>
                        ";

                } else {
                      echo "
                            <div class='col-md-8 offset-md-2'>
                                <div class='post-block'>
                                        <h3>{$title}</h3>                           
                                        <p>{$excerpt}</p>
                                </div>
                            </div>
                        ";

                }
               
              
                endwhile;
            endif;

            // Reset Post Data
            wp_reset_postdata();
            echo "</div>";
        ?>
        <?php endif;?>
    <?php endwhile; ?>
<?php endif; ?>