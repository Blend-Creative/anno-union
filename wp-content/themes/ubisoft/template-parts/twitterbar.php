<?php

$tweets =  get_query_var('tweets'); 
?><section class="container">
    <div class="module module-twitterbar">
    <div class="twitter-side-logo"></div>
        <div class="twitter-posts" >
        <?php foreach($tweets as $k => $tweet): ?>
            <<?= (isset($tweet['extracted_links']) && isset($tweet['extracted_links'][0])?'a href="'.$tweet['extracted_links'][0].'" target="_blank"':'div').' class="twitter-post">'; ?>
                <?php if(isset($tweet['entities']['media']) && $tweet['entities']['media'][0]['type'] == 'photo'): ?>
                    <div class="twitter-image">
                        <img src="{{ post.entities.media[0].media_url_https }}" class="img-fluid">
                    </div>
                <?php endif ?>
                <div class="twitter-text">
                    <span>
                        <?= $tweet['text'] ?>
                    </span>
                </div>
            </<?= (isset($tweet['extracted_links']) && isset($tweet['extracted_links'][0]) ?"a":"div");?>>
        <?php endforeach; ?>
        </div>
    </div>    
</section>