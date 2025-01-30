
jQuery(document).ready(function($) {

    $('.poll-card').each(function () {
        let openText = $(this).find('.toggle-options-btn').data('open'); 
        let closeText = $(this).find('.toggle-options-btn').data('close'); 
        let card = $(this);

        $(card).find('.toggle-options-btn').on('click', function() {
            let $content = $(card).find('.poll-options'); 

            $content.slideToggle(400, () => {
                if ($content.is(':visible')) {
                    $(this).fadeIn().text(closeText); 
                } else {
                    $(this).fadeIn().text(openText); 
                }
            });
        });
    });


    $('.progress-bar').each(function() {
        let percentage = $(this).data('percentage'); 
        $(this).css('width', percentage + '%'); 
    });

    $('.vote-btn').on('click', function() {
        let button = $(this);
        let pollItem = button.closest('.poll-option');
        let pollCard = button.closest('.poll-card');
        let pollId = pollCard.data('poll-id');
        let optionIndex = pollItem.data('item-index');

        button.addClass('loading').prop('disabled', true);

        $.ajax({
            url: pollData.ajax_url,
            method: 'POST',
            data: {
                action: 'cast_vote',
                poll_id: pollId,
                item_index: optionIndex,
                nonce: pollData.nonce,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {

                    if(!response.can_vote){
                        pollCard.find('.vote-btn').hide(100);
                    } 

                    let updatedItem = response.data.updated_items[0];
                    let pollItem = pollCard.find('.poll-option[data-item-index="' + updatedItem.index + '"]');
                    pollCard.find('.poll-total-votes').text("Total Votes: " + response.data.poll_count);
                    pollItem.find('.vote-count').text("Votes: " + updatedItem.vote_count);
                    pollItem.find('.vote-percentage').text("Percentage: " + updatedItem.percentage + "%");

                    let progressBar = pollItem.find('.progress-bar');
                    progressBar.css('width', updatedItem.percentage + '%');

                    
                } else {
                    alert(response.data.message);
                }
            },
            error: function(error) {
                console.log(error);
                alert('An error occurred while processing your vote.');
            },
            complete: function() {
                button.removeClass('loading').prop('disabled', false);
            }
        });
    });




    $('.submit-multiple-btn').on('click', function() {
        var button = $(this);
        var pollCard = button.closest('.poll-card');
        var pollId = pollCard.data('poll-id');
        var selectedOptions = [];

        button.addClass('loading').prop('disabled', true);
    
        pollCard.find('.vote-checkbox:checked').each(function() {
            selectedOptions.push($(this).data('item-index'));
        });
    
        if (selectedOptions.length === 0) {
            alert('Please select at least one option.');
            return;
        }
    
        $.ajax({
            url: pollData.ajax_url,
            method: 'POST',
            data: {
                action: 'cast_vote',
                poll_id: pollId,
                item_indices: selectedOptions,
                nonce: pollData.nonce,
            },
            success: function(response) {
                console.log(response);
                if (response.success) {

                    if(!response.can_vote){
                        pollCard.find('.submit-multiple-btn').hide(100);
                        pollCard.find('.vote-checkbox').hide(100);
                    } 

                    response.data.updated_items.forEach(function(item) {
                        var pollItem = pollCard.find('.poll-option[data-item-index="' + item.index + '"]');
                        pollCard.find('.poll-total-votes').text("Total Votes: " + response.data.poll_count);
                        pollItem.find('.vote-count').text("Votes: " + item.vote_count);
                        pollItem.find('.vote-percentage').text("Percentage: " + item.percentage + "%");

                        let progressBar = pollItem.find('.progress-bar');
                        progressBar.css('width', item.percentage + '%');
                    });
                }
            },
            error: function() {
                alert('An error occurred while processing your vote.');
            },
            complete: function() {
                button.removeClass('loading').prop('disabled', false);
            }
        });
    });
    






});