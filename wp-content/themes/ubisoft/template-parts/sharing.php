<?php
/**
 * Template part for displaying the sharing module
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ubisoft
 */

global $wp;
$link = home_url(add_query_arg(array(), $wp->request));

?>
<div class="sharing" data-sharing>
    <ul>
        <li>
            <button type="button" class="sharing__button" title="<?php _e('Copy Link', 'ubisoft'); ?>" data-sharing-copy="<?php echo $link; ?>" data-toggle="tooltip" data-placement="bottom" data-title="<?php _e('Copied!', 'ubisoft'); ?>">
                <svg width="32" height="32" viewBox="0 0 32 32">
                    <rect width="32" height="32" rx="16" fill="currentColor"/>
                    <path d="M8.22172 23.778C8.68559 24.2425 9.23669 24.6108 9.84334 24.8617C10.45 25.1126 11.1002 25.2411 11.7567 25.24C12.4133 25.2411 13.0637 25.1125 13.6705 24.8617C14.2774 24.6108 14.8286 24.2425 15.2927 23.778L18.1207 20.949L16.7067 19.535L13.8787 22.364C13.3152 22.925 12.5524 23.2399 11.7572 23.2399C10.962 23.2399 10.1992 22.925 9.63572 22.364C9.07422 21.8007 8.75892 21.0378 8.75892 20.2425C8.75892 19.4471 9.07422 18.6842 9.63572 18.121L12.4647 15.293L11.0507 13.879L8.22172 16.707C7.28552 17.6454 6.75977 18.9169 6.75977 20.2425C6.75977 21.568 7.28552 22.8395 8.22172 23.778ZM23.7777 15.293C24.7134 14.3542 25.2388 13.0829 25.2388 11.7575C25.2388 10.432 24.7134 9.16068 23.7777 8.22196C22.8393 7.28577 21.5678 6.76001 20.2422 6.76001C18.9166 6.76001 17.6452 7.28577 16.7067 8.22196L13.8787 11.051L15.2927 12.465L18.1207 9.63596C18.6842 9.07495 19.447 8.75999 20.2422 8.75999C21.0374 8.75999 21.8002 9.07495 22.3637 9.63596C22.9252 10.1992 23.2405 10.9621 23.2405 11.7575C23.2405 12.5528 22.9252 13.3157 22.3637 13.879L19.5347 16.707L20.9487 18.121L23.7777 15.293Z" fill="#D4B99B"/>
                    <path d="M12.4637 20.95L11.0487 19.536L19.5357 11.05L20.9497 12.465L12.4637 20.95Z" fill="#D4B99B"/>
                </svg>
            </button>
        </li>
        <li>
            <a href="https://x.com/intent/post?url=<?php echo rawurlencode($link); ?>" class="sharing__button" target="_blank" rel="noopener" title="<?php _e('Share on X', 'ubisoft'); ?>">
                <svg width="32" height="32" viewBox="0 0 32 32">
                    <rect width="32" height="32" rx="16" fill="currentColor"/>
                    <path d="M21.1761 8.24268H23.9362L17.9061 15.0201L25 24.2427H19.4456L15.0951 18.6493L10.1172 24.2427H7.35544L13.8052 16.9935L7 8.24268H12.6954L16.6279 13.3553L21.1761 8.24268ZM20.2073 22.6181H21.7368L11.8644 9.78196H10.2232L20.2073 22.6181Z" fill="#D4B99B"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode($link); ?>" target="_blank" rel="noopener" class="sharing__button" title="<?php _e('Share on Facebook', 'ubisoft'); ?>">
                <svg width="32" height="32" viewBox="0 0 32 32">
                    <rect width="32" height="32" rx="16" fill="currentColor"/>
                    <path d="M26 16.3038C26 10.7472 21.5229 6.24268 16 6.24268C10.4771 6.24268 6 10.7472 6 16.3038C6 21.3255 9.65684 25.4879 14.4375 26.2427V19.2121H11.8984V16.3038H14.4375V14.0872C14.4375 11.5656 15.9305 10.1728 18.2146 10.1728C19.3088 10.1728 20.4531 10.3693 20.4531 10.3693V12.8453H19.1922C17.95 12.8453 17.5625 13.6209 17.5625 14.4166V16.3038H20.3359L19.8926 19.2121H17.5625V26.2427C22.3432 25.4879 26 21.3257 26 16.3038Z" fill="#D4B99B"/>
                </svg>
            </a>
        </li>
    </ul>
</div>
